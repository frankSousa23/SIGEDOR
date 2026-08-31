<?php

namespace App\Console\Commands;

use Faker\Factory as Faker;
use Illuminate\Console\Command;

class AnonymizeTeachersCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:anonymize-teachers-csv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Anonymize sensitive data in database/seeders/data/teachers.csv';

    /**
     * Ejecuta el proceso de anonimización del CSV de docentes.
     *
     * Lee el archivo `database/seeders/data/teachers.csv`, reemplaza los campos
     * sensibles (nombre, apellido, CDI, teléfono, correo) con datos ficticios
     * generados por Faker (locale: es_ES), preservando datos no personales
     * (sede, área, programa, asignatura, fechas).
     *
     * @return int Código de salida del comando (0 = éxito)
     */
    public function handle(): int
    {
        $csvPath = base_path('database/seeders/data/teachers.csv');

        if (! file_exists($csvPath)) {
            $this->error("File not found at {$csvPath}");

            return self::FAILURE;
        }

        $this->info("Anonymizing file: {$csvPath}");

        $rows = array_map(function ($v) {
            return str_getcsv($v, ';');
        }, file($csvPath));
        $header = array_shift($rows);

        $this->info('First few rows read successfully.');
        $this->table($header, array_slice($rows, 0, 5));

        $faker = Faker::create('es_ES');

        $anonymizedRows = [];
        foreach ($rows as $row) {
            // id;name;surName;cdi;genre;phone;email;birthDate;datePromotion;asignaturePromotion;user_email;sede_nombre;area_nombre;programa_nombre
            // 0: id
            // 1: name
            // 2: surName
            // 3: cdi
            // 4: genre
            // 5: phone
            // 6: email
            // 7: birthDate
            // 8: datePromotion
            // 9: asignaturePromotion
            // 10: user_email
            // 11: sede_nombre
            // 12: area_nombre
            // 13: programa_nombre

            if (count($row) < 14) {
                continue;
            }

            $row[1] = $faker->firstName;
            $row[2] = $faker->lastName;
            $row[3] = $faker->unique()->numerify('########');
            $row[5] = $faker->phoneNumber;

            $email = $faker->unique()->safeEmail;
            $row[6] = $email;
            $row[10] = $email;

            $anonymizedRows[] = $row;
        }

        // Write to temp file first
        $tempPath = base_path('database/seeders/data/teachers_temp.csv');
        $fp = fopen($tempPath, 'w');
        fputcsv($fp, $header, ';');
        foreach ($anonymizedRows as $row) {
            fputcsv($fp, $row, ';');
        }
        fclose($fp);

        $this->info("Temporary file created at: {$tempPath}");

        // Now replace original
        rename($tempPath, $csvPath);

        $this->info('Original CSV replaced with anonymized data.');

        return self::SUCCESS;
    }
}
