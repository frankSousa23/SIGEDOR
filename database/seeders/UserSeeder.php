<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Sede;
use App\Models\Area;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
{

    // Crear roles si no existen
    Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'area_manager', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'teacher', 'guard_name' => 'web']);

    // Crear usuario admin si no existe
    $admin = User::create([
        'name' => 'Admin',
        'email' => 'admin@sigedor.com',
        'password' => 'password',
        'sede_id' => Sede::inRandomOrder()->first()->id,
        'area_id' => Area::inRandomOrder()->first()->id,
        'is_active' => true,
        'is_approved' => true
    ])->assignRole('admin');

    $area_manager = User::create([
        'name' => 'Jefe de Área',
        'email' => 'areamanager@sigedor.com',
        'password' => 'password',
        'sede_id' => Sede::inRandomOrder()->first()->id,
        'area_id' => Area::inRandomOrder()->first()->id,
        'is_active' => true,
        'is_approved' => true
    ])->assignRole('area_manager');

    DB::transaction(function () {
        $csvPath = database_path('seeders/data/users.csv');

        // Leer CSV con corrección de encoding y espacios
        $csvContent = mb_convert_encoding(file_get_contents($csvPath), 'UTF-8', 'UTF-8');
        $csvData = array_map(function($line) {
            return str_getcsv(trim($line), ';');
        }, explode("\n", $csvContent));

        $headers = array_map('trim', array_shift($csvData));

        foreach ($csvData as $index => $row) {
            try {
                // Validación estricta de filas
                if (count($row) < 9 || empty($row[2])) {
                    $this->command->error("Fila {$index} incompleta: " . implode(' | ', $row));
                    continue;
                }

                // Normalización de datos
                $userData = [
                    'name' => trim($row[1]),
                    'email' => strtolower(str_replace(' ', '', trim($row[2]))), // Eliminar espacios en email
                    'password' => trim($row[3]),
                    'sede_nombre' => trim($row[4]),
                    'area_nombre' => trim($row[5]),
                    'rol_name' => trim($row[6]),
                    'is_active' => (int)trim($row[7]) === 1,
                    'is_approved' => (int)trim($row[8]) === 1
                ];

                // Validar relaciones
                $sede = Sede::where('nombre', $userData['sede_nombre'])->first();
                $area = Area::where('nombre', $userData['area_nombre'])->first();
                $role = Role::where('name', $userData['rol_name'])->first();

                if (!$sede || !$area || !$role) {
                    $this->command->error("Relaciones no encontradas en fila {$index}: " . implode(' | ', $row));
                    continue;
                }

                // Crear usuario
                User::updateOrCreate(
                    ['email' => $userData['email']],
                    [
                        'name' => $userData['name'],
                        'password' => Hash::make($userData['password']),
                        'sede_id' => $sede->id,
                        'area_id' => $area->id,
                        'is_active' => $userData['is_active'],
                        'is_approved' => $userData['is_approved']
                    ]
                )->assignRole($role);

                $this->command->info("Usuario {$userData['email']} creado");

            } catch (\Exception $e) {
                $this->command->error("Error en fila {$index}: " . $e->getMessage());
                continue;
            }
        }
    });
}
}
