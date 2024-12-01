<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar caché de roles y permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear roles básicos
        $roles = [
            'admin' => 'Administrador del Sistema',
            'area_manager' => 'Jefe de Área',
            'teacher' => 'Profesor'
        ];

        foreach ($roles as $role => $description) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        // Crear usuarios iniciales
        $adminData = [
            'name' => 'Administrador',
            'email' => 'admin@example.com',
            'password' => Hash::make('password')
        ];
        
        $admin = User::firstOrNew(['email' => $adminData['email']]);
        $admin->fill($adminData);
        $admin->save();
        $admin->assignRole('admin');

        $areaManagerData = [
            'name' => 'Area Manager',
            'email' => 'areamanager@example.com',
            'password' => Hash::make('password1')
        ];
        
        $areaManager = User::firstOrNew(['email' => $areaManagerData['email']]);
        $areaManager->fill($areaManagerData);
        $areaManager->save();
        $areaManager->assignRole('area_manager');

        $teacherData = [
            'name' => 'Teacher',
            'email' => 'teacher@example.com',
            'password' => Hash::make('password')
        ];
        
        $teacher = User::firstOrNew(['email' => $teacherData['email']]);
        $teacher->fill($teacherData);
        $teacher->save();
        $teacher->assignRole('teacher');
    }
}
