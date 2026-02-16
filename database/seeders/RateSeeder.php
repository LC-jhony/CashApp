<?php

namespace Database\Seeders;

use App\Models\Rate;
use Illuminate\Database\Seeder;

class RateSeeder extends Seeder
{
    public function run(): void
    {
        $rates = [
            ['percent' => 5.00, 'fee' => 0, 'state' => 'active'],
            ['percent' => 10.00, 'fee' => 0, 'state' => 'active'],
            ['percent' => 15.00, 'fee' => 0, 'state' => 'active'],
            ['percent' => 20.00, 'fee' => 0, 'state' => 'active'],
            ['percent' => 24.00, 'fee' => 0, 'state' => 'active'],
            ['percent' => 36.00, 'fee' => 0, 'state' => 'active'],
        ];

        foreach ($rates as $rate) {
            Rate::create($rate);
        }
    }
}
