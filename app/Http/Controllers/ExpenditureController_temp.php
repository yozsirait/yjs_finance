<?php

namespace App\Http\Controllers;

use App\Models\Expenditure;
use App\Models\Member;
use App\Models\Category;
use Illuminate\Http\Request;


class ExpenditureController extends Controller
{
    public function index()
    {
        $expenditures = auth()->user()->expenditures()->latest()->get();
        return view('pengeluaran.index', compact('expenditures'));
    }

    public function create()
    {
        $members = auth()->user()->members;
        $categories = auth()->user()->categories()->where('type', 'pengeluaran')->get();
        return view('pengeluaran.create', compact('members', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'member_id'   => 'required|exists:members,id',
            'category_id' => 'nullable|exists:categories,id',
            'date'        => 'required|date',
            'amount'      => 'required|string',
            'description' => 'nullable|string',
        ]);

        Expenditure::create([
            'user_id'     => auth()->id(),
            'member_id'   => $request->member_id,
            'category_id' => $request->category_id,
            'date'        => $request->date,
            'amount'      => str_replace('.', '', $request->amount),
            'description' => $request->description,
        ]);

        return redirect()->route('pengeluaran.index')->with('success', 'Pengeluaran berhasil disimpan');
    }
}
