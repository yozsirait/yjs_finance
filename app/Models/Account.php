<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = ['user_id', 'name', 'type', 'balance'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function updateBalance()
    {
        $pemasukan = $this->transactions()
            ->where('type', 'pemasukan')
            ->sum('amount');

        $pengeluaran = $this->transactions()
            ->where('type', 'pengeluaran')
            ->sum('amount');

        $this->balance = $pemasukan - $pengeluaran;
        $this->save();
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
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

        // Update progress total tersimpan
        $target->saved_amount += $amount;
        if ($target->saved_amount >= $target->target_amount) {
            $target->status = 'tercapai';
        }
        $target->save();

        return back()->with('success', 'Dana berhasil disimpan.');
    }
}
