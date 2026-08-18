<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Seeder de Categorías Docentes del Sistema SIGEDOR.
 *
 * Registra el escalafón inicial de los docentes, grados académicos y fechas de ascenso.
 */
class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $csvFile = database_path('seeders/data/categories.csv');

        if (!file_exists($csvFile)) {
            $this->command->warn("Archivo categories.csv no encontrado.");
            return;
        }

        $csvContent = mb_convert_encoding(file_get_contents($csvFile), 'UTF-8', 'UTF-8');
        $lines = array_filter(array_map('trim', explode("\n", $csvContent)));
        $header = str_getcsv(array_shift($lines), ';');

        $processed = 0;

        foreach ($lines as $line) {
            if (empty($line)) {
                continue;
            }

            $data = str_getcsv($line, ';');

            if (count($data) < 11) {
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

            $category = Category::updateOrCreate(
                ['teacher_cdi' => $cdi],
                [
                    'preTitle' => $this->cleanField($data[2] ?? null),
                    'lastTitle' => $this->cleanField($data[3] ?? null),
                    'current_category' => $this->normalizeCategory($data[4] ?? 'Instructor'),
                    'instructor' => $this->parseDate($data[5] ?? null),
                    'asistente' => $this->parseDate($data[6] ?? null),
                    'agregado' => $this->parseDate($data[7] ?? null),
                    'asociado' => $this->parseDate($data[8] ?? null),
                    'titular' => $this->parseDate($data[9] ?? null),
                    'disable_assistant_rule' => (bool) ($data[10] ?? false),
                    'info' => $this->cleanField($data[11] ?? null),
                ]
            );

            $teacher->update(['category_id' => $category->id]);
            $processed++;
        }

        $this->command->info("Seeding de {$processed} categorías completado exitosamente.");
    }

    private function cleanField(?string $value): ?string
    {
        $value = trim((string) $value);
        return in_array($value, ['Pendiente corregir', '', 'null']) ? null : $value;
    }

    private function parseDate(?string $value): ?string
    {
        $value = trim((string) $value);
        if (empty($value)) return null;

        try {
            return Carbon::createFromFormat('d/m/Y', $value)->toDateString();
        } catch (\Throwable $e) {
            try {
                return Carbon::parse($value)->toDateString();
            } catch (\Throwable $e) {
                return null;
            }
        }
    }

    private function normalizeCategory(?string $category): string
    {
        $category = Str::title(trim((string) $category));
        $valid = ['Instructor', 'Asistente', 'Agregado', 'Asociado', 'Titular'];
        return in_array($category, $valid) ? $category : 'Instructor';
    }
}