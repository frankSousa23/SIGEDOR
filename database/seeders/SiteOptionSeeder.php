<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SiteOption;
use App\Models\Site;
use App\Models\User;

class SiteOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (Site::SITE_OPTIONS as $name) {
            SiteOption::create(['name' => $name]);
        }
    }
}
