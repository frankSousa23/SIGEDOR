<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AreaOption;

class AreaOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (! AreaOption::where('name', 'Area Option 1')->exists()) {
            AreaOption::create(['name' => 'Area Option 1']);
        }
    }
}
