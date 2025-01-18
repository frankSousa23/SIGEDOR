<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Site;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener el ID del primer sitio
        $siteId = Site::first()->id;

        // Primero, limpiamos los roles existentes
        Role::query()->delete();

        // Crear roles
        $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $areaManagerRole = Role::create(['name' => 'area_manager', 'guard_name' => 'web']);
        $teacherRole = Role::create(['name' => 'teacher', 'guard_name' => 'web']);

        // Usuario Administrador
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'cdi' => '12345678',
            'password' => Hash::make('password'),
            'is_active' => true,
            'is_approved' => true,
            'site_id' => $siteId,
        ]);
        $admin->assignRole($adminRole);

        // Usuario Area Manager
        $areaManager = User::create([
            'name' => 'Area Manager',
            'email' => 'area@example.com',
            'cdi' => '87654321',
            'password' => Hash::make('password'),
            'is_active' => true,
            'is_approved' => true,
            'site_id' => $siteId,
        ]);
        $areaManager->assignRole($areaManagerRole);

        // Usuario Teacher
        $teacher = User::create([
            'name' => 'Teacher',
            'email' => 'teacher@example.com',
            'cdi' => '11223344',
            'password' => Hash::make('password'),
            'is_active' => true,
            'is_approved' => true,
            'site_id' => $siteId,
        ]);
        $teacher->assignRole($teacherRole);
    }
}
