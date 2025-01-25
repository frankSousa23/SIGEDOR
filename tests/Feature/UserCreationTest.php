<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Site;

class UserCreationTest extends TestCase
{
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
    }
}
