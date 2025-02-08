<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Filament\Facades\Filament;
use App\Providers\RouteServiceProvider;
use Filament\Http\Livewire\Auth\Login;
use Tests\Feature\FakeLogin;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseTransactions;

    // public function setUp(): void
    // {
    //     parent::setUp();
    //     // Ejecutar los seeders antes de cada test
    //     $this->seed();
    // }

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();

        // Solución definitiva para inyección de dependencias
        $this->app->bind(
            \Filament\Http\Livewire\Auth\Login::class,
            function () {
                return new class extends \Livewire\Component {
                    public $email = '';
                    public $password = '';
                    public $remember = false;

                    public function authenticate()
                    {
                        if (! auth()->attempt([
                            'email' => $this->email,
                            'password' => $this->password,
                        ], $this->remember)) {
                            throw ValidationException::withMessages([
                                'email' => __('filament::login.messages.failed'),
                            ]);
                        }

                        return redirect()->intended(Filament::getCurrentPanel()->getUrl());
                    }

                    public function render()
                    {
                        return view('filament::login');
                    }
                };
            }
        );

        // Inyectar el stub de FakeLogin en el contenedor
        $this->app->bind(Login::class, FakeLogin::class);
    }

    /**
     * Authenticate a user for testing purposes.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  string|null  $driver
     * @return static
     */
    public function actingAs(Authenticatable $user, string $driver = null): static
    {
        Auth::login($user, $driver);

        return $this;
    }
}
