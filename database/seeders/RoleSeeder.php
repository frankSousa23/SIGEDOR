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
            Permission::create(['name' => $permission]);
        }

        // Rol Admin
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        // Rol Area Manager
        $areaManagerRole = Role::create(['name' => 'areamanager']);
        $areaManagerRole->givePermissionTo([
            'view_teachers',
            'edit_teachers',
            'view_reports',
            'create_reports'
        ]);

        // Rol Teacher
        $teacherRole = Role::create(['name' => 'teacher']);
        $teacherRole->givePermissionTo([
            'view_teachers' // Solo podrá ver su propia información
        ]);
    }
}
