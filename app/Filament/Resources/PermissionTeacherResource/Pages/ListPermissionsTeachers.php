<?php

namespace App\Filament\Resources\PermissionTeacherResource\Pages;

use App\Filament\Resources\PermissionTeacherResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPermissionsTeachers extends ListRecords
{
    protected static string $resource = PermissionTeacherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
