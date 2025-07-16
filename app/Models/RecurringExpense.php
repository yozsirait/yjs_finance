<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecurringExpense extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'type',
        'category',
        'account_id',
        'member_id',
        'amount',
        'description',
        'start_date',
        'next_date',
        'interval',
        'active'
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
