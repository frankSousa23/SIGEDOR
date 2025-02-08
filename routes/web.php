<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

// Ruta principal
Route::get('/', function () {
    return view('welcome');
});

// Grupo de rutas autenticadas
Route::middleware(['auth'])->group(function () {
    // Ruta del dashboard (usando Filament)
    Route::get('/admin', function () {
        return redirect('/dashboard');
    })->name('dashboard');

    // Ruta del dashboard (usando Filament)
    Route::get('/dashboard', function () {
        // Asegúrese de que 'filament.dashboard' es la ruta o vista configurada en su panel de Filament
        return redirect()->route('filament.dashboard');
    });
});

// Ruta de logout
Route::post('/logout', function () {
    Auth::logout();
    Session::flush();
    Session::regenerate();
    return redirect('/');
})->name('logout');

// Fallback para 404
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

Route::get('/session-check', function() {
    return [
        'session_id' => session()->getId(),
        'user' => auth()->user()?->only('id','name'),
        'session_data' => session()->all()
    ];
});

require __DIR__.'/auth.php';
