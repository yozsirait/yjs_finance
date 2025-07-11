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
}
