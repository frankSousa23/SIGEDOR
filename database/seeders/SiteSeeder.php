<?php

namespace Database\Seeders;

use App\Models\Site;
use App\Models\Teacher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SiteSeeder extends Seeder
{
    private $processed = 0;
    private $errors = 0;
    private $duplicates = 0;
    private $rows = [];

    public function run()
    {
        $csvFile = database_path('seeders/data/sites.csv');

        if (!file_exists($csvFile)) {
            $this->handleFileNotFound($csvFile);
            return;
        }

        $this->rows = $this->readCSV($csvFile);

        if (empty($this->rows)) {
            $this->command->error('El archivo CSV está vacío o tiene formato incorrecto');
            return;
        }

        DB::transaction(function () {
            foreach ($this->rows as $index => $row) {
                $this->processRow($row, $index + 1);
            }
        });

        $this->outputResults();
    }

    private function readCSV($filePath)
    {
        $rows = [];
        $handle = fopen($filePath, 'r');

        if ($handle === false) {
            return [];
        }

        $header = fgetcsv($handle, 0, ';');
        
        if ($header === false) {
            fclose($handle);
            return [];
        }

        while (($data = fgetcsv($handle, 0, ';')) !== false) {
            if (count($data) === 1 && trim($data[0]) === '') {
                continue;
            }
            $rows[] = array_combine($header, $data);
        }

        fclose($handle);
        return $rows;
    }

    private function processRow($row, $lineNumber)
    {
        try {
            $this->validateRow($row);
            
            $teacherCdi = trim($row['teacher_cdi']);
            $sede = $this->normalizeString($row['sede_nombre']);
            $area = $this->normalizeString($row['area_nombre']);
            $programa = $this->normalizeString($row['programa_nombre']);
            $uc = $this->sanitizeUc($row['uc'] ?? 1);

            if (!$this->teacherExists($teacherCdi)) {
                throw new \Exception("Docente con CDI $teacherCdi no registrado");
            }

            if ($this->isDuplicateAssignment($teacherCdi, $sede, $area)) {
                $this->duplicates++;
                return;
            }

            Site::updateOrCreate(
                [
                    'teacher_cdi' => $teacherCdi,
                    'sede_nombre' => $sede,
                    'area_nombre' => $area
                ],
                [
                    'programa_nombre' => $programa,
                    'uc' => $uc,
                    'is_active' => true,
                    'last_assignment' => now()  // Nueva columna requerida
                ]
            );

            $this->processed++;

        } catch (\Exception $e) {
            $this->logError($lineNumber, $e->getMessage());
            $this->errors++;
        }
    }

    private function validateRow($row)
    {
        $requiredFields = [
            'teacher_cdi' => 'CDI docente',
            'sede_nombre' => 'Nombre de sede',
            'area_nombre' => 'Área académica'
        ];

        foreach ($requiredFields as $field => $name) {
            if (empty(trim($row[$field] ?? ''))) {
                throw new \Exception("Campo requerido faltante: $name");
            }
        }
    }

    private function normalizeString($value)
    {
        $value = trim($value);
        if ($value === 'Pendiente corregir' || empty($value)) {
            return 'Por definir';
        }
        return Str::title(Str::lower($value));
    }

    private function sanitizeUc($value)
    {
        $uc = (int) preg_replace('/[^0-9]/', '', $value);
        return max(1, min(30, $uc));
    }

    private function teacherExists($cdi)
    {
        return Teacher::where('cdi', $cdi)->exists();
    }

    private function isDuplicateAssignment($cdi, $sede, $area)
    {
        return Site::where('teacher_cdi', $cdi)
            ->where('sede_nombre', $sede)
            ->where('area_nombre', $area)
            ->exists();
    }

    private function handleFileNotFound($path)
    {
        Log::error("Archivo no encontrado: $path");
        $this->command->error("¡Error crítico! El archivo CSV no existe en: $path");
        $this->command->warn('Verifica que:');
        $this->command->line('1. El archivo exista en la ruta especificada');
        $this->command->line('2. El nombre del archivo coincida exactamente (incluyendo mayúsculas)');
        $this->command->line('3. El archivo tenga permisos de lectura');
    }

    private function logError($line, $message)
    {
        Log::error("Línea $line: $message");
        $this->command->warn("Error en línea $line: " . Str::limit($message, 50));
    }

    private function outputResults()
    {
        $totalRows = count($this->rows);
        $this->command->newLine(2);
        $this->command->info('Resultado del proceso de asignaciones:');
        $this->command->table(
            ['Procesados', 'Duplicados', 'Errores', 'Total CSV'],
            [[
                $this->processed, 
                $this->duplicates, 
                $this->errors, 
                $totalRows
            ]]
        );
        
        if ($this->errors > 0) {
            $this->command->error('Errores detectados. Revisa el archivo de logs para detalles:');
            $this->command->line(storage_path('logs/laravel.log'));
        }
    }
}