<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Crear permisos básicos
        $permissions = [
            'view_teachers',
            'create_teachers',
            'edit_teachers',
            'delete_teachers',
            'manage_sites',
            'manage_categories',
            'manage_dedications',
            'manage_permissions',
            'view_reports',
            'create_reports'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Crear roles solo si no existen
        $roles = ['admin', 'area_manager', 'teacher'];
        foreach ($roles as $role) {
            Role::firstOrCreate([
                'name' => $role,
                'guard_name' => 'web'
            ]);
        }

        // Asignar permisos a roles
        $adminRole = Role::where('name', 'admin')->first();
        $adminRole->givePermissionTo(Permission::all());

        $areaManagerRole = Role::where('name', 'area_manager')->first();
        $areaManagerRole->givePermissionTo([
            'view_teachers',
            'edit_teachers',
            'view_reports',
            'create_reports'
        ]);

        $teacherRole = Role::where('name', 'teacher')->first();
        $teacherRole->givePermissionTo(['view_teachers']);
    }
}
