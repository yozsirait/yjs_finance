<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MutationTransactionController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $month = $request->month ?? now()->month;
        $year = $request->year ?? now()->year;
        $member = $request->member;

        $query = $user->transactions()
            ->whereIn('category', ['Mutasi Masuk', 'Mutasi Keluar'])
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->with(['member', 'account'])
            ->latest('date');

        if ($member) {
            $query->where('member_id', $member);
        }

        $transactions = $query->get();

        $members = $user->members;
        $availableYears = $user->transactions()
            ->selectRaw('YEAR(date) as year')
            ->distinct()
            ->pluck('year')
            ->sortDesc();

        return view('transaksi.mutasi', compact('transactions', 'month', 'year', 'member', 'members', 'availableYears'));
    }
}
