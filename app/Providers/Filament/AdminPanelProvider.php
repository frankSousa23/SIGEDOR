<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Navigation\NavigationGroup;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Filament\Pages\Dashboard as PagesDashboard;
use App\Filament\Pages\Navigation;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use App\Filament\Widgets;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\TasksOverview;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName('SIGEDOR')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Gestión Docente')
                    ->icon('heroicon-o-academic-cap')
                    ->collapsed()
                    ->collapsible()
                    ->sort(-1),
                NavigationGroup::make()
                    ->label('Asignaciones')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->collapsed()
                    ->collapsible()
                    ->sort(0),
                NavigationGroup::make()
                    ->label('Gestión de Reportes')
                    ->icon('heroicon-o-document-chart-bar')
                    ->collapsed()
                    ->collapsible()
                    ->sort(1),
                NavigationGroup::make()
                    ->label('Configuración')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->collapsed()
                    ->collapsible()
                    ->sort(2),
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
                RoleMiddleware::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->navigation(fn () => Navigation::build());
    }
}
