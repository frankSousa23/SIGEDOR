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

        // Creación con password no persistente
        $password = 'tmp_'.Str::uuid().'_'.now()->format('YmdHis');
        $user = User::create([
            'name' => 'Admin Temporal',
            'email' => 'admin@temp.com',
            'password' => Hash::make('password'),
            'site_option_id' => $siteOption->id,
            'area_option_id' => $areaOption->id,
            'is_active' => true,
            'is_approved' => true,
            'email_verified_at' => now(),
            'is_temporary' => true,
            'remember_token' => Str::random(60) // Token inválido
        ]);

        // Limpieza de cachés Spatie
        Cache::forget('spatie.permission.cache');
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Asignación de rol
        $user->assignRole('admin');
    }
}
