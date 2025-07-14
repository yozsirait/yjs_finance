<?php

namespace App\Http\Controllers;
use App\Models\RecurringExpense;

use Illuminate\Http\Request;

class RecurringExpenseController extends Controller
{
    public function index()
    {
        $rutin = RecurringExpense::where('user_id', auth()->id())->get();
        $accounts = auth()->user()->accounts;
        $members = auth()->user()->members;
        $categories = auth()->user()->categories()->where('type', 'pengeluaran')->get();

        return view('pengeluaran_rutin.index', compact('rutin', 'accounts', 'members', 'categories'));
    }

    public function store(Request $request)
    {
        $amount = (int) str_replace(['.', ','], '', $request->amount);
        $request->merge(['amount_clean' => $amount]);

        $request->validate([
            'name' => 'required|string',
            'category' => 'required|string',
            'account_id' => 'nullable|exists:accounts,id',
            'member_id' => 'nullable|exists:members,id',
            'start_date' => 'required|date',
            'interval' => 'required|in:harian,mingguan,bulanan,tahunan',
            'amount_clean' => 'required|numeric|min:1000',
        ]);

        RecurringExpense::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'type' => 'pengeluaran',
            'category' => $request->category,
            'account_id' => $request->account_id,
            'member_id' => $request->member_id,
            'start_date' => $request->start_date,
            'interval' => $request->interval,
            'description' => $request->description,
            'amount' => $amount,
        ]);

        return back()->with('success', 'Pengeluaran rutin berhasil ditambahkan.');
    }
}
