<?php

namespace App\Models;

use App\Models\Plan;
use App\Models\Rate;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = [
        'amount',
        'frecuency_id',
        'user_id',
        //   'customer_id',
        'rate_id',
    ];
    public function plan()
    {
        return $this->hasMany(Plan::class);
    }

    // public function customer()
    // {
    //     return $this->belongsTo(Customer::class);
    // }

    public function rate()
    {
        return $this->belongsTo(Rate::class);
    }

    public function frecuency()
    {
        return $this->belongsTo(Frecuencie::class);
    }
}
