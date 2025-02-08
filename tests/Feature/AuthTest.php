<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\SiteOption;
use App\Models\Role;
use Database\Seeders\RoleSeeder;
use Illuminate\Support\Facades\Hash;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /*public function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh --seed');
    }*/

    public function test_admin_route_returns_success_when_authenticated()
    {
        //$this->seed(RoleSeeder::class);

        $siteOption = SiteOption::factory()->create();
        $role = Role::where('name', 'admin')->first();

        $admin = User::factory()->create([
            'site_option_id' => $siteOption->id,
            'role_id' => $role->id,
        ]);
        $admin->assignRole('admin');

        $this->actingAs($admin);
        $response = $this->get('/filament/dashboard/users'); // Usar ruta directa
        $response->assertStatus(200);
    }

    public function test_login_with_free_email()
    {
        // Crear un usuario con un correo libre
        $user = User::create([
            'name' => 'Free User',
            'email' => 'freeuser@gmail.com', // Email sin restricción de dominio
            'password' => Hash::make('password_seguro'),
        ]);

        // Enviar las credenciales al endpoint de login (se asume que la ruta es "/login")
        $response = $this->post('/login', [
            'email' => 'freeuser@gmail.com',
            'password' => 'password_seguro',
        ]);

        // Se espera una redirección al dashboard (ajustar esta ruta si es necesario)
        $response->assertRedirect('/dashboard');

        // Verifica que el usuario quedó autenticado
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_failure_with_wrong_credentials()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@gmail.com',
            'password' => Hash::make('password_seguro')
        ]);

        // Intentar iniciar sesión con una contraseña incorrecta
        $response = $this->post('/login', [
            'email' => 'test@gmail.com',
            'password' => 'wrong_password',
        ]);

        // Se espera que se generen errores en la sesión
        $response->assertSessionHasErrors();

        // Asegura que el usuario no quedó autenticado
        $this->assertGuest();
    }
}
