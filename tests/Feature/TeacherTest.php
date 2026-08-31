<?php

namespace Tests\Feature;

use App\Models\Area;
use App\Models\Programa;
use App\Models\Sede;
use App\Models\Teacher;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeacherTest extends TestCase
{
    use RefreshDatabase;

    public function test_creacion_y_relaciones_de_docente()
    {
        $this->seed(RoleSeeder::class);
        $sede = Sede::create(['nombre' => 'Sede Central']);
        $area = Area::create(['nombre' => 'Ingeniería de Sistemas']);
        $programa = Programa::create(['nombre' => 'Ingeniería en Informática']);

        $user = User::create([
            'name' => 'Prof. Carlos Mendoza',
            'email' => 'carlos.mendoza@test.com',
            'password' => 'password',
            'sede_id' => $sede->id,
            'area_id' => $area->id,
            'is_active' => true,
            'is_approved' => true,
        ]);
        $user->assignRole('teacher');

        $teacher = Teacher::create([
            'name' => 'Carlos',
            'surName' => 'Mendoza',
            'cdi' => '10000001',
            'genre' => 'M',
            'phone' => '+58 412 1000001',
            'email' => $user->email,
            'birthDate' => '1985-05-15',
            'datePromotion' => '2015-06-20',
            'asignaturePromotion' => 'Ingeniería de Software',
            'user_id' => $user->id,
            'sede_id' => $sede->id,
            'area_id' => $area->id,
            'programa_id' => $programa->id,
        ]);

        $this->assertEquals('Carlos Mendoza', $teacher->full_name);
        $this->assertEquals($user->id, $teacher->user->id);
        $this->assertEquals($sede->id, $teacher->sede->id);
        $this->assertEquals($area->id, $teacher->area->id);
        $this->assertEquals($programa->id, $teacher->programa->id);
    }
}
