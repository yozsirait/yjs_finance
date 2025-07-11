<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expenditure extends Model
{
    protected $fillable = [
        'user_id',
        'member_id',
        'category_id',
        'date',
        'amount',
        'description',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function member() {
        return $this->belongsTo(Member::class);
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }
}
