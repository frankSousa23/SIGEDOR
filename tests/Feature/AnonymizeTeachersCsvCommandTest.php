<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class AnonymizeTeachersCsvCommandTest extends TestCase
{
    protected string $csvPath;

    protected string $backupPath;

    protected function setUp(): void
    {
        parent::setUp();

        $this->csvPath = database_path('seeders/data/teachers.csv');
        $this->backupPath = database_path('seeders/data/teachers.csv.bak_test');

        // Setup a mock CSV file for testing
        if (! File::exists(dirname($this->csvPath))) {
            File::makeDirectory(dirname($this->csvPath), 0755, true);
        }

        if (File::exists($this->csvPath)) {
            File::copy($this->csvPath, $this->backupPath);
        }

        $header = "id;name;surName;cdi;genre;phone;email;birthDate;datePromotion;asignaturePromotion;user_email;sede_nombre;area_nombre;programa_nombre\n";
        $row1 = "1;Juan;Perez;12345678;M;04141234567;juan@example.com;1980-01-01;2010-01-01;Matematicas;juan@example.com;Sede Central;Area Ciencias;Programa Info\n";

        File::put($this->csvPath, $header.$row1);
    }

    protected function tearDown(): void
    {
        // Restore the original CSV file
        if (File::exists($this->backupPath)) {
            File::move($this->backupPath, $this->csvPath);
        } else {
            File::delete($this->csvPath);
        }

        parent::tearDown();
    }

    public function test_it_anonymizes_csv_data_without_crashing()
    {
        $exitCode = Artisan::call('app:anonymize-teachers-csv');

        $this->assertEquals(0, $exitCode);

        $content = File::get($this->csvPath);

        $this->assertStringNotContainsString('Juan', $content);
        $this->assertStringNotContainsString('Perez', $content);
        $this->assertStringNotContainsString('12345678', $content);
        $this->assertStringNotContainsString('04141234567', $content);

        // Ensure structure remains
        $this->assertStringContainsString('Sede Central', $content);
        $this->assertStringContainsString('Area Ciencias', $content);
        $this->assertStringContainsString('Programa Info', $content);
        $this->assertStringContainsString('Matematicas', $content);
        $this->assertStringContainsString('1980-01-01', $content);
        $this->assertStringContainsString('2010-01-01', $content);
    }
}
