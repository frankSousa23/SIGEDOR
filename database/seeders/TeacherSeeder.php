<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Programa;
use App\Models\Sede;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * Seeder de Docentes del Sistema SIGEDOR.
 *
 * Carga el registro de docentes académicos vinculándolos a su usuario de sistema,
 * asignando CDI, datos demográficos, fechas de ingreso/ascenso y cátedra asignada.
 */
class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Teacher::truncate();

        $csvPath = database_path('seeders/data/teachers.csv');

        if (!file_exists($csvPath)) {
            $this->command->warn("Archivo teachers.csv no encontrado.");
            Schema::enableForeignKeyConstraints();
            return;
        }

        $csvContent = mb_convert_encoding(file_get_contents($csvPath), 'UTF-8', 'UTF-8');
        $lines = array_filter(array_map('trim', explode("\n", $csvContent)));
        $header = str_getcsv(array_shift($lines), ';');

        $defaultSede = Sede::first() ?? Sede::create(['nombre' => 'Sede Central/San Juan de los Morros']);
        $defaultArea = Area::first() ?? Area::create(['nombre' => 'Ingeniería de sistemas']);
        $defaultPrograma = Programa::first() ?? Programa::create(['nombre' => 'Ingeniería en Informática']);

        $counter = 0;

        foreach ($lines as $index => $line) {
            if (empty($line)) {
                continue;
            }

            $data = str_getcsv($line, ';');

            if (count($data) < 10) {
                continue;
            }

            $name = trim($data[1]);
            $surName = trim($data[2]);
            $cdi = preg_replace('/[^0-9]/', '', trim($data[3]));
            if (empty($cdi)) {
                $cdi = (string) (10101000 + $counter + 1);
            }

            $genre = in_array(strtoupper(trim($data[4])), ['F', 'M']) ? strtoupper(trim($data[4])) : 'M';
            $phone = trim($data[5]);
            $email = strtolower(trim($data[6]));
            $birthDate = $this->parseDate($data[7] ?? null);
            $datePromotion = $this->parseDate($data[8] ?? null);
            $asignature = trim($data[9] ?? '');
            $userEmail = strtolower(trim($data[10] ?? $email));
            $sedeNombre = trim($data[11] ?? '');
            $areaNombre = trim($data[12] ?? '');
            $programaNombre = trim($data[13] ?? '');

            $sede = Sede::where('nombre', $sedeNombre)->first() ?? $defaultSede;
            $area = Area::where('nombre', $areaNombre)->first() ?? $defaultArea;
            $programa = Programa::where('nombre', $programaNombre)->first() ?? $defaultPrograma;

            // Buscar usuario existente por email o crear uno
            $user = User::where('email', $userEmail)->first();
            if (!$user) {
                $user = User::create([
                    'name' => "Prof. {$name} {$surName}",
                    'email' => $userEmail,
                    'password' => Hash::make('password'),
                    'sede_id' => $sede->id,
                    'area_id' => $area->id,
                    'is_active' => true,
                    'is_approved' => true,
                ]);
                $user->assignRole('teacher');
            }

            Teacher::updateOrCreate(
                ['cdi' => $cdi],
                [
                    'name' => $name,
                    'surName' => $surName,
                    'genre' => $genre,
                    'phone' => $phone,
                    'email' => $email,
                    'birthDate' => $birthDate,
                    'datePromotion' => $datePromotion,
                    'asignaturePromotion' => $asignature,
                    'user_id' => $user->id,
                    'sede_id' => $sede->id,
                    'area_id' => $area->id,
                    'programa_id' => $programa->id,
                ]
            );

            $counter++;
        }

        Schema::enableForeignKeyConstraints();
        $this->command->info("Seeding de {$counter} docentes completado exitosamente.");
    }

    private function parseDate(?string $date): ?string
    {
        if (empty($date) || in_array($date, ['N/A', 'null', 'None'])) {
            return null;
        }

        try {
            $parsed = \DateTime::createFromFormat('d/m/Y', trim($date));
            if ($parsed) {
                return $parsed->format('Y-m-d');
            }

            $carbonDate = \Carbon\Carbon::parse($date);
            return $carbonDate->format('Y-m-d');
        } catch (\Throwable $e) {
            return null;
        }
    }
}
