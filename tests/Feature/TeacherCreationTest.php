<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;

class TeacherCreationTest extends TestCase
{
    public function test_crear_teacher_con_usuario_existente()
    {
        $user = User::factory()->create(['role' => 'teacher']);

        $response = $this->post('/filament/dashboard/teachers', [
            'user_id' => $user->id,
            'category_id' => Category::factory()->create()->id,
        ]);

        $this->assertDatabaseHas('teachers', ['user_id' => $user->id]);
    }
}
