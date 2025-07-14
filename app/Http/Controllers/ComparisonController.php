<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ComparisonController extends Controller
{
    // Perbandingan antar bulan (fitur sebelumnya)
    public function bulan(Request $request)
    {
        $user = auth()->user();

        $bulan1 = $request->bulan1 ?? now()->month;
        $tahun1 = $request->tahun1 ?? now()->year;

        $bulan2 = $request->bulan2 ?? now()->subMonth()->month;
        $tahun2 = $request->tahun2 ?? now()->subMonth()->year;

        $data1 = $user->transactions()
            ->whereMonth('date', $bulan1)
            ->whereYear('date', $tahun1)
            ->selectRaw('type, SUM(amount) as total')
            ->groupBy('type')
            ->pluck('total', 'type');

        $data2 = $user->transactions()
            ->whereMonth('date', $bulan2)
            ->whereYear('date', $tahun2)
            ->selectRaw('type, SUM(amount) as total')
            ->groupBy('type')
            ->pluck('total', 'type');

        $pemasukan1 = $data1['pemasukan'] ?? 0;
        $pengeluaran1 = $data1['pengeluaran'] ?? 0;
        $sisa1 = $pemasukan1 - $pengeluaran1;

        $pemasukan2 = $data2['pemasukan'] ?? 0;
        $pengeluaran2 = $data2['pengeluaran'] ?? 0;
        $sisa2 = $pemasukan2 - $pengeluaran2;

        return view('laporan.bulanan', compact(
            'bulan1',
            'tahun1',
            'bulan2',
            'tahun2',
            'pemasukan1',
            'pengeluaran1',
            'sisa1',
            'pemasukan2',
            'pengeluaran2',
            'sisa2'
        ));
    }

    // Perbandingan antar anggota keluarga
    public function member(Request $request)
    {
        $user = auth()->user();
        $bulan = $request->bulan ?? now()->month;
        $tahun = $request->tahun ?? now()->year;

        $perMember = $user->members->map(function ($member) use ($bulan, $tahun) {
            $trx = $member->transactions()
                ->whereMonth('date', $bulan)
                ->whereYear('date', $tahun)
                ->selectRaw('type, SUM(amount) as total')
                ->groupBy('type')
                ->pluck('total', 'type');

            $pemasukan = $trx['pemasukan'] ?? 0;
            $pengeluaran = $trx['pengeluaran'] ?? 0;

            return [
                'name' => $member->name,
                'pemasukan' => $pemasukan,
                'pengeluaran' => $pengeluaran,
                'saldo' => $pemasukan - $pengeluaran,
            ];
        });

        return view('laporan.member', compact('perMember', 'bulan', 'tahun'));
    }
}
