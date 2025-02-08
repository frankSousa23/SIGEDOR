<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Site;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_crear_usuario_con_site_valido()
    {
        $site = Site::factory()->create();

        $response = $this->post('/filament/dashboard/users', [
            'name' => 'Test User',
            'email' => 'test@sigedor.com',
            'password' => 'password',
            'site_id' => $site->id,
            'role' => 'teacher'
        ]);

        $this->assertDatabaseHas('users', ['email' => 'test@sigedor.com']);

        // Verificar que el rol se haya asignado correctamente
        $user = User::where('email', 'test@sigedor.com')->first();
        $this->assertTrue($user->hasRole('teacher'));
    }
}
