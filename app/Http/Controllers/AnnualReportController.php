<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AnnualReportController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $year = $request->year ?? now()->year;
        $memberId = $request->member;

        // Ambil data transaksi per bulan dan jenis
        $query = $user->transactions()
            ->whereYear('date', $year);

        if ($memberId) {
            $query->where('member_id', $memberId);
        }

        $data = $query->selectRaw('MONTH(date) as month, type, SUM(amount) as total')
            ->groupByRaw('MONTH(date), type')
            ->get();

        // Buat struktur data bulanan
        $monthlyData = collect(range(1, 12))->mapWithKeys(function ($month) use ($data) {
            $pemasukan = $data->firstWhere(fn($d) => $d->month == $month && $d->type == 'pemasukan')?->total ?? 0;
            $pengeluaran = $data->firstWhere(fn($d) => $d->month == $month && $d->type == 'pengeluaran')?->total ?? 0;

            return [$month => [
                'pemasukan' => $pemasukan,
                'pengeluaran' => $pengeluaran,
                'sisa' => $pemasukan - $pengeluaran
            ]];
        });

        // Ambil semua anggota
        $members = $user->members;

        // Ambil tahun-tahun yang tersedia dari data transaksi user
        $availableYears = $user->transactions()
            ->selectRaw('YEAR(date) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        // Hitung total tahunan
        $totalPemasukan = $monthlyData->sum(fn($m) => $m['pemasukan']);
        $totalPengeluaran = $monthlyData->sum(fn($m) => $m['pengeluaran']);

        return view('laporan.tahunan', compact(
            'year',
            'monthlyData',
            'members',
            'availableYears',
            'memberId',
            'totalPemasukan',
            'totalPengeluaran'
        ));
    }
}
