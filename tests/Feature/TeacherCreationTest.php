<?php

namespace Tests\Feature;

use App\Models\Dedication;
use App\Models\PermissionTeacher;
use Tests\TestCase;

/**
 * Pruebas de validación de Dedicación y Permisos Docentes.
 *
 * Verifica el cálculo de horas válidas por tipo de dedicación y el cálculo
 * automático de fechas de fin de permisos/licencias según la duración seleccionada.
 *
 * @note Este archivo se llama TeacherCreationTest.php por razones históricas
 *       pero la clase fue renombrada a PermissionAndDedicationTest para reflejar
 *       su contenido actual. Se recomienda renombrar el archivo si es posible.
 */
class PermissionAndDedicationTest extends TestCase
{
    public function test_validacion_de_horas_segun_dedicacion()
    {
        $validHoursTCV = Dedication::getValidHours('Tiempo Convencional');
        $this->assertArrayHasKey(12, $validHoursTCV);

        $validHoursMT = Dedication::getValidHours('Medio Tiempo');
        $this->assertEquals([18 => 18], $validHoursMT);

        $validHoursTC = Dedication::getValidHours('Tiempo Completo');
        $this->assertEquals([30 => 30], $validHoursTC);
    }

    public function test_calculo_de_fecha_fin_en_permisos()
    {
        $permission = new PermissionTeacher([
            'start_date' => '2026-01-01',
            'duration_type' => 'semestral',
        ]);

        $this->assertEquals('2026-07-01', $permission->calculateEndDate());
    }
}
