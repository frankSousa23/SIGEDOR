<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Deshabilitar la verificación de claves foráneas
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncar las tablas de Spatie Permission
        DB::table('roles')->truncate();
        DB::table('permissions')->truncate();
        DB::table('role_has_permissions')->truncate();
        DB::table('model_has_roles')->truncate();
        DB::table('model_has_permissions')->truncate();

        // Habilitar la verificación de claves foráneas
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Eliminar roles existentes
        Role::whereIn('name', ['admin', 'area_manager', 'teacher'])->delete();

        // Crear roles
        $admin = Role::create(['name' => 'admin']);
        $area_manager = Role::create(['name' => 'area_manager']);
        $teacher = Role::create(['name' => 'teacher']);

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

        $area_manager->givePermissionTo([
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
