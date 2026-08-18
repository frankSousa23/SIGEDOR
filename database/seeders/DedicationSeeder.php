<?php

namespace Database\Seeders;

use App\Models\Dedication;
use App\Models\Teacher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Seeder de Dedicaciones Docentes del Sistema SIGEDOR.
 *
 * Registra la carga horaria semanal, asignaciones administrativas/docentes
 * y cantidad de estudiantes atendidos por cada profesor.
 */
class DedicationSeeder extends Seeder
{
    public function run(): void
    {
        $filePath = database_path('seeders/data/dedications.csv');

        if (!file_exists($filePath)) {
            $this->command->warn("Archivo dedications.csv no encontrado.");
            return;
        }

        $csvContent = mb_convert_encoding(file_get_contents($filePath), 'UTF-8', 'UTF-8');
        $lines = array_filter(array_map('trim', explode("\n", $csvContent)));
        $header = str_getcsv(array_shift($lines), ';');

        $processed = 0;

        foreach ($lines as $line) {
            if (empty($line)) {
                continue;
            }

            $data = str_getcsv($line, ';');

            if (count($data) < 4) {
                continue;
            }

            $cdi = preg_replace('/[^0-9]/', '', trim($data[1]));
            if (empty($cdi)) {
                continue;
            }

            $teacher = Teacher::where('cdi', $cdi)->first();
            if (!$teacher) {
                continue;
            }

            $name = trim($data[2] ?? 'Tiempo Convencional');
            $hours = (int) ($data[3] ?? 12);
            $director = !empty(trim($data[4] ?? '')) ? trim($data[4]) : null;
            $studentNumber = !empty(trim($data[5] ?? '')) ? (int) $data[5] : null;
            $studentHours = !empty(trim($data[6] ?? '')) ? (int) $data[6] : null;
            $info = !empty(trim($data[7] ?? '')) ? trim($data[7]) : null;

            $dedication = Dedication::updateOrCreate(
                ['teacher_cdi' => $cdi],
                [
                    'name' => $name,
                    'hours' => $hours,
                    'director' => $director,
                    'studentNumber' => $studentNumber,
                    'studentHours' => $studentHours,
                    'info' => $info,
                ]
            );

            $teacher->update(['dedication_id' => $dedication->id]);
            $processed++;
        }

        $this->command->info("Seeding de {$processed} dedicaciones completado exitosamente.");
    }
}