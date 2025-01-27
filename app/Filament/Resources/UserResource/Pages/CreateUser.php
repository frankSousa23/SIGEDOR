<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Site;
use App\Enums\Role;
use Filament\Forms\Components\Select;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (isset($data['site_id']) && !Site::where('id', $data['site_id'])->exists()) {
            throw new \Exception('El site seleccionado no existe');
        }
        $data['role'] = $data['role'];
        return $data;
    }

    protected function getFormComponents(): array
    {
        $roles = [
            Role::ADMIN => 'Administrador',
            Role::AREA_MANAGER => 'Gerente de Área',
            Role::TEACHER => 'Docente',
        ];

        return [
            Select::make('role')
                ->label('Rol')
                ->options($roles)
                ->required(),
            Select::make('site_id')
                ->label('Site')
                ->options(Site::pluck('name', 'id'))
                ->required(),
            Select::make('area')
                ->label('Área')
                ->options(Site::pluck('area', 'id'))
                ->required(),
        ];
    }

    protected function afterCreate(): void
    {
        $user = $this->record;
        $user->syncRoles([$user->role]);
    }
}
