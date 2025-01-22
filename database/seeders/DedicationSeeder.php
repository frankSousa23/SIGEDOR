<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dedication;

class DedicationSeeder extends Seeder
{
    public function run(): void
    {
        Dedication::factory()
            ->count(5) // Crear 5 tipos de dedicación
            ->create();
    }
}
