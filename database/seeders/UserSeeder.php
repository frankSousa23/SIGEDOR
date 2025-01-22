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
        // Disable foreign key checks! (necesario para truncar roles y usuarios)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Limpiar roles y usuarios existentes para evitar duplicados en cada seed
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        Role::truncate();
        User::truncate();

        // Crear roles
        $adminRole = Role::create(['name' => 'admin']);
        $areaManagerRole = Role::create(['name' => 'area-manager']);
        $teacherRole = Role::create(['name' => 'teacher']);

        // Crear permisos (ejemplos - ajusta según tus necesidades)
        $manageTeachers = Permission::create(['name' => 'manage teachers']);
        $manageSites = Permission::create(['name' => 'manage sites']);
        $manageCategories = Permission::create(['name' => 'manage categories']);
        $manageDedications = Permission::create(['name' => 'manage dedications']);

        // Asignar permisos a roles (ejemplos - ajusta según tus necesidades)
        $adminRole->givePermissionTo(Permission::all()); // Admin tiene todos los permisos
        $areaManagerRole->givePermissionTo([$manageTeachers, $manageSites, $manageCategories, $manageDedications]);
        $teacherRole->givePermissionTo([$manageCategories]); // Ejemplo: Teacher solo manage categories

        // Obtener sedes existentes para asignar a usuarios
        $sites = Site::all();

        // Crear usuario Admin
        $adminUser = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'), // Cambia 'password' por una contraseña segura en producción
            'is_active' => true,
            'is_approved' => true,
            'site_id' => $sites->random()->id ?? null, // Asignar un site_id aleatorio o null si no hay sedes
        ]);
        $adminUser->assignRole($adminRole);

        // Crear usuario Area Manager
        $areaManagerUser = User::create([
            'name' => 'Area Manager',
            'email' => 'areamanager@example.com',
            'password' => bcrypt('password'), // Cambia 'password' por una contraseña segura en producción
            'is_active' => true,
            'is_approved' => true,
            'site_id' => $sites->random()->id ?? null, // Asignar un site_id aleatorio o null si no hay sedes
        ]);
        $areaManagerUser->assignRole($areaManagerRole);

        // Crear usuario Teacher
        $teacherUser = User::create([
            'name' => 'Teacher User',
            'email' => 'teacher@example.com',
            'password' => bcrypt('password'), // Cambia 'password' por una contraseña segura en producción
            'is_active' => true,
            'is_approved' => true,
            'site_id' => $sites->random()->id ?? null, // Asignar un site_id aleatorio o null si no hay sedes
        ]);
        $teacherUser->assignRole($teacherRole);

        // Puedes crear más usuarios o factories aquí si es necesario

        // Re-enable foreign key checks!
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
