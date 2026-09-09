<?php

namespace Tests\Unit;

use App\Models\Dedication;
use PHPUnit\Framework\TestCase;

class DedicationUnitTest extends TestCase
{
    public function test_valid_hours_returns_correct_hours_for_tiempo_completo(): void
    {
        $this->assertEquals([30 => 30], Dedication::getValidHours('Tiempo Completo'));
    }

    public function test_valid_hours_returns_correct_hours_for_medio_tiempo(): void
    {
        $this->assertEquals([18 => 18], Dedication::getValidHours('Medio Tiempo'));
    }

    public function test_valid_hours_returns_correct_hours_for_tiempo_convencional(): void
    {
        $hours = Dedication::getValidHours('Tiempo Convencional');
        $this->assertArrayHasKey(12, $hours);
        $this->assertArrayHasKey(8, $hours);
        $this->assertArrayHasKey(4, $hours);
    }

    public function test_valid_hours_returns_empty_array_for_unknown_dedication(): void
    {
        $this->assertEmpty(Dedication::getValidHours('Inexistente'));
    }
}
