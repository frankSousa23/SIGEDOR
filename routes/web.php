<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Filament\Http\Livewire\Auth\Login as FilamentLogin;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

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
        return redirect('/admin');
    })->name('dashboard');
});

// Ruta de logout
Route::post('/logout', function () {
    Auth::logout();
    Session::flush();
    Session::regenerateToken();
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

// Redirección para /login
Route::get('/login', function () {
    return redirect('/admin/login');
})->name('login');

// Redirigir /dashboard/login a /admin/login
Route::get('/dashboard/login', function () {
    return redirect('/admin/login');
});

Route::get('/admin-only', function () {
    return 'This page is only for admins!';
})->middleware('role:admin');

Route::middleware('web')->group(function () {
    // Rutas del panel dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Rutas para el panel admin de Filament (Filament asume "web" por defecto)
    // Además, si rediriges /login hacia /admin/login:
    Route::get('/login', function () {
         return redirect('/admin/login');
    })->name('login');
});

require __DIR__.'/auth.php';
