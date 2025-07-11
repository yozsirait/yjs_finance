<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $now = now();

        // Saldo
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
            ->with(['member', 'account'])
            ->latest('date')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalPemasukan',
            'totalPengeluaran',
            'saldoBulanIni',
            'totalSaldoAkun',
            'monthly',
            'latestTransactions'
        ));
    }
}
