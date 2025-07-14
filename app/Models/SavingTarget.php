<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavingTarget extends Model
{

    protected $fillable = ['user_id', 'name', 'target_amount', 'saved_amount', 'deadline', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getProgressPercentageAttribute()
    {
        return min(100, round($this->saved_amount / max(1, $this->target_amount) * 100));
    }

    public function logs()
    {
        return $this->hasMany(SavingTargetLog::class);
    }
}
