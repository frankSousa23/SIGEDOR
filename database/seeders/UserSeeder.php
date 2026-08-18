<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Sede;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

/**
 * Seeder de Usuarios del Sistema SIGEDOR.
 *
 * Carga los usuarios iniciales demostrativos y asigna sus respectivos
 * roles (admin, area_manager, teacher) y dependencias institucionales (sede/área).
 */
class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Garantizar que los roles básicos existan
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $areaManagerRole = Role::firstOrCreate(['name' => 'area_manager', 'guard_name' => 'web']);
        $teacherRole = Role::firstOrCreate(['name' => 'teacher', 'guard_name' => 'web']);

        $defaultSede = Sede::first() ?? Sede::create(['nombre' => 'Sede Central/San Juan de los Morros']);
        $defaultArea = Area::first() ?? Area::create(['nombre' => 'Ingeniería de sistemas']);

        $csvPath = database_path('seeders/data/users.csv');

        if (!file_exists($csvPath)) {
            $this->command->warn("Archivo users.csv no encontrado, creando usuarios básicos por defecto.");

            User::updateOrCreate(
                ['email' => 'admin@sigedor.com'],
                [
                    'name' => 'Administrador General',
                    'password' => Hash::make('password'),
                    'sede_id' => $defaultSede->id,
                    'area_id' => $defaultArea->id,
                    'is_active' => true,
                    'is_approved' => true,
                ]
            )->assignRole('admin');

            User::updateOrCreate(
                ['email' => 'areamanager@sigedor.com'],
                [
                    'name' => 'Jefe de Área',
                    'password' => Hash::make('password'),
                    'sede_id' => $defaultSede->id,
                    'area_id' => $defaultArea->id,
                    'is_active' => true,
                    'is_approved' => true,
                ]
            )->assignRole('area_manager');

            User::updateOrCreate(
                ['email' => 'docente@sigedor.com'],
                [
                    'name' => 'Prof. Carlos Mendoza',
                    'password' => Hash::make('password'),
                    'sede_id' => $defaultSede->id,
                    'area_id' => $defaultArea->id,
                    'is_active' => true,
                    'is_approved' => true,
                ]
            )->assignRole('teacher');

            return;
        }

        DB::transaction(function () use ($csvPath, $defaultSede, $defaultArea) {
            $csvContent = mb_convert_encoding(file_get_contents($csvPath), 'UTF-8', 'UTF-8');
            $lines = array_filter(array_map('trim', explode("\n", $csvContent)));
            $header = str_getcsv(array_shift($lines), ';');

            foreach ($lines as $index => $line) {
                if (empty($line)) {
                    continue;
                }

                $row = str_getcsv($line, ';');

                if (count($row) < 9 || empty($row[2])) {
                    continue;
                }

                $name = trim($row[1]);
                $email = strtolower(str_replace(' ', '', trim($row[2])));
                $password = trim($row[3]);
                $sedeNombre = trim($row[4]);
                $areaNombre = trim($row[5]);
                $rolName = trim($row[6]);
                $isActive = (int) trim($row[7]) === 1;
                $isApproved = (int) trim($row[8]) === 1;

                $sede = Sede::where('nombre', $sedeNombre)->first() ?? $defaultSede;
                $area = Area::where('nombre', $areaNombre)->first() ?? $defaultArea;
                $user = User::updateOrCreate(
                    ['email' => $email],
                    [
                        'name' => $name,
                        'password' => Hash::make($password),
                        'sede_id' => $sede->id,
                        'area_id' => $area->id,
                        'is_active' => $isActive,
                        'is_approved' => $isApproved,
                    ]
                );

                $roleNames = array_filter(array_map('trim', explode(',', $rolName)));
                $validRoles = [];
                foreach ($roleNames as $rName) {
                    if (Role::where('name', $rName)->exists()) {
                        $validRoles[] = $rName;
                    }
                }
                if (empty($validRoles)) {
                    $validRoles = ['teacher'];
                }
                $user->syncRoles($validRoles);
            }
        });

        $this->command->info("Seeding de usuarios completado exitosamente.");
    }
}
