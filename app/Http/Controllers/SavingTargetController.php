<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SavingTarget;

class SavingTargetController extends Controller
{
    public function index()
    {
        $targets = auth()->user()->savingTargets()->latest()->get();
        return view('target_dana.index', compact('targets'));
    }

    public function create()
    {
        return view('target_dana.create');
    }

    public function store(Request $request)
    {
        // Bersihkan format rupiah dulu
        $amount = (int) str_replace(['.', ','], '', $request->target_amount);

        // Validasi setelah angka dibersihkan
        $request->merge(['target_amount_clean' => $amount]);

        $request->validate([
            'name' => 'required|string',
            'target_amount_clean' => 'required|numeric|min:1000',
            'deadline' => 'nullable|date|after_or_equal:today',
        ]);

        auth()->user()->savingTargets()->create([
            'name' => $request->name,
            'target_amount' => $amount,
            'deadline' => $request->deadline,
        ]);

        return redirect()->route('target-dana.index')->with('success', 'Target dana berhasil ditambahkan.');
    }

    public function show($id)
    {
        $target = SavingTarget::with('logs')->where('user_id', auth()->id())->findOrFail($id);
        return view('target_dana.detail', compact('target'));
    }

    public function simpanDana(Request $request, $id)
    {
        $target = SavingTarget::where('user_id', auth()->id())->findOrFail($id);

        $amount = (int) str_replace(['.', ','], '', $request->amount);
        $request->merge(['amount_clean' => $amount]);

        $request->validate([
            'amount_clean' => 'required|numeric|min:1000',
            'description' => 'nullable|string',
            'date' => 'required|date',
        ]);

        $target->logs()->create([
            'amount' => $amount,
            'description' => $request->description,
            'date' => $request->date,
        ]);

        // Update total dana tersimpan
        $target->saved_amount += $amount;

        if ($target->saved_amount >= $target->target_amount) {
            $target->status = 'tercapai';
        }

        $target->save();

        return back()->with('success', 'Dana berhasil disimpan.');
    }

    public function editLog($targetId, $logId)
    {
        $target = SavingTarget::where('user_id', auth()->id())->findOrFail($targetId);
        $log = $target->logs()->findOrFail($logId);

        return view('target_dana.edit_log', compact('target', 'log'));
    }

    public function updateLog(Request $request, $targetId, $logId)
    {
        $target = SavingTarget::where('user_id', auth()->id())->findOrFail($targetId);
        $log = $target->logs()->findOrFail($logId);

        $newAmount = (int) str_replace(['.', ','], '', $request->amount);
        $request->merge(['amount_clean' => $newAmount]);

        $request->validate([
            'amount_clean' => 'required|numeric|min:1000',
            'description' => 'nullable|string',
            'date' => 'required|date',
        ]);

        // Kurangi jumlah lama, tambahkan jumlah baru
        $target->saved_amount = $target->saved_amount - $log->amount + $newAmount;

        // Perbarui log
        $log->update([
            'amount' => $newAmount,
            'description' => $request->description,
            'date' => $request->date,
        ]);

        // Cek status
        $target->status = $target->saved_amount >= $target->target_amount ? 'tercapai' : 'berjalan';
        $target->save();

        return redirect()->route('target-dana.show', $target->id)->with('success', 'Log berhasil diperbarui.');
    }

    public function destroyLog($targetId, $logId)
    {
        $target = SavingTarget::where('user_id', auth()->id())->findOrFail($targetId);
        $log = $target->logs()->findOrFail($logId);

        $target->saved_amount -= $log->amount;
        $log->delete();

        $target->status = $target->saved_amount >= $target->target_amount ? 'tercapai' : 'berjalan';
        $target->save();

        return back()->with('success', 'Log berhasil dihapus.');
    }

    public function destroy($id)
    {
        $target = auth()->user()->savingTargets()->findOrFail($id);
        $target->delete();

        return redirect()->route('target-dana.index')->with('success', 'Target berhasil dihapus.');
    }
}
