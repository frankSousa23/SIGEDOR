<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // El constructor de BcryptHasher espera un arreglo como primer parámetro,
        // no un entero. Por ello, hay que pasar el valor de las rondas como un arreglo.
        $this->app->singleton('hash', function ($app) {
            return new \Illuminate\Hashing\BcryptHasher([
                'rounds' => (int) $app['config']['hashing.bcrypt.rounds'], // Convertimos a entero para mayor seguridad
            ]);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Registrar componentes Blade personalizados
        Blade::componentNamespace('App\\View\\Components', 'app');

        // Configuración adicional de Filament
        Filament::serving(function () {
            Filament::registerNavigationGroups([
                'Gestión Docente',
                'Asignaciones',
                'Gestión de Reportes',
                'Configuración',
            ]);
        });

        Role::preventLazyLoading(!app()->isProduction());
        User::preventAccessingMissingAttributes();

        // Desactivar temporalmente autenticación automática
        if (app()->environment('local') && !app()->runningInConsole()) {
            try {
                if(User::exists()) {
                    Auth::login(User::with('roles')->first());
                }
            } catch (\Throwable $th) {
                // Ignorar errores durante migraciones
            }
        }
    }
}
