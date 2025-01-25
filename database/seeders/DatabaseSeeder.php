<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            RoleSeeder::class,       // 1. Roles primero
            SiteSeeder::class,       // 2. Sites (usar constantes)
            UserSeeder::class,       // 3. Usuarios con roles
            TeacherSeeder::class,    // 4. Teachers (requiere users)
            CategorySeeder::class,   // 5. Categories
            DedicationSeeder::class, // 6. Dedications
        ]);
    }
}
