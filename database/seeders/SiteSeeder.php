<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Programa;
use App\Models\Sede;
use App\Models\Site;
use App\Models\Teacher;
use Illuminate\Database\Seeder;

/**
 * Seeder de Asignaciones de Sede/Área del Sistema SIGEDOR.
 *
 * Registra la adscripción física y departamental de cada profesor,
 * junto con las unidades de crédito, horas semanales y secciones asignadas.
 */
class SiteSeeder extends Seeder
{
    public function run(): void
    {
        $filePath = database_path('seeders/data/sites.csv');

        if (! file_exists($filePath)) {
            $this->command->warn('Archivo sites.csv no encontrado.');

            return;
        }

        $csvContent = mb_convert_encoding(file_get_contents($filePath), 'UTF-8', 'UTF-8');
        $lines = array_filter(array_map('trim', explode("\n", $csvContent)));
        $header = str_getcsv(array_shift($lines), ';');

        $defaultSede = Sede::first() ?? Sede::create(['nombre' => 'Sede Central/San Juan de los Morros']);
        $defaultArea = Area::first() ?? Area::create(['nombre' => 'Ingeniería de sistemas']);
        $defaultPrograma = Programa::first() ?? Programa::create(['nombre' => 'Ingeniería en informática']);

        $processed = 0;

        foreach ($lines as $line) {
            if (empty($line)) {
                continue;
            }

            $data = str_getcsv($line, ';');

            if (count($data) < 5) {
                continue;
            }

            $cdi = preg_replace('/[^0-9]/', '', trim($data[1]));
            if (empty($cdi)) {
                continue;
            }

            $teacher = Teacher::where('cdi', $cdi)->first();
            if (! $teacher) {
                continue;
            }

            $sedeNombre = trim($data[2] ?? '');
            $areaNombre = trim($data[3] ?? '');
            $programaNombre = trim($data[4] ?? '');

            $sede = Sede::where('nombre', $sedeNombre)->first() ?? $defaultSede;
            $area = Area::where('nombre', $areaNombre)->first() ?? $defaultArea;
            $programa = Programa::where('nombre', $programaNombre)->first() ?? $defaultPrograma;

            $uc = ! empty(trim($data[5] ?? '')) ? (int) $data[5] : 3;
            $weekHours = ! empty(trim($data[6] ?? '')) ? (int) $data[6] : 6;
            $sections = ! empty(trim($data[7] ?? '')) ? (int) $data[7] : 2;
            $info = ! empty(trim($data[8] ?? '')) ? trim($data[8]) : null;

            $site = Site::updateOrCreate(
                [
                    'teacher_cdi' => $cdi,
                    'sede_id' => $sede->id,
                    'area_id' => $area->id,
                    'programa_id' => $programa->id,
                ],
                [
                    'uc' => $uc,
                    'weekHours' => $weekHours,
                    'sections' => $sections,
                    'info' => $info,
                    'is_active' => true,
                    'is_available' => true,
                ]
            );

            $teacher->update([
                'site_id' => $site->id,
                'sede_id' => $sede->id,
                'area_id' => $area->id,
                'programa_id' => $programa->id,
            ]);

            $processed++;
        }

        $this->command->info("Seeding de {$processed} asignaciones de sede completado exitosamente.");
    }
}
