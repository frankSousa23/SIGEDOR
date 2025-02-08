<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Http\FormRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\View\View;
use Filament\Http\Livewire\Auth\Login as FilamentLogin;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create()
    {
        return redirect('/admin/login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended(Route::has('admin.home') ? route('admin.home') : '/admin');
        }

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Limpiar todas las cookies relacionadas
        Cookie::queue(Cookie::forget('sigedor_session'));
        Cookie::queue(Cookie::forget('XSRF-TOKEN'));

        return redirect('/')->withHeaders([
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }
}
