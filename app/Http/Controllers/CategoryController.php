<?php

namespace App\Http\Controllers;


use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = auth()->user()->categories->sortBy('type');
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

    public function destroy(Category $kategori)
    {
        // Pastikan user hanya bisa menghapus miliknya
        if ($kategori->user_id !== auth()->id()) {
            abort(403);
        }

        $kategori->delete();

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus');
    }

    public function byType($type)
    {
        $categories = auth()->user()
            ->categories()
            ->where('type', $type)
            ->select('id', 'name')
            ->get();

        return response()->json($categories);
    }
}
