<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Transaction;
use App\Models\Account;
use Illuminate\Http\Request;


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

    public function store(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:members,id',
            'type'      => 'required|in:pemasukan,pengeluaran',
            'account_id' => 'required|exists:accounts,id', // ✅
            'amount'    => 'required|numeric|min:0',
            'date'      => 'required|date',
            'category'  => 'required|string',
            'description' => 'nullable|string',
        ]);

        Transaction::create([
            'user_id'    => auth()->id(),
            'member_id'  => $request->member_id,
            'type'       => $request->type,
            'account_id' => $request->account_id,
            'amount'     => str_replace(['.', ','], '', $request->amount), // bersihkan format Rp            
            'date'       => $request->date,
            'category'   => $request->category,
            'description' => $request->description,
        ]);

        $account = Account::find($request->account_id);
        if ($account) {
            $account->updateBalance();
        }


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
            ->with('account')
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

    public function update(Request $request, $id)
    {
        $request->validate([
            'date'        => 'required|date',
            'type'        => 'required|in:pemasukan,pengeluaran',
            'account_id'  => 'required|exists:accounts,id', // ✅
            'member_id'   => 'required|exists:members,id',
            'category'    => 'required|string|max:100',
            'amount'      => 'required|string',
            'description' => 'nullable|string|max:255',
        ]);

        $transaction = auth()->user()->transactions()->findOrFail($id);
        $oldAccountId = $transaction->account_id;

        $transaction->update([
            'member_id'   => $request->member_id,
            'type'        => $request->type,
            'account_id'  => $request->account_id,
            'category'    => $request->category,
            'date'        => $request->date,
            'amount'     => str_replace(['.', ','], '', $request->amount), // bersihkan format Rp            
            'description' => $request->description,
        ]);

        if ($oldAccountId != $request->account_id) {
            Account::find($oldAccountId)?->updateBalance();
            Account::find($request->account_id)?->updateBalance();
        } else {
            Account::find($request->account_id)?->updateBalance();
        }

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil diupdate.');
    }

    public function destroy($id)
    {
        $transaction = auth()->user()->transactions()->findOrFail($id);        
        $account = $transaction->account;
        $transaction->delete();
        $account?->updateBalance();


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
