<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Site;

class SiteSeeder extends Seeder
{
    public function run(): void
    {
        Site::create([
            'name' => 'Sede Principal',
            'area' => 'Área General',
            'is_active' => true,
        ]);
    }
}
