<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Crear roles
        $adminRole = Role::create(['name' => 'admin']);
        $areaManagerRole = Role::create(['name' => 'area_manager']);
        $teacherRole = Role::create(['name' => 'teacher']);

        // Usuario Administrador
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'cdi' => '12345678',
            'password' => Hash::make('password'),
            'is_active' => true,
            'is_approved' => true,
        ]);
        $admin->assignRole($adminRole);

        // Usuario Area Manager (inactivo inicialmente)
        $areaManager = User::create([
            'name' => 'Area Manager',
            'email' => 'area@example.com',
            'cdi' => '87654321',
            'password' => Hash::make('password'),
            'is_active' => false,
            'is_approved' => false,
        ]);
        $areaManager->assignRole($areaManagerRole);

        // Usuario Teacher (inactivo inicialmente)
        $teacher = User::create([
            'name' => 'Teacher',
            'email' => 'teacher@example.com',
            'cdi' => '11223344',
            'password' => Hash::make('password'),
            'is_active' => false,
            'is_approved' => false,
        ]);
        $teacher->assignRole($teacherRole);
    }
}
