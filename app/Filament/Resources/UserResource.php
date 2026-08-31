<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

/**
 * Recurso Filament para Gestión de Usuarios del Sistema.
 */
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
                            ->label('Nombre Completo')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('email')
                            ->label('Correo Electrónico')
                            ->required()
                            ->email()
                            ->unique(ignoreRecord: true)
                            ->autocomplete('email'),

                        TextInput::make('password')
                            ->label('Contraseña')
                            ->password()
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->minLength(8)
                            ->helperText('Dejar en blanco para mantener la contraseña actual en edición.'),
                    ]),

                Section::make('Asignación Institucional')
                    ->schema([
                        Select::make('sede_id')
                            ->label('Sede')
                            ->relationship('sede', 'nombre')
                            ->required()
                            ->searchable()
                            ->preload(),

                        Select::make('area_id')
                            ->label('Área Académica')
                            ->relationship('area', 'nombre')
                            ->required()
                            ->searchable()
                            ->preload(),
                    ])->columns(2),

                Section::make('Rol y Permisos')
                    ->schema([
                        Select::make('roles')
                            ->label('Roles del Sistema')
                            ->relationship('roles', 'name')
                            ->options(Role::all()->mapWithKeys(fn ($role) => [
                                $role->id => match ($role->name) {
                                    'admin' => 'Administrador',
                                    'area_manager' => 'Jefe de Área',
                                    'teacher' => 'Docente',
                                    default => $role->name,
                                },
                            ]))
                            ->multiple()
                            ->required()
                            ->searchable()
                            ->preload(),
                    ]),

                Section::make('Estado de la Cuenta')
                    ->schema([
                        Toggle::make('is_active')
                            ->label('Activo')
                            ->default(true),

                        Toggle::make('is_approved')
                            ->label('Aprobado')
                            ->default(false),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Usuario')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label('Correo')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('sede.nombre')
                    ->label('Sede')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('area.nombre')
                    ->label('Área')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('roles.name')
                    ->label('Rol')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'admin' => 'Administrador',
                        'area_manager' => 'Jefe de Área',
                        'teacher' => 'Docente',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'danger',
                        'area_manager' => 'warning',
                        'teacher' => 'info',
                        default => 'gray',
                    }),

                ToggleColumn::make('is_active')
                    ->label('Activo')
                    ->sortable(),

                ToggleColumn::make('is_approved')
                    ->label('Aprobado')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('roles')
                    ->relationship('roles', 'name')
                    ->options([
                        'admin' => 'Administrador',
                        'area_manager' => 'Jefe de Área',
                        'teacher' => 'Docente',
                    ])
                    ->label('Rol'),

                SelectFilter::make('sede_id')
                    ->relationship('sede', 'nombre')
                    ->label('Sede')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('area_id')
                    ->relationship('area', 'nombre')
                    ->label('Área')
                    ->searchable()
                    ->preload(),

                TernaryFilter::make('is_approved')
                    ->label('Aprobación')
                    ->placeholder('Todos')
                    ->trueLabel('Aprobados')
                    ->falseLabel('Pendientes'),

                TernaryFilter::make('is_active')
                    ->label('Estado')
                    ->placeholder('Todos')
                    ->trueLabel('Activos')
                    ->falseLabel('Inactivos'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn (User $record) => Auth::user()?->isAdmin() || Auth::id() === $record->id
                    ),

                Action::make('approve')
                    ->label('Aprobar')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn (User $record) => Auth::user()?->isAdmin() &&
                        ! $record->is_approved &&
                        Auth::id() !== $record->id
                    )
                    ->action(fn (User $record) => $record->update(['is_approved' => true])),

                Action::make('deactivate')
                    ->label('Desactivar')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (User $record) => Auth::user()?->isAdmin() &&
                        $record->is_active &&
                        ! $record->isAdmin() &&
                        Auth::id() !== $record->id
                    )
                    ->action(fn (User $record) => $record->update(['is_active' => false])),

                Tables\Actions\DeleteAction::make()
                    ->visible(fn (User $record) => Auth::user()?->isAdmin() &&
                        ! $record->isAdmin() &&
                        Auth::id() !== $record->id
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => Auth::user()?->isAdmin()),
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
        $user = Auth::user();

        if (! $user) {
            return $query;
        }

        if ($user->isAdmin()) {
            return $query;
        }

        if ($user->isAreaManager()) {
            return $query->whereHas('roles', fn ($q) => $q->where('name', 'teacher'))
                ->where('sede_id', $user->sede_id)
                ->where('area_id', $user->area_id);
        }

        return $query->where('id', $user->id);
    }
}
