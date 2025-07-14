<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class TransferController extends Controller
{
    public function create()
    {
        $accounts = auth()->user()->accounts;
        $members = auth()->user()->members;
        return view('mutasi.create', compact('accounts', 'members'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'from_account' => 'required|different:to_account',
            'to_account' => 'required',
            'amount' => 'required|numeric|min:1',
            'date' => 'required|date',
            'mutation_type' => 'required|in:transfer,tarik_tunai',
            'description' => 'nullable|string',
        ]);

        $amount = (int) str_replace(['.', ','], '', $request->amount);

        // Ambil member pertama dari user
        $member = auth()->user()->members()->first();

        if (!$member) {
            return back()->withErrors(['member' => 'Tidak ada anggota yang terdaftar.']);
        }

        // Transaksi pengeluaran
        $from = Transaction::create([
            'user_id' => auth()->id(),
            'account_id' => $request->from_account,
            'type' => 'pengeluaran',
            'category' => 'Mutasi Keluar',
            'member_id' => $member->id,
            'amount' => $amount,
            'date' => $request->date,
            'description' => $request->description ?? 'Mutasi keluar',
        ]);

        // Transaksi pemasukan
        $to = Transaction::create([
            'user_id' => auth()->id(),
            'account_id' => $request->to_account,
            'type' => 'pemasukan',
            'category' => 'Mutasi Masuk',
            'member_id' => $member->id,
            'amount' => $amount,
            'date' => $request->date,
            'description' => $request->description ?? 'Mutasi masuk',
        ]);

        // Hubungkan keduanya
        $from->update(['transfer_id' => $to->id]);
        $to->update(['transfer_id' => $from->id]);

        // Update saldo
        $from->account->updateBalance();
        $to->account->updateBalance();

        return redirect()->route('transaksi.index')->with('success', 'Mutasi rekening berhasil disimpan.');
    }
}
