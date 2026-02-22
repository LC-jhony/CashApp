<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'status',
    ];

    public function loan(): BelongsTo
    {
        return $this->belongsTo(
            related: Loan::class,
            foreignKey: 'loan_id',
        );
    }
}
