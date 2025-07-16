<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CategoryBudget;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $now  = now();
        $memberId = $request->member_id;

        /* ─── Saldo bulan ini ───────────────────────────────────────────── */
        $trxQuery = $user->transactions()
            ->whereMonth('date', $now->month)
            ->whereYear('date',  $now->year);

        if ($memberId) {
            $trxQuery->where('member_id', $memberId);
        }

        $totalPemasukan = (clone $trxQuery)->where('type', 'pemasukan')->sum('amount');
        $totalPengeluaran = (clone $trxQuery)->where('type', 'pengeluaran')->sum('amount');
        $saldoBulanIni = $totalPemasukan - $totalPengeluaran;

        $accountQuery = $user->accounts();
        if ($memberId) {
            $accountQuery->where('member_id', $memberId);
        }
        $totalSaldoAkun = $accountQuery->sum('balance');

        /* ─── Perbandingan bulanan (bar chart) ──────────────────────────── */
        $monthly = collect(range(1, 12))->mapWithKeys(function ($month) use ($user, $now, $memberId) {
            $trx = $user->transactions()
                ->selectRaw('type, SUM(amount) AS total')
                ->whereYear('date', $now->year)
                ->whereMonth('date', $month);

            if ($memberId) {
                $trx->where('member_id', $memberId);
            }

            $trx = $trx->groupBy('type')->pluck('total', 'type');

            return [$month => [
                'pemasukan'   => $trx->get('pemasukan', 0),
                'pengeluaran' => $trx->get('pengeluaran', 0),
            ]];
        });

        /* ─── Saldo harian 30 hari (line chart) ─────────────────────────── */
        $period = CarbonPeriod::between(
            $now->copy()->subDays(29)->startOfDay(),
            $now->copy()->startOfDay()
        );

        $txLast30 = $user->transactions()
            ->whereBetween('date', [$now->copy()->subDays(29)->startOfDay(), $now->endOfDay()]);

        if ($memberId) {
            $txLast30->where('member_id', $memberId);
        }

        $txLast30 = $txLast30->orderBy('date')->get()->groupBy(fn($t) => $t->date->toDateString());

        $runningSaldo = 0;
        $last30days = collect($period)->map(function ($date) use (&$runningSaldo, $txLast30) {
            $dayTx = $txLast30->get($date->toDateString(), collect());
            $runningSaldo += $dayTx->where('type', 'pemasukan')->sum('amount')
                - $dayTx->where('type', 'pengeluaran')->sum('amount');

            return ['date' => $date->toDateString(), 'saldo' => $runningSaldo];
        });

        /* ─── Transaksi terbaru (kec. mutasi) ───────────────────────────── */
        $latestTransactionsQuery = $user->transactions()
            ->whereNotIn('category', ['Mutasi Masuk', 'Mutasi Keluar'])
            ->with(['member', 'account']);

        if ($memberId) {
            $latestTransactionsQuery->where('member_id', $memberId);
        }

        $latestTransactions = $latestTransactionsQuery
            ->latest('date')
            ->take(5)
            ->get();


        /* ─── Notifikasi kategori over‑budget bulanan ───────────────────── */
        $overbudgetCategories = [];

        $budgets = CategoryBudget::where('user_id', $user->id)
            ->where('month', $now->month)
            ->where('year',  $now->year)
            ->get();

        foreach ($budgets as $budget) {
            $spentQuery = $user->transactions()
                ->where('type', $budget->type)
                ->where('category', $budget->category)
                ->whereMonth('date', $now->month)
                ->whereYear('date',  $now->year);

            if ($memberId) {
                $spentQuery->where('member_id', $memberId);
            }

            $spent = $spentQuery->sum('amount');

            if ($spent > $budget->amount) {
                $overbudgetCategories[] = [
                    'name'   => $budget->category,
                    'type'   => $budget->type,
                    'budget' => $budget->amount,
                    'spent'  => $spent,
                ];
            }
        }

        /* ─── Kirim ke view ─────────────────────────────────────────────── */
        return view('dashboard', compact(
            'totalPemasukan',
            'totalPengeluaran',
            'saldoBulanIni',
            'totalSaldoAkun',
            'monthly',
            'last30days',
            'latestTransactions',
            'overbudgetCategories'
        ));
    }
}
