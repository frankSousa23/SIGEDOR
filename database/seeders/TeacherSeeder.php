<?php

namespace Database\Seeders;

use App\Models\Teacher;
use App\Models\User;
use App\Models\Sede;
use App\Models\Area;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class TeacherSeeder extends Seeder
{
    private $cdiRegistry = [];

    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Teacher::truncate();

        $csvPath = base_path("database/seeders/data/teachers.csv");
        $csvFile = fopen($csvPath, 'r');
        $header = fgetcsv($csvFile, 2000, ";");

        $this->command->info("Iniciando proceso de seeding para teachers...");

        $counter = 0;
        $errors = 0;

        while (($data = fgetcsv($csvFile, 2000, ";")) !== FALSE) {
            try {
                $this->processRecord($data, $counter);
                $counter++;
            } catch (\Exception $e) {
                Log::error("Error en línea " . ($counter + 1) . ": " . $e->getMessage());
                $this->command->error($e->getMessage());
                $errors++;
                continue;
            }
        }

        fclose($csvFile);
        Schema::enableForeignKeyConstraints();
        $this->command->info("Registros exitosos: {$counter} | Errores: {$errors}");
    }

    private function processRecord($data, $lineNumber)
    {
        // Validación estricta de estructura CSV
        if (count($data) < 11) {
            throw new \Exception("Línea {$lineNumber}: Faltan columnas (Requiere 11, tiene " . count($data) . ")");
        }

        // Normalización de datos
        $userName = $this->normalizeName($data[1]);
        $cdi = $this->validateCdi($data[2], $lineNumber);
        $name = Str::limit(trim($data[3]), 50);
        $surName = Str::limit(trim($data[4]), 50);

        // Búsqueda de usuario con creación temporal
        $user = User::whereRaw('REPLACE(REPLACE(name, " ", ""), ".", "") = ?',
            Str::replace([' ', '.'], '', $userName))->first();

        if (!$user) {
            $user = User::create([
                'name' => $userName,
                'email' => Str::slug($userName).'@sigedor.temp',
                'password' => bcrypt('temporal'.rand(1000,9999)),
                'sede_id' => Sede::first()->id,
                'area_id' => Area::first()->id,
                'is_active' => false,
                'is_approved' => false
            ]);
            $user->assignRole('teacher');
            $this->command->warn("Usuario temporal creado: {$userName}");
        }

        Teacher::create([
            'name' => $name,
            'surName' => $surName,
            'cdi' => $cdi,
            'genre' => $this->validateGenre($data[5]),
            'phone' => $this->formatPhone($data[6]),
            'email' => $this->normalizeEmail($data[7], $userName),
            'birthDate' => $this->parseDate($data[8]),
            'datePromotion' => $this->parseDate($data[9]),
            'asignaturePromotion' => Str::limit($data[10], 250),
            'user_id' => $user->id,
            'sede_id' => $user->sede_id,
            'area_id' => $user->area_id
        ]);
    }

    private function normalizeName($name)
    {
        return Str::squish(preg_replace('/\s*\.\s*/', ' ', $name));
    }

    private function validateCdi($cdi, $lineNumber)
    {
        $cdi = preg_replace('/[^0-9]/', '', trim($cdi));
        if (empty($cdi)) {
            throw new \Exception("Línea {$lineNumber}: CDI vacío");
        }

        if (in_array($cdi, $this->cdiRegistry)) {
            $newCdi = $cdi . '-' . (count($this->cdiRegistry) + 1);
            $this->cdiRegistry[] = $newCdi;
            return $newCdi;
        }

        $this->cdiRegistry[] = $cdi;
        return $cdi;
    }

    private function validateGenre($genre)
    {
        return in_array($genre, ['F', 'M']) ? $genre : 'F';
    }

    private function formatPhone($phone)
    {
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
        return (strlen($cleanPhone) >= 10) ? $cleanPhone : null;
    }

    private function normalizeEmail($email, $userName)
    {
        $baseEmail = filter_var($email, FILTER_VALIDATE_EMAIL)
            ? $email
            : Str::slug($userName) . '@sigedor.com';

        $originalEmail = $baseEmail;
        $counter = 1;

        while (Teacher::where('email', $baseEmail)->exists()) {
            $baseEmail = preg_replace('/(.+?)(@.+)/', "$1.{$counter}$2", $originalEmail);
            $counter++;
        }

        return $baseEmail;
    }

    private function parseDate($date)
    {
        if (in_array($date, ['Servidor no encontrado', 'N/A', ''])) return null;

        $parsedDate = \DateTime::createFromFormat('d/m/Y', $date);
        if (!$parsedDate) {
            throw new \Exception("Fecha inválida: {$date}");
        }
        return $parsedDate->format('Y-m-d');
    }
}
