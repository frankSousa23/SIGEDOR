<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User; // Asegúrate que el namespace es correcto
use Illuminate\Support\Facades\Hash;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;
use function Pest\Laravel\get;

class SystemCycleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Comprueba el ciclo completo: login, acceso al dashboard y logout.
     */
    public function test_ciclo_completo_del_sistema()
    {
        // Crear un usuario de prueba con las credenciales definidas
        $user = User::factory()->create([
            'email'    => 'admin@sigedor.com',
            'password' => Hash::make('password'), // Aseguramos que la contraseña esté hasheada
        ]);

        // Simular el inicio de sesión enviando una petición POST a /login
        $loginResponse = $this->post('/login', [
            'email'    => 'admin@sigedor.com',
            'password' => 'password',
            '_token'   => csrf_token(), // En testing se podría omitir o usar withoutMiddleware en algunos casos
        ]);

        // Verificamos la redirección al panel correcto (ajusta la ruta según tu configuración)
        $loginResponse->assertRedirect('/main');

        // Forzar la sesión autenticada y verificar el acceso a la página del dashboard
        $this->actingAs($user)
             ->get('/main')
             ->assertStatus(200)
             ->assertSee('dashboard'); // Cambia 'dashboard' por un contenido representativo de tu panel

        // Simular el cierre de sesión mediante la ruta POST /logout
        $logoutResponse = $this->post('/logout', [
            '_token' => csrf_token(),
        ]);

        $logoutResponse->assertRedirect('/'); // Se redirige a la raíz u otra página pública tras el logout

        // Comprobar que, tras cerrar sesión, se impide el acceso a rutas protegidas (redirige a /login)
        $this->get('/main')
             ->assertRedirect('/login');
    }
}
