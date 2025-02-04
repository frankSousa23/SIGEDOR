<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Página de inicio a la que se redirige a los usuarios autenticados.
     */
    public const HOME = '/dashboard';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });

            Route::post('logout', function () {
                auth()->logout();
                return redirect('/');
        })->name('logout');
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting(): void
    {
        // Limiter para las rutas API (default)
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)
                ->by(optional($request->user())->getAuthIdentifier() ?: $request->ip());
        });

        // Registrar rate limiter con la clave simple "global"
        RateLimiter::for('global', function (Request $request) {
            return Limit::perMinute(60)
                ->by(optional($request->user())->getAuthIdentifier() ?: $request->ip());
        });

        // Registrar rate limiter para la clave compuesta "App\Models\User::global"
        $keyComplet = \App\Models\User::class . '::global';
        RateLimiter::for($keyComplet, function (Request $request) {
            return Limit::perMinute(60)
                ->by(optional($request->user())->getAuthIdentifier() ?: $request->ip());
        });
    }

    public static function redirectTo(): string
    {
        return match(auth()->user()->getRoleNames()->first()) {
            'Admin' => '/admin',
            'Area Manager' => '/admin/teachers',
            default => '/teacher/profile'
        };
    }
}
