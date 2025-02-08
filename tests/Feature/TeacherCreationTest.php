<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use Spatie\Permission\Models\Role;

class TeacherCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_crear_teacher_con_usuario_existente()
    {
        // Crear un usuario y asignarle el rol de teacher
        $user = User::factory()->create();
        $user->assignRole('teacher');

        $response = $this->post('/filament/dashboard/teachers', [
            'user_id' => $user->id,
            'category_id' => Category::factory()->create()->id,
        ]);

        $this->assertDatabaseHas('teachers', ['user_id' => $user->id]);
    }
}
