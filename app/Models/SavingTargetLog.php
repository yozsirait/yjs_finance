<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavingTargetLog extends Model
{
    protected $fillable = [
        'saving_target_id',
        'amount',
        'description',
        'date',
    ];
}
