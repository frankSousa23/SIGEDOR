<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Site;
use App\Models\Teacher;
use App\Models\Category;
use App\Models\Dedication;
use Faker\Factory as Faker;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SiteSeeder::class,       // 1. Sedes primero
            DedicationSeeder::class,  // 2. Dedicaciones
            RoleSeeder::class,       // 3. Roles
            UserSeeder::class,       // 4. Usuarios
            CategorySeeder::class,    // 5. Categorías
            TeacherSeeder::class,     // 6. Profesores
            // ... (otros seeders que tengas) ...
        ]);
    }
}
