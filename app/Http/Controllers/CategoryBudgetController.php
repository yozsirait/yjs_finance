<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\CategoryBudget;
use App\Models\Transaction;


class CategoryBudgetController extends Controller
{

    public function index(Request $request)
    {
        $month = $request->month ?? now()->month;
        $year = $request->year ?? now()->year;

        $budgets = CategoryBudget::where('user_id', auth()->id())
            ->where('month', $month)
            ->where('year', $year)
            ->get();

        // Hitung total transaksi per kategori
        $usages = Transaction::where('user_id', auth()->id())
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->selectRaw('category, type, SUM(amount) as total')
            ->groupBy('category', 'type')
            ->get();

        return view('anggaran.index', compact('budgets', 'usages', 'month', 'year'));
    }

    public function store(Request $request)
    {
        $amount = (int) str_replace(['.', ','], '', $request->amount);

        $request->merge(['amount_clean' => $amount]);

        $request->validate([
            'category' => 'required|string',
            'type' => 'required|in:pemasukan,pengeluaran',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:2100',
            'amount_clean' => 'required|numeric|min:1000',
        ]);

        CategoryBudget::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'category' => $request->category,
                'type' => $request->type,
                'month' => $request->month,
                'year' => $request->year,
            ],
            ['amount' => $amount]
        );

        return back()->with('success', 'Anggaran berhasil disimpan.');
    }

    public function destroy($id)
    {
        $budget = CategoryBudget::where('user_id', auth()->id())->findOrFail($id);
        $budget->delete();

        return back()->with('success', 'Anggaran berhasil dihapus.');
    }
}
