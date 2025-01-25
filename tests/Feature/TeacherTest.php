<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Site;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TeacherTest extends TestCase
{
    public function test_docente_solo_ve_su_informacion()
    {
        // Crear jerarquía completa
        $site = Site::factory()->create();
        $user = User::factory()->create();
        $user->assignRole('teacher');
        $user = $user->fresh();

        $teacher = Teacher::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);
        $response = $this->get('/filament/dashboard/profile');
        $response->assertSeeText($user->name);
    }
}
