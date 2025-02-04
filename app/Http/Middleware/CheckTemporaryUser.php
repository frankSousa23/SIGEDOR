<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckTemporaryUser
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->is_temporary) {
            if (auth()->user()->hasRole('admin')) {
                auth()->logout();
                return redirect('/login')->with('error', 'Acceso temporal no permitido para admins');
            }
            // Agregar mensaje de advertencia
            session()->flash('warning', 'Usuario temporal activo - Crear usuario permanente');

            // Verificar tiempo de sesión
            if (session()->has('temporary_user_login_time')) {
                $loginTime = session('temporary_user_login_time');
                if (now()->diffInHours($loginTime) > 24) {
                    auth()->logout();
                    return redirect('/')->with('error', 'Sesión temporal expirada');
                }
            } else {
                session(['temporary_user_login_time' => now()]);
            }
        }

        return $next($request);
    }
}
