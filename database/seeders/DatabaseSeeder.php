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
        // Crear roles primero
        $this->call(RoleSeeder::class);

        // Admin temporal
        $admin = \App\Models\User::create([
            'name' => 'Admin Temporal',
            'email' => 'admin@sigedor.com',
            'password' => Hash::make('password'),
            'is_temporary' => true, // Agregar este campo
        ]);

        $admin->assignRole('Admin');

        $this->call([
            SiteOptionSeeder::class,
            AreaOptionSeeder::class
        ]);

        $this->call(UserSeeder::class);
    }
}
