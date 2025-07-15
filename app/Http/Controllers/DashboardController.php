<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CategoryBudget;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $now  = now();

        /* ─── Saldo bulan ini ───────────────────────────────────────────── */
        $totalPemasukan   = $user->transactions()
            ->where('type', 'pemasukan')
            ->whereMonth('date', $now->month)
            ->whereYear('date',  $now->year)
            ->sum('amount');

        $totalPengeluaran = $user->transactions()
            ->where('type', 'pengeluaran')
            ->whereMonth('date', $now->month)
            ->whereYear('date',  $now->year)
            ->sum('amount');

        $saldoBulanIni    = $totalPemasukan - $totalPengeluaran;
        $totalSaldoAkun   = $user->accounts()->sum('balance');

        /* ─── Perbandingan bulanan (bar chart) ──────────────────────────── */
        $monthly = collect(range(1, 12))->mapWithKeys(function ($month) use ($user, $now) {
            $trx = $user->transactions()
                ->selectRaw('type, SUM(amount) AS total')
                ->whereYear('date', $now->year)
                ->whereMonth('date', $month)
                ->groupBy('type')
                ->pluck('total', 'type');   // ['pemasukan' => xxx, 'pengeluaran' => yyy]

            return [$month => [
                'pemasukan'   => $trx->get('pemasukan',   0),
                'pengeluaran' => $trx->get('pengeluaran', 0),
            ]];
        });

        /* ─── Saldo harian 30 hari (line chart) ─────────────────────────── */
        $dates = collect(range(0, 29))->map(fn($i) => $now->copy()->subDays(29 - $i)->startOfDay());

        // ambil semua transaksi 30 hari ke belakang sekali query
        $txLast30 = $user->transactions()
            ->whereBetween('date', [$now->copy()->subDays(29)->startOfDay(), $now->endOfDay()])
            ->orderBy('date')
            ->get()
            ->groupBy(fn($t) => $t->date->toDateString());

        $period = CarbonPeriod::between(
            $now->copy()->subDays(29)->startOfDay(),
            $now->copy()->startOfDay()
        );
        
        $runningSaldo = 0;
        $last30days = collect($period)->map(function ($date) use (&$runningSaldo, $txLast30) {
            $dayTx   = $txLast30->get($date->toDateString(), collect());            
            $runningSaldo += $dayTx->where('type', 'pemasukan')->sum('amount')
                -  $dayTx->where('type', 'pengeluaran')->sum('amount');

            return ['date' => $date->toDateString(), 'saldo' => $runningSaldo];
        });

        

        /* ─── Transaksi terbaru (kec. mutasi) ───────────────────────────── */
        $latestTransactions = $user->transactions()
            ->whereNotIn('category', ['Mutasi Masuk', 'Mutasi Keluar'])
            ->with(['member', 'account'])
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
            $spent = $user->transactions()
                ->where('type',     $budget->type)
                ->where('category', $budget->category)
                ->whereMonth('date', $now->month)
                ->whereYear('date',  $now->year)
                ->sum('amount');

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
            'last30days',          // <— tambah untuk line chart saldo harian
            'latestTransactions',
            'overbudgetCategories'
        ));
    }
}
