<?php

namespace Tests\Feature;

use App\Models\Area;
use App\Models\Category;
use App\Models\Sede;
use App\Models\Teacher;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_calculo_automatico_de_categoria_actual()
    {
        $category = new Category([
            'teacher_cdi' => '10000001',
            'instructor' => '2010-01-01',
            'asistente' => '2013-01-01',
            'agregado' => '2016-01-01',
            'asociado' => '2019-01-01',
            'titular' => '2022-01-01',
        ]);

        $this->assertEquals('Titular', $category->current_category);
    }
}
