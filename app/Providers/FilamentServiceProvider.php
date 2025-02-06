<?php

namespace App\Providers;

use App\Filament\Pages\Auth\Login;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Facades\Filament;
use Filament\Navigation\NavigationGroup;

class FilamentServiceProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('dashboard')
            ->path('dashboard')
            ->login(Login::class)
            ->colors([
                'primary' => Color::hex('#004B93'),
            ])
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([AccountWidget::class])
            ->middleware(['web'])
            ->authMiddleware(['auth']);
    }

    public function boot(): void
    {
        Filament::serving(function () {
            Filament::registerNavigationGroups([
                NavigationGroup::make('Administración'),
                NavigationGroup::make('Sistema')
            ]);

            // Widgets específicos por rol
            if (auth()->check()) {
                if (auth()->user()->hasRole('Admin')) {
                    Filament::registerWidgets([
                        \App\Filament\Widgets\StatsOverview::class,
                        // \App\Filament\Widgets\LatestUsers::class, // Comentado como solicitado
                    ]);
                } elseif (auth()->user()->hasRole('Area Manager')) {
                    Filament::registerWidgets([
                        \App\Filament\Widgets\AreaStats::class,
                    ]);
                } elseif (auth()->user()->hasRole('Teacher')) {
                    Filament::registerWidgets([
                        \App\Filament\Widgets\TeacherDashboard::class,
                    ]);
                }
            }
        });
    }
}
