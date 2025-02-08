<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Deshabilitar la verificación de claves foráneas
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncar las tablas
        Role::truncate();
        Permission::truncate();

        // Volver a habilitar la verificación de claves foráneas
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Crear roles
        $adminRole = Role::create(['name' => 'admin']);
        $areaManagerRole = Role::create(['name' => 'area_manager']);
        $teacherRole = Role::create(['name' => 'teacher']);

        // Crear permisos
        $permission = Permission::create(['name' => 'full access']);

        // Asignar permisos a roles
        $adminRole->givePermissionTo($permission);
        // $areaManagerRole->givePermissionTo('...'); // Asignar permisos específicos
        // $teacherRole->givePermissionTo('...'); // Asignar permisos específicos
    }
}
