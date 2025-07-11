<?php

namespace App\Http\Controllers;


use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = auth()->user()->categories;
        return view('kategori.index', compact('categories'));
    }

    public function create()
    {
        return view('kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'type' => 'required|in:pemasukan,pengeluaran',
        ]);

        Category::create([
            'user_id' => auth()->id(),
            'name'    => $request->name,
            'type'    => $request->type,
        ]);

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan');
    }
}
