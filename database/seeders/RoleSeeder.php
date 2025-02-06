<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Roles en minúsculas como debe ser
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $areaManager = Role::firstOrCreate(['name' => 'area_manager']);
        $teacher = Role::firstOrCreate(['name' => 'teacher']);

        // Permisos específicos del sistema
        $permissions = [
            // Permisos Admin
            'manage_users',
            'view_all_sites',
            'manage_roles',
            'view_activity_logs',

            // Permisos Area Manager
            'manage_site_users',
            'view_site_reports',
            'edit_teacher_status',

            // Permisos Teacher
            'view_profile',
            'update_personal_info',
            'view_dedications'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Asignación jerárquica de permisos
        $admin->givePermissionTo(Permission::all());

        $areaManager->givePermissionTo([
            'manage_site_users',
            'view_site_reports',
            'edit_teacher_status',
            'view_profile',
            'update_personal_info',
            'view_dedications'
        ]);

        $teacher->givePermissionTo([
            'view_profile',
            'update_personal_info',
            'view_dedications'
        ]);
    }
}
