<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\Member;
use App\Models\Category;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    public function index()
    {
        $incomes = auth()->user()->incomes()->latest()->get();
        return view('pemasukan.index', compact('incomes'));
    }

    public function create()
    {
        $members = auth()->user()->members;
        $categories = auth()->user()->categories()->where('type', 'pemasukan')->get();
        return view('pemasukan.create', compact('members', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:members,id',
            'category_id' => 'nullable|exists:categories,id',
            'date' => 'required|date',
            'amount' => 'required|string',
            'description' => 'nullable|string',
        ]);

        Income::create([
            'user_id'     => auth()->id(),
            'member_id'   => $request->member_id,
            'category_id' => $request->category_id,
            'date'        => $request->date,
            'amount'      => str_replace('.', '', $request->amount),
            'description' => $request->description,
        ]);

        return redirect()->route('pemasukan.index')->with('success', 'Pemasukan berhasil ditambahkan');
    }
}
