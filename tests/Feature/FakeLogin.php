<?php

namespace Tests\Feature;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class FakeLogin extends Component
{
    // Simula los datos del formulario
    public $data = [
        'email'    => '',
        'password' => '',
    ];

    /**
     * Método simulado de autenticación.
     * Aquí se puede validar la autenticación de forma básica.
     */
    public function authenticate()
    {
        // Se recogen las credenciales del arreglo $data
        $credentials = $this->data;

        // Se intenta la autenticación sin la lógica de Filament
        if (Auth::attempt($credentials)) {
            session()->regenerate();
        }
    }

    public function render()
    {
        return <<<'blade'
<div>Fake Login Component for Testing</div>
blade;
    }
}
