<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Filament\Http\Livewire\Auth\Login as FilamentLogin;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Ruta principal
Route::get('/', function () {
    return view('welcome');
});

// Grupo de rutas autenticadas
Route::middleware(['auth'])->group(function () {
    // Ruta del dashboard (usando Filament)
    Route::get('/main', function () {
        return view('filament.main.pages.dashboard');
    });
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
    return redirect('/main/login');
})->name('login');

// Eliminar las rutas de los paneles anteriores
// Route::get('/dashboard', function () {
//     return view('filament.dashboard.pages.dashboard');
// });

// Route::get('/admin', function () {
//     return view('filament.admin.pages.dashboard');
// });

require __DIR__.'/auth.php';
