<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{   
    protected $fillable = [
        'user_id',
        'member_id',
        'type',
        'amount',
        'date',
        'category',
        'description',
    ];

    
    public function user() {
    return $this->belongsTo(User::class);
    }

    public function member() {
        return $this->belongsTo(Member::class);
    }

}
