<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Site;
use App\Models\AreaOption;
use App\Models\User;
use App\Models\SiteOption;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            SiteOptionSeeder::class,
            AreaOptionSeeder::class,
            UserSeeder::class,
        ]);
    }
}
