<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Site;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear el usuario administrador
        $admin = User::create([
            'name' => 'Administrador',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'is_active' => true,
            'is_approved' => true,
        ]);

        // Crear roles y permisos
        $role = Role::create(['name' => 'admin']);
        $permission = Permission::create(['name' => 'manage everything']);

        // Asignar permisos al rol
        $role->givePermissionTo($permission);

        // Asignar rol al usuario administrador
        $admin->assignRole($role);
    }
}
