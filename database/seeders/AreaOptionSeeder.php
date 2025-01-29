<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AreaOption;
use App\Models\Site;

class AreaOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (Site::AREA_OPTIONS as $name) {
            AreaOption::create(['name' => $name]);
        }
    }
}
