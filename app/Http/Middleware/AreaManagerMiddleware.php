<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AreaManagerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->user() && $request->user()->hasRole('area_manager')) {
            $siteId = $request->user()->teacher->site_id;
            $request->merge(['site_id' => $siteId]);
        }

        return $next($request);
    }
}
