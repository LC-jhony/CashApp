<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'user_id',
        'loan_id',
        'amount',
        'interest',
        'amort',
        'type',
    ];
}
