<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'member_id',
        'type',
        'account_id',
        'amount',
        'date',
        'category',
        'description',
    ];

    protected $casts = [
        'date' => 'date', // ✅ auto-convert ke Carbon
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
