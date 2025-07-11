<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Transaction;
use Illuminate\Http\Request;


class TransactionController extends Controller
{
    public function create(Request $request)
    {
        $members = auth()->user()->members;
        return view('transaksi.create', compact('members'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:members,id',
            'type'      => 'required|in:pemasukan,pengeluaran',
            'amount'    => 'required|numeric|min:0',
            'date'      => 'required|date',
            'category'  => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        Transaction::create([
            'user_id'    => auth()->id(),
            'member_id'  => $request->member_id,
            'type'       => $request->type,
            'amount' => str_replace('.', '', $request->amount),
            'date'       => $request->date,
            'category'   => $request->category,
            'description' => $request->description,
        ]);

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil disimpan!');
    }

    public function index(Request $request)
    {
        $query = auth()->user()
            ->transactions()
            ->with('member');

        // Filter
        if ($request->filled('month')) {
            $query->whereMonth('date', $request->month);
        }

        if ($request->filled('year')) {
            $query->whereYear('date', $request->year);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('member')) {
            $query->where('member_id', $request->member);
        }

        $transactions = $query->latest()->get();

        // Ambil tahun unik dari data
        $availableYears = auth()->user()
            ->transactions()
            ->selectRaw('YEAR(date) as year')
            ->distinct()
            ->pluck('year')
            ->sortDesc();

        $members = auth()->user()->members;

        return view('transaksi.index', compact('transactions', 'members', 'availableYears'));
    }

    public function edit($id)
    {
        $transaction = auth()->user()->transactions()->findOrFail($id);
        $members = auth()->user()->members;
        $categories = auth()->user()
            ->categories()
            ->where('type', $transaction->type)
            ->get();

        return view('transaksi.edit', compact('transaction', 'members', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'date'        => 'required|date',
            'type'        => 'required|in:pemasukan,pengeluaran',
            'member_id'   => 'required|exists:members,id',
            'category'    => 'nullable|string|max:100',
            'amount'      => 'required|string',
            'description' => 'nullable|string|max:255',
        ]);

        $transaction = auth()->user()->transactions()->findOrFail($id);

        $transaction->update([
            'member_id'   => $request->member_id,
            'type'        => $request->type,
            'category'    => $request->category,
            'date'        => $request->date,
            'amount'      => str_replace('.', '', $request->amount),
            'description' => $request->description,
        ]);

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil diupdate.');
    }

    public function destroy($id)
    {
        $transaction = auth()->user()->transactions()->findOrFail($id);
        $transaction->delete();

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus.');
    }
}
