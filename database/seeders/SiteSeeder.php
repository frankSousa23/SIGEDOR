<?php

namespace Database\Seeders;

use App\Models\Site;
use App\Models\Teacher;
use App\Models\Sede;
use App\Models\Area;
use App\Models\Programa;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SiteSeeder extends Seeder
{
    private $processed = 0;
    private $errors = 0;
    private $missingTeachers = 0;
    private $missingRelations = 0;

    public function run()
    {
        $this->command->info('🚀 Iniciando carga masiva de asignaciones docentes');
        $this->command->warn('⚠️ Validando relaciones requeridas primero...');

        DB::beginTransaction();
        try {
            $this->processCSVFile();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->handleProcessingError(0, $e);
        }

        $this->outputFinalResults();
    }

    private function processCSVFile()
    {
        $filePath = database_path('seeders/data/sites.csv');
        $rows = $this->readCSVFile($filePath);

        $this->command->info("📁 Archivo detectado: " . basename($filePath));
        $this->command->line("📊 Total de registros CSV: " . number_format(count($rows), 0));

        foreach ($rows as $line => $row) {
            $this->processSingleRecord($line + 1, $row);
        }
    }

    private function readCSVFile($path)
    {
        $rows = [];
        $handle = fopen($path, 'r');

        while (($data = fgetcsv($handle, 0, ';')) !== false) {
            $rows[] = $data;
        }

        fclose($handle);
        array_shift($rows); // Remover encabezado
        return $rows;
    }

    private function processSingleRecord($lineNumber, $row)
    {
        try {
            $this->validateCSVRowStructure($row, $lineNumber);

            $cdi = $this->normalizeAndVerifyCDI($row[1], $lineNumber);
            $relations = $this->locateRequiredRelationships($row, $lineNumber);

            $this->createOrUpdateSiteRecord($cdi, $relations, $row);
            $this->processed++;

        } catch (\Exception $e) {
            $this->handleProcessingError($lineNumber, $e);
        }
    }

    private function validateCSVRowStructure($row, $line)
    {
        if (count($row) < 7) {
            throw new \Exception("❌ Estructura inválida. Columnas requeridas: 7 | Encontradas: " . count($row));
        }
    }

    private function normalizeAndVerifyCDI($rawCDI, $line)
    {
        $cdi = str_pad(preg_replace('/[^0-9]/', '', $rawCDI), 8, '0', STR_PAD_LEFT);

        if (!Teacher::where('cdi', $cdi)->exists()) {
            $this->missingTeachers++;
            throw new \Exception("👨🏫 Docente no registrado con CDI: {$cdi}");
        }

        return $cdi;
    }

    private function locateRequiredRelationships($row, $line)
{
    // Búsqueda exacta con parámetros vinculados
    $sede = Sede::whereRaw('BINARY nombre = ?', [trim($row[2])])->first();
    $area = Area::whereRaw('BINARY nombre = ?', [trim($row[3])])->first();
    $programa = Programa::whereRaw('BINARY nombre = ?', [trim($row[4])])->first();

    if (!$sede) {
        throw new \Exception("SEDE no encontrada: '{$row[2]}'");
    }
    if (!$area) {
        throw new \Exception("ÁREA no encontrada: '{$row[3]}'");
    }
    if (!$programa) {
        throw new \Exception("PROGRAMA no encontrado: '{$row[4]}'");
    }

    return [
        'sede' => $sede,
        'area' => $area,
        'programa' => $programa
    ];
}

    private function formatMissingRelations($row)
    {
        return sprintf('Sede: "%s" | Area: "%s" | Programa: "%s"',
            $row[2], $row[3], $row[4]);
    }

    private function createOrUpdateSiteRecord($cdi, $relations, $row)
    {
        Site::updateOrCreate(
            [
                'teacher_cdi' => $cdi,
                'sede_id' => $relations['sede']->id,
                'area_id' => $relations['area']->id,
                'programa_id' => $relations['programa']->id
            ],
            [
                'uc' => Str::limit(trim($row[5]), 250, ''),
                'is_active' => $this->parseBoolean($row[6]),
                'weekHours' => 0, // Valor temporal seguro
                'sections' => 0,  // Valor temporal seguro
                'last_assignment' => now()
            ]
        );
    }

    private function parseBoolean($value)
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    private function handleProcessingError($line, $e)
    {
        Log::error("LÍNEA {$line}: {$e->getMessage()}");
        $this->command->error("[L{$line}] " . Str::limit($e->getMessage(), 45));
        $this->errors++;
    }

    private function outputFinalResults()
    {
        $this->command->line("\n📊 RESUMEN FINAL:");
        $this->command->table(
            ['Procesados', 'Docentes Faltantes', 'Relaciones Faltantes', 'Errores'],
            [[
                $this->processed,
                $this->missingTeachers,
                $this->missingRelations,
                $this->errors
            ]]
        );

        if ($this->errors > 0) {
            $this->command->error("\n🔧 Acciones Requeridas:");
            $this->command->line("1. Verificar CDI en teachers.csv");
            $this->command->line("2. Validar nombres exactos en sedes, áreas y programas");
            $this->command->line("3. Revisar log completo: " . storage_path('logs/laravel.log'));
        }
    }
}