<?php

namespace Database\Seeders;

use App\Models\Frecuencie;
use Illuminate\Database\Seeder;

class FrecuencieSeeder extends Seeder
{
    public function run(): void
    {
        $frecuencies = [
            ['name' => 'MENSUAL', 'days' => 30],
            ['name' => 'BIMESTRAL', 'days' => 60],
            ['name' => 'TRIMESTRAL', 'days' => 90],
            ['name' => 'SEMESTRAL', 'days' => 180],
            ['name' => 'ANUAL', 'days' => 360],
        ];

        foreach ($frecuencies as $frecuency) {
            Frecuencie::create($frecuency);
        }
    }
}
