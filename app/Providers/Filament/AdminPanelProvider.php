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
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Enums\RecordActions;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Pages;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use App\Filament\Pages\Dashboard;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->registration()
            ->passwordReset()
            ->emailVerification()
            ->colors([
                'primary' => Color::Rose,
                'secondary' => Color::Gray,
                'tertiary' => Color::Emerald,
            ])
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Gestión Docente')
                    ->icon('heroicon-o-academic-cap'),
                NavigationGroup::make()
                    ->label('Asignaciones')
                    ->icon('heroicon-o-clipboard-document-list'),
                NavigationGroup::make()
                    ->label('Gestión de Reportes')
                    ->icon('heroicon-o-document-chart-bar'),
                NavigationGroup::make()
                    ->label('Configuración')
                    ->icon('heroicon-o-cog-6-tooth')
            ])
            ->pages([
                PagesDashboard::class,
            ])
            ->maxContentWidth('full')
            ->widgets([
                //Widgets\AccountWidget::class,
                //Widgets\FilamentInfoWidget::class,
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
                HandlePrecognitiveRequests::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->authGuard('web')
            ->navigation(fn () => Navigation::build())
            ->databaseNotifications()
            ->databaseNotificationsPolling('30s')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->databaseTransactions();

        return $panel;
    }
}
