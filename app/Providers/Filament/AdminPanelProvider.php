<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Dashboard as PagesDashboard;
use App\Filament\Pages\Navigation;
use App\Filament\Widgets\SedeStatsChart;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\TasksOverview;
use App\Filament\Widgets\TeacherDistributionChart;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

/**
 * Proveedor principal del Panel de Administración de Filament.
 * Configura la interfaz, middleware, widgets y la navegación principal del sistema.
 */
class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->authGuard('web')
            ->authPasswordBroker('users')
            ->login(\App\Filament\Pages\Auth\Login::class)
            ->id('admin')
            ->path('admin')
            ->brandName('SIGEDOR')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Aprobar')
                    ->icon('heroicon-o-check-circle')
                    ->collapsed(false)
                    ->collapsible(),
                NavigationGroup::make()
                    ->label('Gestión Docente')
                    ->icon('heroicon-o-academic-cap')
                    ->collapsed(false)
                    ->collapsible(),
                NavigationGroup::make()
                    ->label('Asignaciones')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->collapsed(false)
                    ->collapsible(),
                NavigationGroup::make()
                    ->label('Gestión Reportes')
                    ->icon('heroicon-o-document-chart-bar')
                    ->collapsed(false)
                    ->collapsible(),
                NavigationGroup::make()
                    ->label('Configuración')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->collapsed(false)
                    ->collapsible(),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                PagesDashboard::class,
            ])
            ->maxContentWidth('full')
            ->widgets([
                StatsOverview::class,
                TasksOverview::class,
                TeacherDistributionChart::class,
                SedeStatsChart::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->navigation(fn () => Navigation::build());
    }
}
