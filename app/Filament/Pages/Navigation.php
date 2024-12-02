<?php

namespace App\Filament\Pages;

use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use App\Filament\Resources\UserResource;
use App\Filament\Resources\TeacherResource;
use App\Filament\Resources\SiteResource;
use App\Filament\Resources\CategoryResource;
use App\Filament\Resources\DedicationResource;
use App\Filament\Resources\PermissionTeacherResource;
use App\Filament\Resources\ReportResource;
use Illuminate\Support\Facades\Auth;

class Navigation
{
    public static function build(): NavigationBuilder
    {
        $navigation = NavigationBuilder::make();
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            $navigation
                ->groups([
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
            $navigation
                ->groups([
                    NavigationGroup::make('Gestión Docente')
                        ->items([
                            ...TeacherResource::getNavigationItems(),
                            ...CategoryResource::getNavigationItems(),
                            ...DedicationResource::getNavigationItems(),
                        ]),
                    NavigationGroup::make('Gestión Reportes')
                        ->items([
                            ...PermissionTeacherResource::getNavigationItems(),
                            ...ReportResource::getNavigationItems(),
                        ]),
                ]);
        } elseif ($user->hasRole('teacher')) {
            $navigation
                ->groups([
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
