<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryBudget extends Model
{
    protected $fillable = ['user_id', 'category', 'type', 'month', 'year', 'amount'];
}
