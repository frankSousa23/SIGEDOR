<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Hash;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\EndsWith;
use App\Models\Site;
use App\Models\Sede;
use App\Models\Area;
use App\Filament\Resources\SiteResource;
use Spatie\Permission\Models\Role;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\ToggleColumn;


class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Configuración';
    protected static ?string $navigationLabel = 'Usuarios';
    protected static ?string $modelLabel = 'Usuario';
    protected static ?string $pluralModelLabel = 'Usuarios';
    protected static ?int $navigationSort = 40;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Información del Usuario')
                ->schema([
                    TextInput::make('name')
                        ->label('Nombre')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('email')
                        ->label('Correo Electrónico')
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
                        ->required()
                        ->minLength(8),
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Correo Electrónico')
                    ->searchable(),
                TextColumn::make('sede.nombre')
                    ->label('Sede')
                    ->searchable(),
                TextColumn::make('area.nombre')
                    ->label('Área')
                    ->searchable(),
                TextColumn::make('roles.name')
                    ->label('Rol')
                    ->badge(),
                ToggleColumn::make('is_active')
                    ->label('Activo'),
                ToggleColumn::make('is_approved')
                    ->label('Aprobado'),
                TextColumn::make('activities.description'),
            ])
            ->filters([
                SelectFilter::make('roles')
                    ->relationship('roles', 'name')
                    ->options([
                        'area_manager' => 'Jefe de Área',
                        'teacher' => 'Profesor'
                    ])
                    ->label('Rol'),

                SelectFilter::make('sede_id')
                    ->relationship('sede', 'nombre')
                    ->label('Sede')
                    ->searchable()
                    ->preload()
                    ->native(false),

                Tables\Filters\TernaryFilter::make('is_approved')
                    ->label('Aprobado')
                    ->placeholder('Todos')
                    ->trueLabel('Aprobados')
                    ->falseLabel('Pendientes'),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Estado')
                    ->placeholder('Todos')
                    ->trueLabel('Activos')
                    ->falseLabel('Inactivos'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn (User $record) =>
                        auth()->user()->hasRole('admin') ||
                        auth()->id() === $record->id
                    ),

                Tables\Actions\DeleteAction::make()
                    ->visible(fn (User $record) =>
                        auth()->user()->hasRole('admin') &&
                        !$record->hasRole('admin') &&
                        auth()->id() !== $record->id
                    ),

                Action::make('approve')
                    ->label('Aprobar')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn (User $record) =>
                        auth()->user()->hasRole('admin') &&
                        !$record->is_approved &&
                        !$record->hasRole('admin') &&
                        auth()->id() !== $record->id
                    )
                    ->action(fn (User $record) => $record->update(['is_approved' => true])),

                Action::make('deactivate')
                    ->label('Desactivar')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (User $record) =>
                        auth()->user()->hasRole('admin') &&
                        $record->is_active &&
                        !$record->hasRole('admin') &&
                        auth()->id() !== $record->id
                    )
                    ->action(fn (User $record) => $record->update(['is_active' => false])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => auth()->user()->hasRole('admin')),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->user()->hasRole('admin')) {
            return $query;
        }

        if (auth()->user()->hasRole('area_manager')) {
            return $query->whereHas('roles', fn($q) => $q->where('name', 'teacher'))
                        ->where('sede_id', auth()->user()->sede_id)
                        ->whereHas('areas', fn($q) => $q->whereIn('id', auth()->user()->areas->pluck('id')));
        }

        return $query->where('id', auth()->id());
    }
}
