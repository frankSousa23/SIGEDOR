<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    private $processed = 0;
    private $errors = 0;
    private $duplicates = 0;
    private $rows = [];

    public function run()
    {
        $csvFile = database_path('seeders/data/categories.csv');

        if (!file_exists($csvFile)) {
            $this->handleFileNotFound($csvFile);
            return;
        }

        $this->rows = $this->readCSV($csvFile);

        DB::transaction(function () {
            foreach ($this->rows as $index => $row) {
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

    private function processRow($data, $lineNumber)
    {
        try {
            if ($lineNumber === 1 || empty($data[1])) {
                return;
            }

            $cdi = $this->normalizeCdi($data[1]);
            $teacher = Teacher::where('cdi', $cdi)->first();

            if (!$teacher) {
                $this->logError($lineNumber, "CDI no existe: {$cdi}");
                return;
            }

            $categoryData = [
                'preTitle' => $this->cleanField($data[2]),
                'lastTitle' => $this->cleanField($data[3]),
                'disable_assistant_rule' => $this->parseBoolean($data[4]),
                'current_category' => $this->normalizeCategory($data[5]),
                'instructor' => $this->parseDate($data[6]),
                'asistente' => $this->parseDate($data[7]),
                'agregado' => $this->parseDate($data[8]),
                'asociado' => $this->parseDate($data[9]),
                'titular' => $this->parseDate($data[10]),
                'is_active' => $this->parseBoolean($data[11]),
                'teachers_count' => 1,
                'is_available' => true
            ];

            Category::updateOrCreate(
                ['teacher_cdi' => $cdi],
                $categoryData
            );

            $this->processed++;

        } catch (\Exception $e) {
            $this->logError($lineNumber, $e->getMessage());
        }
    }

    private function cleanField($value)
    {
        $value = trim($value);
        return in_array($value, ['Pendiente corregir', '']) ? null : $value;
    }

    private function parseDate($value)
    {
        $value = trim($value);
        if (empty($value)) return null;

        try {
            return Carbon::createFromFormat('d/m/Y', $value)->toDateString();
        } catch (\Exception $e) {
            try {
                return Carbon::createFromFormat('Y-m-d', $value)->toDateString();
            } catch (\Exception $e) {
                return null;
            }
        }
    }

    private function normalizeCategory($category)
    {
        $category = Str::upper(str_replace(['.', ','], '', trim($category)));
        $validCategories = ['INSTRUCTOR', 'ASISTENTE', 'AGREGADO', 'ASOCIADO', 'TITULAR'];
        
        return in_array($category, $validCategories) ? $category : 'INSTRUCTOR';
    }

    private function parseBoolean($value)
    {
        $value = Str::lower(trim($value));
        return in_array($value, ['1', 'true', 'si', 'yes']);
    }

    private function normalizeCdi($value)
    {
        return str_pad(trim($value), 8, '0', STR_PAD_LEFT);
    }

    private function handleFileNotFound($path)
    {
        Log::error("Archivo no encontrado: {$path}");
        $this->command->error("Error crítico: Archivo CSV no existe en {$path}");
        $this->command->warn('Verifique:');
        $this->command->line('1. Existencia del archivo');
        $this->command->line('2. Permisos de lectura');
        $this->command->line('3. Codificación UTF-8');
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