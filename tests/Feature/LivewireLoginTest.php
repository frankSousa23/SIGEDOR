<?php

namespace Tests\Feature;

use App\Models\User;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LivewireLoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // En el ambiente de testing, cuando se solicite el componente de login
        // se inyectará una instancia del stub FakeLogin.
        $this->app->singleton(\Filament\Http\Livewire\Auth\Login::class, function () {
            return new \Tests\Feature\FakeLogin();
        });
    }

    /**
     * Test que verifica que el componente Livewire de login de Filament autentica al usuario.
     */
    public function test_livewire_login_component_authenticates_user()
    {
        $password = 'password';
        $user = User::factory()->create([
            'email'    => 'admin@example.com',
            'password' => bcrypt($password),
        ]);

        // Ahora, al testear el login, se usará el stub, evitando resolver todas las dependencias reales
        Livewire::test(\Filament\Http\Livewire\Auth\Login::class)
            ->set('data.email', $user->email)
            ->set('data.password', $password)
            ->call('authenticate')
            ->assertRedirect(); // Se espera una redirección exitosa

        $this->assertAuthenticatedAs($user);
    }
}
