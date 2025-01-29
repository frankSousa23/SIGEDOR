<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Site;
use App\Enums\Role;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use App\Models\AreaOption;

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
            Select::make('area_id')
                ->label('Área')
                ->options(AreaOption::all()->pluck('name', 'id'))
                ->required(),
        ];
    }

    protected function afterCreate(): void
    {
        $user = $this->record;
        $user->syncRoles([$user->role]);
    }

    protected function handleRecordCreation(array $data): Model
    {
        DB::beginTransaction();

        try {
            $user = User::create([
                'email' => $data['email'],
                'name' => $data['name'],
                'password' => Hash::make($data['password']),
                'role' => $data['role'],
                'is_active' => $data['is_active'],
                'is_approved' => $data['is_approved'],
                'site_option_id' => $data['site_option_id'],
                'area_option_id' => $data['area_option_id'],
            ]);

            Site::create([
                'site_option_id' => $data['site_option_id'],
                'area_option_id' => $data['area_option_id'],
                'user_id' => $user->id,
            ]);

            DB::commit();
            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
