<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Toggle;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use App\Models\Sede;
use App\Models\Area;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Información del Usuario')
                ->schema([
                    TextInput::make('name')
                        ->label('Usuario')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('email')
                        ->label('Correo')
                        ->required()
                        ->email()
                        ->unique('users', 'email')
                        ->rules([
                            'regex:/@sigedor\.com$/',
                        ])
                        ->autocomplete('email')
                        ->helperText('El correo debe terminar en @sigedor.com'),
                    TextInput::make('password')
                        ->label('Contraseña')
                        ->password()
                        ->minLength(8)
                        ->dehydrateStateUsing(fn ($state) => ! empty($state) ? bcrypt($state) : null) // Only update password if provided
                        ->nullable(), // Password is not required on edit
                ]),
                Section::make('Asignación de Sede')->schema([
                    Select::make('sede_id')
                        ->label('Sede')
                        ->relationship('sede', 'nombre')
                        ->required()
                        ->native(false)
                        ->searchable()
                        ->preload()
                        ->columnSpanFull(),
                ]),

                Section::make('Asignación de Área')->schema([
                    Select::make('area_id')
                        ->label('Área')
                        ->relationship('area', 'nombre')
                        ->required()
                        ->native(false)
                        ->searchable()
                        ->preload()
                        ->columnSpanFull(),
                ]),

                Section::make('Asignación de Rol')
                ->schema([
                    Select::make('roles')
                        ->label('Rol')
                        ->relationship('roles', 'name')
                        ->options(Role::all()->mapWithKeys(function ($role) {
                            return [
                                $role->id => match ($role->name) {
                                    'admin' => 'Administrador',
                                    'area_manager' => 'Jefe de Área',
                                    'teacher' => 'Docente',
                                    default => $role->name
                                }
                            ];
                        }))
                        ->required()
                        ->native(false)
                        ->searchable()
                        ->preload()
                        ->columnSpanFull(),
                ]),

            Section::make('Estado del Usuario')
                ->schema([
                    Toggle::make('is_active')
                        ->label('Activo')
                        ->default(true),
                    Toggle::make('is_approved')
                        ->label('Aprobado')
                        ->default(false),
                ]),
            ]);
    }
}
