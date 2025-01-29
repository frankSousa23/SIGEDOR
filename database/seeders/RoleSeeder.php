<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Crear permisos (USAR ESPACIOS)
        $permissions = [
            'view users',
            'create users',
            'edit users',
            'delete users',
            'view teachers',
            'create teachers',
            'edit teachers',
            'delete teachers',
            'manage sites',
            'manage categories',
            'manage dedications',
            'manage permissions',
            'view reports',
            'create reports'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Crear rol admin y asignar todos los permisos
        $role = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $role->givePermissionTo($permissions);

        // Crear roles solo si no existen
        $roles = ['area_manager', 'teacher'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'web'
            ]);
        }

        // Asignar permisos a roles
        $areaManagerRole = Role::where('name', 'area_manager')->first();
        $areaManagerRole->givePermissionTo([
            'view teachers',
            'edit teachers',
            'view reports',
            'create reports'
        ]);

        $teacherRole = Role::where('name', 'teacher')->first();
        $teacherRole->givePermissionTo(['view teachers']);

        $roles = [
            'admin',
            'gerente',
            'docente',
        ];

        foreach ($roles as $role) {
            if (!Role::where('name', $role)->exists()) {
                Role::create(['name' => $role]);
            }
        }
    }
}
