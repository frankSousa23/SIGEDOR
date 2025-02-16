<?php

namespace Database\Seeders;

use App\Models\Dedication;
use App\Models\Teacher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DedicationSeeder extends Seeder
{
    private $processed = 0;
    private $errors = 0;
    private $duplicates = 0;
    private $rows = [];

    public function run()
    {
        $filePath = database_path('seeders/data/dedications.csv');

        if (!file_exists($filePath)) {
            Log::error('Archivo CSV no encontrado: ' . $filePath);
            $this->command->error('¡Error crítico! Archivo no encontrado: ' . $filePath);
            return;
        }

        $rows = $this->readCSV($filePath);

        DB::transaction(function () use ($rows) {
            foreach ($rows as $index => $row) {
                $this->processRow($row, $index + 1);
            }
        });

        $this->outputResults();
    }

    private function readCSV($path)
    {
        $rows = [];
        $handle = fopen($path, 'r');
        
        while (($data = fgetcsv($handle, 0, ';')) !== false) {
            $rows[] = $data;
        }
        
        fclose($handle);
        return $rows;
    }

    private function processRow(array $data, int $lineNumber)
    {
        // Saltar encabezado y filas vacías
        if ($lineNumber === 1 || empty($data[1])) {
            return;
        }

        // Validar estructura mínima
        if (count($data) < 5) {
            $this->logError($lineNumber, "Estructura inválida. Campos requeridos: 5, obtenidos: " . count($data));
            return;
        }

        try {
            $cdi = $this->normalizeCdi($data[1]);
            $type = $this->normalizeType($data[3]);
            $hours = $this->validateHours($data[4]);

            // Validar relación con Teacher
            if (!Teacher::where('cdi', $cdi)->exists()) {
                $this->logError($lineNumber, "CDI no registrado: {$cdi}");
                return;
            }

            // Prevenir duplicados
            if (Dedication::where('teacher_cdi', $cdi)->exists()) {
                $this->duplicates++;
                Log::warning("Deduplicación línea {$lineNumber}: CDI {$cdi}");
                return;
            }

            // Crear registro
            Dedication::create([
                'teacher_cdi' => $cdi,
                'type' => $type,
                'hours' => $hours,
                'name' => $this->generateDedicationName($type, $hours),
                'is_active' => true
            ]);

            $this->processed++;

        } catch (\Exception $e) {
            $this->logError($lineNumber, "Error: " . $e->getMessage());
        }
    }

    private function normalizeCdi($value)
    {
        return str_pad(trim($value), 8, '0', STR_PAD_LEFT);
    }

    private function normalizeType($type)
    {
        $type = Str::upper(str_replace(['.', ','], '', trim($type)));
        $validTypes = ['TCV', 'DE', 'MT', 'TC', 'EX'];
        return in_array($type, $validTypes) ? $type : 'TCV';
    }

    private function validateHours($hours)
    {
        $hours = (int)preg_replace('/[^0-9]/', '', $hours);
        return max(0, min($hours, 40)); // Rango 0-40 horas
    }

    private function generateDedicationName($type, $hours)
    {
        $nombres = [
            'TCV' => 'Tiempo Convencional',
            'DE' => 'Dedicación Exclusiva',
            'MT' => 'Medio Tiempo',
            'TC' => 'Tiempo Completo',
            'EX' => 'Exclusiva'
        ];
        
        return ($nombres[$type] ?? 'Tiempo Convencional') . " ({$hours}h)";
    }

    private function logError($line, $message)
    {
        Log::error("Línea {$line}: {$message}");
        $this->command->warn("Error línea {$line}: " . Str::limit($message, 50));
        $this->errors++;
    }

    private function outputResults()
    {
        $this->command->table(
            ['Procesados', 'Duplicados', 'Errores', 'Total CSV'],
            [[
                $this->processed, 
                $this->duplicates, 
                $this->errors, 
                count($this->rows) - 1 // Excluir encabezado
            ]]
        );

        if ($this->errors > 0) {
            $this->command->error('Revise el log completo: ' . storage_path('logs/laravel.log'));
        }
    }
}