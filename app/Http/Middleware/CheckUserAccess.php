<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user()->hasRole('admin')) {
            return $next($request);
        }

        abort(403, 'Acceso denegado');
    }
}
