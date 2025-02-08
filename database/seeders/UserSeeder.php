<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Models\SiteOption;
use App\Models\AreaOption;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Desactivar otros seeders temporalmente
        if (User::count() > 0) {
            return;
        }

        // Verificar que existan SiteOption y AreaOption (deben haber sido sembradas previamente)
        $siteOption = SiteOption::first();
        $areaOption = AreaOption::first();

        if (!$siteOption || !$areaOption) {
            // Opcional: lanzar error ó crear opciones predeterminadas
            throw new \Exception("Se requieren SiteOption y AreaOption para crear el usuario.");
        }

        // Eliminar sesiones existentes
        DB::table('sessions')->delete();

        // Eliminar usuarios existentes
        User::whereIn('email', ['admin@example.com', 'area_manager@example.com', 'teacher@example.com'])->delete();

        // Crear un usuario por cada rol
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $admin = User::create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
            ]);
            $admin->assignRole($adminRole);
        }

        $areaManagerRole = Role::where('name', 'area_manager')->first();
        if ($areaManagerRole) {
            $area_manager = User::create([
                'name' => 'Area Manager',
                'email' => 'area_manager@example.com',
                'password' => Hash::make('password'),
            ]);
            $area_manager->assignRole($areaManagerRole);
        }

        $teacherRole = Role::where('name', 'teacher')->first();
        if ($teacherRole) {
            $teacher = User::create([
                'name' => 'Teacher',
                'email' => 'teacher@example.com',
                'password' => Hash::make('password'),
            ]);
            $teacher->assignRole($teacherRole);
        }

        // Limpieza de cachés Spatie
        Cache::forget('spatie.permission.cache');
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // [Línea comentada] Comentar autenticación automática para evitar sesiones persistentes inesperadas
        // Auth::login($user);
    }
}
