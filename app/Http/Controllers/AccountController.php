<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        $accounts = auth()->user()->accounts;
        return view('akun.index', compact('accounts'));
    }

    public function create()
    {
        return view('akun.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:100',
            'type'    => 'required|in:bank,ewallet,cash',
            'balance' => 'nullable|numeric',
        ]);

        auth()->user()->accounts()->create($request->only('name', 'type', 'balance'));

        return redirect()->route('akun.index')->with('success', 'Akun berhasil ditambahkan.');
    }

    public function edit(Account $akun)
    {
        $this->authorize('update', $akun); // Opsional jika pakai policy
        return view('akun.edit', ['account' => $akun]);
    }

    public function update(Request $request, Account $akun)
    {
        $request->validate([
            'name'    => 'required|string|max:100',
            'type'    => 'required|in:bank,ewallet,cash',
            'balance' => 'nullable|numeric',
        ]);

        $akun->update($request->only('name', 'type', 'balance'));

        return redirect()->route('akun.index')->with('success', 'Akun berhasil diupdate.');
    }

    public function destroy(Account $akun)
    {
        $akun->delete();
        return redirect()->route('akun.index')->with('success', 'Akun berhasil dihapus.');
    }
}
