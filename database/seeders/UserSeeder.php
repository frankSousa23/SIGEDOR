<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Sede;
use App\Models\Area;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Crear roles si no existen
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'area_manager', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'teacher', 'guard_name' => 'web']);

        // Crear usuario admin si no existe
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@sigedor.com',
            'password' => 'password',
            'sede_id' => Sede::inRandomOrder()->first()->id,
            'area_id' => Area::inRandomOrder()->first()->id,
            'is_active' => true,
            'is_approved' => true
        ])->assignRole('admin');
    }
}
