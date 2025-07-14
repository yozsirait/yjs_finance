<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CategoryBudget;
use App\Models\Category;
use App\Models\Transaction;

class CategoryBudgetController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $month = $request->month ?? now()->month;
        $year = $request->year ?? now()->year;

        // Ambil semua anggaran kategori
        $budgets = CategoryBudget::where('user_id', $user->id)
            ->where('month', $month)
            ->where('year', $year)
            ->get();

        // Hitung total pemakaian per kategori
        $usages = Transaction::where('user_id', $user->id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->selectRaw('category, type, SUM(amount) as total')
            ->groupBy('category', 'type')
            ->get();

        // Ambil kategori dari tabel categories (untuk form input)
        $categories = $user->categories()->orderBy('type')->get();

        return view('anggaran.index', compact('budgets', 'usages', 'month', 'year', 'categories'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        // Bersihkan angka dari format rupiah
        $amount = (int) str_replace(['.', ','], '', $request->amount);
        $request->merge(['amount_clean' => $amount]);

        // Validasi input
        $request->validate([
            'category' => 'required|exists:categories,name',
            'type' => 'required|in:pemasukan,pengeluaran',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:2100',
            'amount_clean' => 'required|numeric|min:1000',
        ]);

        // Simpan atau update anggaran
        CategoryBudget::updateOrCreate(
            [
                'user_id' => $user->id,
                'category' => $request->category,
                'type' => $request->type,
                'month' => $request->month,
                'year' => $request->year,
            ],
            [
                'amount' => $amount,
            ]
        );

        return back()->with('success', 'Anggaran berhasil disimpan.');
    }

    public function edit($id)
    {
        $budget = CategoryBudget::where('user_id', auth()->id())->findOrFail($id);
        $categories = auth()->user()->categories()->where('type', $budget->type)->get();

        return view('anggaran.edit', compact('budget', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $amount = (int) str_replace(['.', ','], '', $request->amount);
        $request->merge(['amount_clean' => $amount]);

        $request->validate([
            'amount_clean' => 'required|numeric|min:1000',
        ]);

        $budget = CategoryBudget::where('user_id', auth()->id())->findOrFail($id);
        $budget->update([
            'amount' => $amount
        ]);

        return redirect()->route('anggaran.index', ['month' => $budget->month, 'year' => $budget->year])
            ->with('success', 'Anggaran berhasil diperbarui.');
    }


    public function destroy($id)
    {
        $budget = CategoryBudget::where('user_id', auth()->id())->findOrFail($id);
        $budget->delete();

        return back()->with('success', 'Anggaran berhasil dihapus.');
    }
}
