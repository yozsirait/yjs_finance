<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CategoryBudget;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $now = now();

        // Saldo bulan ini
        $totalPemasukan = $user->transactions()
            ->where('type', 'pemasukan')
            ->whereMonth('date', $now->month)
            ->whereYear('date', $now->year)
            ->sum('amount');

        $totalPengeluaran = $user->transactions()
            ->where('type', 'pengeluaran')
            ->whereMonth('date', $now->month)
            ->whereYear('date', $now->year)
            ->sum('amount');

        $totalSaldoAkun = $user->accounts()->sum('balance');
        $saldoBulanIni = $totalPemasukan - $totalPengeluaran;

        // Perbandingan bulanan
        $monthly = collect(range(1, 12))->mapWithKeys(function ($month) use ($user, $now) {
            $trx = $user->transactions()
                ->selectRaw("type, SUM(amount) as total")
                ->whereYear('date', $now->year)
                ->whereMonth('date', $month)
                ->groupBy('type')
                ->get()
                ->keyBy('type');

            return [$month => [
                'pemasukan' => $trx['pemasukan']->total ?? 0,
                'pengeluaran' => $trx['pengeluaran']->total ?? 0,
            ]];
        });

        // Transaksi terakhir
        $latestTransactions = $user->transactions()
            ->whereNotIn('category', ['Mutasi Masuk', 'Mutasi Keluar'])
            ->with(['member', 'account'])
            ->latest('date')
            ->take(5)
            ->get();

        // Notifikasi kategori overbudget bulan ini
        $overbudgetCategories = [];

        $budgets = CategoryBudget::where('user_id', $user->id)
            ->where('month', $now->month)
            ->where('year', $now->year)
            ->get();

        foreach ($budgets as $budget) {
            $totalSpent = $user->transactions()                
                ->where('type', $budget->type)
                ->where('category', $budget->category)
                ->whereMonth('date', $now->month)
                ->whereYear('date', $now->year)
                ->sum('amount');

            if ($totalSpent > $budget->amount) {
                $overbudgetCategories[] = [
                    'name' => $budget->category,
                    'type' => $budget->type,
                    'budget' => $budget->amount,
                    'spent' => $totalSpent,
                ];
            }
        }

        return view('dashboard', compact(
            'totalPemasukan',
            'totalPengeluaran',
            'saldoBulanIni',
            'totalSaldoAkun',
            'monthly',
            'latestTransactions',
            'overbudgetCategories'
        ));
    }
}
