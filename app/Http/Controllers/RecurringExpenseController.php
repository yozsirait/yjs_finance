<?php

namespace App\Http\Controllers;

use App\Models\RecurringExpense;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RecurringExpenseController extends Controller
{
    public function index()
    {
        $rutin = RecurringExpense::where('user_id', auth()->id())->get();
        $accounts = auth()->user()->accounts;
        $members = auth()->user()->members;
        $categories = auth()->user()->categories()->where('type', 'pengeluaran')->get();

        return view('pengeluaran_rutin.index', compact('rutin', 'accounts', 'members', 'categories'));
    }

    public function store(Request $request)
    {
        $amount = (int) str_replace(['.', ','], '', $request->amount);
        $request->merge(['amount_clean' => $amount]);

        $request->validate([
            'name' => 'required|string',
            'category' => 'required|string',
            'account_id' => 'nullable|exists:accounts,id',
            'member_id' => 'nullable|exists:members,id',
            'start_date' => 'required|date',
            'interval' => 'required|in:harian,mingguan,bulanan,tahunan',
            'amount_clean' => 'required|numeric|min:1000',
        ]);

        // Tentukan next_date pertama kali
        $nextDate = match ($request->interval) {
            'harian'   => Carbon::parse($request->start_date)->addDay(),
            'mingguan' => Carbon::parse($request->start_date)->addWeek(),
            'bulanan'  => Carbon::parse($request->start_date)->addMonth(),
            'tahunan'  => Carbon::parse($request->start_date)->addYear(),
            default    => Carbon::parse($request->start_date),
        };


        RecurringExpense::create([
            'user_id'    => auth()->id(),
            'name'       => $request->name,
            'type'       => 'pengeluaran',
            'category'   => $request->category,
            'account_id' => $request->account_id,
            'member_id'  => $request->member_id,
            'start_date' => $request->start_date,
            'interval'   => $request->interval,
            'description'=> $request->description,
            'amount'     => $amount,
            'next_date'  => $nextDate,
        ]);



        return back()->with('success', 'Pengeluaran rutin berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $recurring = RecurringExpense::where('user_id', auth()->id())->findOrFail($id);
        return view('pengeluaran_rutin.edit', compact('recurring'));
    }

    public function update(Request $request, $id)
    {
        $amount = (int) str_replace(['.', ','], '', $request->amount);
        $request->merge(['amount_clean' => $amount]);

        $request->validate([
            'name' => 'required|string|max:255',
            'amount_clean' => 'required|numeric|min:1000',
            'description' => 'nullable|string',
            'repeat_day' => 'required|integer|min:1|max:31',
        ]);

        $recurring = RecurringExpense::where('user_id', auth()->id())->findOrFail($id);
        $recurring->update([
            'name' => $request->name,
            'amount' => $amount,
            'description' => $request->description,
            'repeat_day' => $request->repeat_day,
        ]);

        return redirect()->route('pengeluaran-rutin.index')->with('success', 'Pengeluaran rutin berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $recurring = auth()->user()->recurringExpenses()->findOrFail($id);
        $recurring->delete();

        return redirect()->route('pengeluaran-rutin.index')->with('success', 'Pengeluaran rutin berhasil dihapus.');
    }

    public function eksekusi()
    {
        $today = now()->toDateString();
        $user  = auth()->user();

        $routines = RecurringExpense::where('user_id', $user->id)
            ->whereDate('next_date', '<=', $today)
            ->get();

        foreach ($routines as $routine) {
            Transaction::create([
                'user_id'     => $user->id,
                'account_id'  => $routine->account_id,
                'member_id'   => $routine->member_id,
                'type'        => 'pengeluaran',
                'category'    => $routine->category,
                'amount'      => $routine->amount,
                'description' => 'Pengeluaran Rutin: ' . $routine->description,
                'date'        => $today,
            ]);

            $next = match ($routine->interval) {
                'harian'   => Carbon::parse($routine->next_date)->addDay(),
                'mingguan' => Carbon::parse($routine->next_date)->addWeek(),
                'bulanan'  => Carbon::parse($routine->next_date)->addMonth(),
                'tahunan'  => Carbon::parse($routine->next_date)->addYear(),
                default    => Carbon::parse($routine->next_date),
            };

            $routine->update(['next_date' => $next]);
        }

        return redirect()->back()->with('success', 'Pengeluaran rutin hari ini berhasil dieksekusi.');
    }
}
