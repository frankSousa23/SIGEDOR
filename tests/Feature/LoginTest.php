<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Verifica que la página de login se carga correctamente.
     */
    public function test_login_page_loads()
    {
        $response = $this->get('/admin/login');
        $response->assertStatus(200);
        // Verifica que se muestre un contenido característico de la vista de login.
        $response->assertSee('Entre a su cuenta');
    }

    /**
     * Test de autenticación con credenciales correctas vía HTTP.
     */
    public function test_user_can_login_with_valid_credentials()
    {
        $this->markTestSkipped('El login en Filament se gestiona vía Livewire; verificarlo con LivewireLoginTest.');
    }

    public function test_user_cannot_login_without_csrf_token()
    {
        $this->markTestSkipped('El flujo de autenticación de Filament es Livewire y no utiliza POST tradicional.');
    }
}
