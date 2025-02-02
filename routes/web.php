<?php

use App\Providers\FilamentServiceProvider;
use Illuminate\Support\Facades\Route;
use Filament\Facades\Filament;

// Ruta raíz: usuario autenticado se redirige a /dashboard, de lo contrario se muestra welcome.
Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/dashboard'); // Asegúrate de que aquí se invoque el middleware de autenticación si es necesario.
    }
    return view('welcome');
});

//Route::permanentRedirect('/login', '/dashboard/login');

// Asegúrate de NO tener un catch-all que redirija a /dashboard de forma global.
// Fallback: si ninguna ruta es capturada, mostrar error 404.
Route::fallback(function () {
    return response()->view('errors.404', [], 404); // Conserva tu vista de error ya aprobada.
});

