<?php

namespace App\Filament\Pages;

use App\Filament\Resources\CategoryResource;
use App\Filament\Resources\DedicationResource;
use App\Filament\Resources\PermissionTeacherResource;
use App\Filament\Resources\ReportResource;
use App\Filament\Resources\SiteResource;
use App\Filament\Resources\TeacherResource;
use App\Filament\Resources\UserResource;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Illuminate\Support\Facades\Auth;

/**
 * Constructor de Navegación Dinámica por Roles en Filament.
 *
 * Configura la barra lateral según los permisos del usuario autenticado
 * (Administrador, Jefe de Área o Docente).
 */
class Navigation
{
    public static function build(): NavigationBuilder
    {
        $navigation = new NavigationBuilder;
        $user = Auth::user();

        if (! $user) {
            return $navigation;
        }

        if ($user->hasRole('admin')) {
            $navigation->groups([
                NavigationGroup::make('Aprobar')
                    ->items([
                        ...UserResource::getNavigationItems(),
                    ]),
                NavigationGroup::make('Gestión Docente')
                    ->items([
                        ...TeacherResource::getNavigationItems(),
                        ...SiteResource::getNavigationItems(),
                        ...CategoryResource::getNavigationItems(),
                        ...DedicationResource::getNavigationItems(),
                    ]),
                NavigationGroup::make('Gestión Reportes')
                    ->items([
                        ...PermissionTeacherResource::getNavigationItems(),
                        ...ReportResource::getNavigationItems(),
                    ]),
            ]);
        } elseif ($user->hasRole('area_manager')) {
            $navigation->groups([
                NavigationGroup::make('Gestión Docente')
                    ->items([
                        ...TeacherResource::getNavigationItems(),
                        ...SiteResource::getNavigationItems(),
                        ...CategoryResource::getNavigationItems(),
                        ...DedicationResource::getNavigationItems(),
                    ]),
                NavigationGroup::make('Gestión Reportes')
                    ->items([
                        ...PermissionTeacherResource::getNavigationItems(),
                        ...ReportResource::getNavigationItems(),
                    ]),
            ]);
        } else {
            $navigation->groups([
                NavigationGroup::make('Gestión Reportes')
                    ->items([
                        ...PermissionTeacherResource::getNavigationItems(),
                        ...ReportResource::getNavigationItems(),
                    ]),
            ]);
        }

        return $navigation;
    }
}
