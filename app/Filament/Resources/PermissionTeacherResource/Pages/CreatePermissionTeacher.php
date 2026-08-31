<?php

namespace App\Filament\Resources\PermissionTeacherResource\Pages;

use App\Filament\Resources\PermissionTeacherResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreatePermissionTeacher extends CreateRecord
{
    protected static string $resource = PermissionTeacherResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Permiso creado')
            ->body('El permiso ha sido creado exitosamente.');
    }
}
