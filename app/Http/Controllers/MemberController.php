<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index()
    {
        $members = auth()->user()->members()->get();
        return view('anggota.index', compact('members'));
    }

    public function create()
    {
        return view('anggota.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        auth()->user()->members()->create([
            'name' => $request->name
        ]);

        return redirect()->route('anggota.index')->with('success', 'Anggota berhasil ditambahkan.');
    }

    public function edit(Member $anggota)
    {
        return view('anggota.edit', ['anggota' => $anggota]);
    }


    public function update(Request $request, Member $anggota)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'pin' => 'nullable|digits:4'
        ]);

        $data = ['name' => $request->name];

        if ($request->filled('pin')) {
            $data['pin'] = $request->pin;
        }

        $anggota->update($data);

        return redirect()->route('anggota.index')->with('success', 'Anggota berhasil diupdate.');
    }


    public function destroy(Member $member)
    {
        $member->delete();
        return redirect()->route('anggota.index')->with('success', 'Anggota berhasil dihapus.');
    }
}
