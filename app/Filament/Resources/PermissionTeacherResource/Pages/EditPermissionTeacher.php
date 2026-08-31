<?php

namespace App\Filament\Resources\PermissionTeacherResource\Pages;

use App\Filament\Resources\PermissionTeacherResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditPermissionTeacher extends EditRecord
{
    protected static string $resource = PermissionTeacherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->before(function () {
                    Notification::make()
                        ->warning()
                        ->title('Eliminando permiso')
                        ->body('El permiso será eliminado permanentemente.')
                        ->send();
                }),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Permiso actualizado')
            ->body('El permiso ha sido actualizado exitosamente.');
    }
}
