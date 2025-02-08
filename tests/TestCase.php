<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Filament\Facades\Filament;

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
    }
}
