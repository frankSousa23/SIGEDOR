<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Hash;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
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

        Hash::extend('force_bcrypt', function () {
            return new \Illuminate\Hashing\BcryptHasher([
                'rounds' => 12,
            ]);
        });

        config(['hashing.driver' => 'force_bcrypt']);
    }
}
