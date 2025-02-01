<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Models\SiteOption;
use App\Models\AreaOption;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verificar que existan SiteOption y AreaOption (deben haber sido sembradas previamente)
        $siteOption = SiteOption::first();
        $areaOption = AreaOption::first();

        if (!$siteOption || !$areaOption) {
            // Opcional: lanzar error ó crear opciones predeterminadas
            throw new \Exception("Se requieren SiteOption y AreaOption para crear el usuario.");
        }

        // Crear usuario "Admin" con lo mínimo para iniciar sesión
        $admin = User::create([
            'name'              => 'Admin',
            'email'             => 'admin@sigedor.com',
            // Se utiliza Hash::make; en el modelo se aplica un boot para hashear también,
            // pero se recomienda que en este seeder el valor se pase ya hasheado
            'password'          => Hash::make('secret'),
            'role'              => 'admin', // Columna adicional, si se requiere, sólo para fines informativos
            'site_option_id'    => $siteOption->id,
            'area_option_id'    => $areaOption->id,
            'is_active'         => true,
            'is_approved'       => true,
            'email_verified_at' => now(),
        ]);

        // Asegurar que el rol "admin" exista; de lo contrario, lo crea.
        $adminRole = Role::firstOrCreate(
            ['name' => 'admin', 'guard_name' => 'web']
        );

        // Asignar rol "admin" utilizando Spatie/Permission de forma segura.
        $admin->assignRole($adminRole->name);
    }
}
