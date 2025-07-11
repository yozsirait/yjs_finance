<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Transaction;
use Illuminate\Http\Request;


class TransactionController extends Controller
{
    public function create()
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
            'description'=> $request->description,
        ]);

        return redirect()->route('transaksi.create')->with('success', 'Transaksi berhasil disimpan!');
    }

}

