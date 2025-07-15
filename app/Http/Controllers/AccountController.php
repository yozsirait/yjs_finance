<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Category;
use App\Models\Member;
use App\Models\Transaction;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {   
        $accounts = auth()->user()->accounts()->with('member')->get();
        return view('akun.index', compact('accounts'));
    }

    public function create()
    {
        $members = auth()->user()->members()->get();
        return view('akun.create', compact('members'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'            => 'required',
            'type'            => 'required|in:bank,ewallet,tunai',
            'initial_balance' => 'nullable|string',
            'member_id'       => 'nullable|exists:members,id',
        ]);

        // Bersihkan format rupiah
        $initialBalance = (int) str_replace(['.', ','], '', $request->initial_balance);

        $account = Account::create([
            'user_id'   => auth()->id(),
            'member_id' => $request->member_id,
            'name'      => $request->name,
            'type'      => $request->type,
        ]);

        // Jika saldo awal > 0, buat transaksi otomatis
        if ($initialBalance > 0) {
            $category = Category::firstOrCreate([
                'user_id' => auth()->id(),
                'name'    => 'Saldo Awal',
                'type'    => 'pemasukan',
            ]);

            Transaction::create([
                'user_id'    => auth()->id(),
                'account_id' => $account->id,
                'type'       => 'pemasukan',
                'category'   => $category->name,
                'member_id'  => $request->member_id ?? auth()->user()->members()->first()->id ?? null,
                'amount'     => $initialBalance,
                'description'=> 'Saldo awal akun',
                'date'       => now()->toDateString(),
            ]);
        }

        $account->updateBalance();

        return redirect()->route('akun.index')->with('success', 'Akun berhasil ditambahkan.');
    }

    public function edit(Account $akun)
    {
        $members = auth()->user()->members()->get();
        return view('akun.edit', ['account' => $akun, 'members' => $members]);
    }

    public function update(Request $request, Account $akun)
    {
        $request->validate([
            'name'      => 'required|string|max:100',
            'type'      => 'required|in:bank,ewallet,tunai',
            'balance'   => 'nullable|numeric',
            'member_id' => 'nullable|exists:members,id',
        ]);

        $akun->update($request->only('name', 'type', 'balance', 'member_id'));

        return redirect()->route('akun.index')->with('success', 'Akun berhasil diupdate.');
    }

    public function destroy(Account $akun)
    {
        $akun->delete();
        return redirect()->route('akun.index')->with('success', 'Akun berhasil dihapus.');
    }
}
