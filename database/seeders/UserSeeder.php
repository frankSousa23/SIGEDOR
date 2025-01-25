<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Site;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear roles si no existen
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'area_manager', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'teacher', 'guard_name' => 'web']);

        // Crear usuario admin si no existe
        User::create([
            'name' => 'Admin',
            'email' => 'admin@sigedor.com',
            'password' => bcrypt('password'),
            'is_active' => true,
            'is_approved' => true
        ])->assignRole('admin');

        // Crear usuario area_manager si no existe
        User::factory()->create([
            'name' => 'Gestor Área',
            'email' => 'gestor@sigedor.com',
            'password' => bcrypt('password'),
        ])->assignRole('area_manager');

        // Crear usuario teacher si no existe
        User::factory()->create([
            'name' => 'Docente',
            'email' => 'docente@sigedor.com',
            'password' => bcrypt('password'),
        ])->assignRole('teacher');
    }
}
