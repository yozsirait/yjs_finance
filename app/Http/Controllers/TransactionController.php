<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Transaction;
use App\Models\Account;
use Illuminate\Http\Request;
use App\Services\TransactionService;

class TransactionController extends Controller
{
    public function create()
    {
        $members = auth()->user()->members;
        $accounts = auth()->user()->accounts;

        // Ambil default kategori berdasarkan tipe awal (pemasukan)
        $type = request()->old('type', 'pemasukan');

        $categories = auth()->user()
            ->categories()
            ->where('type', $type)
            ->get();

        return view('transaksi.create', compact('members', 'accounts', 'categories', 'type'));
    }

    public function store(Request $request, TransactionService $service)
    {
        $validated = $request->validate([
            'member_id'  => 'required|exists:members,id',
            'type'       => 'required|in:pemasukan,pengeluaran',
            'account_id' => 'required|exists:accounts,id',
            'amount'     => 'required|numeric|min:0',
            'date'       => 'required|date',
            'category'   => 'required|string',
            'description' => 'nullable|string',
        ]);

        $service->create($validated);

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

        if ($request->filled('account_id')) {
            $query->where('account_id', $request->account_id);
        }

        // 👇 gunakan query yang sudah difilter
        $transactions = $query
            ->whereNotIn('category', ['Mutasi Masuk', 'Mutasi Keluar'])
            ->with('account', 'member')
            ->latest('date')
            ->get();

        // Tahun unik untuk filter
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

    public function update(Request $request, $id, TransactionService $service)
    {
        $validated = $request->validate([
            'date'        => 'required|date',
            'type'        => 'required|in:pemasukan,pengeluaran',
            'account_id'  => 'required|exists:accounts,id',
            'member_id'   => 'required|exists:members,id',
            'category'    => 'required|string|max:100',
            'amount'      => 'required|string',
            'description' => 'nullable|string|max:255',
        ]);

        $transaction = auth()->user()->transactions()->findOrFail($id);

        $service->update($transaction, $validated);

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil diupdate.');
    }

    public function destroy($id, TransactionService $service)
    {
        $transaction = auth()->user()->transactions()->findOrFail($id);
        $service->delete($transaction);

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus.');
    }

    public function duplicate($id)
    {
        $original = auth()->user()->transactions()->findOrFail($id);
        $members = auth()->user()->members;

        $categories = auth()->user()
            ->categories()
            ->where('type', $original->type)
            ->get();

        return view('transaksi.create', [
            'members'    => $members,
            'categories' => $categories,
            'type'       => $original->type,
            'duplicate'  => $original,
        ]);
    }
}
