<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'loan_id',
        'date',
        'number',
        'payment',
        'interest',
        'amort',
        'balance',
    ];
}
