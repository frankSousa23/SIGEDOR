<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dedication;

class DedicationSeeder extends Seeder
{
    public function run(): void
    {
        Dedication::factory()
            ->count(3) // Solo 3 tipos de dedicación para pruebas
            ->create();
    }
}
