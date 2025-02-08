<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SimpleAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_acting_as_authenticates()
    {
        $password = 'password';
        $user = User::factory()->create([
            'password' => bcrypt($password),
        ]);

        // Autentica el usuario sin pasar por el componente de login
        $this->actingAs($user)
             ->get('/dashboard')
             ->assertStatus(200);

        $this->assertAuthenticatedAs($user);
    }
}
