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
        $isAdmin = auth()->user()->hasRole('admin');
        $isCreate = $form->getOperation() === 'create';
        $record = $form->getRecord();

        return $form->schema([
            Section::make('Información del Usuario')
                ->description('Información básica del usuario')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('name')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(255)
                            ->disabled(fn () => !$isAdmin && $record?->hasRole('admin')),

                        TextInput::make('email')
                            ->label('Correo')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->disabled(fn () => !$isAdmin && $record?->hasRole('admin')),

                        TextInput::make('cdi')
                            ->label('Cédula de Identidad')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(10)
                            ->helperText('Sin puntos ni espacios')
                            ->disabled(fn () => !$isAdmin && $record?->hasRole('admin')),

                        TextInput::make('password')
                            ->label('Contraseña')
                            ->password()
                            ->required(fn ($context) => $context === 'create')
                            ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                            ->visible(fn () => $isCreate || $isAdmin || auth()->id() === $record?->id),
                    ]),
                ]),

            Section::make('Asignación y Permisos')
                ->description('Configuración de rol y sede')
                ->visible(fn () => $isAdmin || auth()->user()->hasRole('area_manager'))
                ->schema([
                    Grid::make(2)->schema([
                        Select::make('site_id')
                            ->label('Sede')
                            ->relationship('site', 'name')
                            ->searchable()
                            ->preload()
                            ->required(fn () => !$isCreate || $isAdmin)
                            ->visible(fn () => $isAdmin || (auth()->user()->hasRole('area_manager') && !$record?->hasRole('admin'))),

                        Select::make('roles')
                            ->label('Rol')
                            ->multiple(false)
                            ->relationship('roles', 'name')
                            ->preload()
                            ->required()
                            ->options([
                                'admin' => 'Administrador',
                                'area_manager' => 'Jefe de Área',
                                'teacher' => 'Profesor'
                            ])
                            ->default('teacher')
                            ->disabled(fn () => !$isAdmin || $record?->hasRole('admin'))
                            ->visible(fn () => $isAdmin),

                        Forms\Components\Toggle::make('is_approved')
                            ->label('Aprobado')
                            ->default(false)
                            ->disabled(fn () => !$isAdmin || $record?->hasRole('admin'))
                            ->visible(fn () => $isAdmin),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Activo')
                            ->default(true)
                            ->disabled(fn () => !$isAdmin || $record?->hasRole('admin'))
                            ->visible(fn () => $isAdmin),
                    ]),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label('Correo')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('cdi')
                    ->label('Cédula')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('site.name')
                    ->label('Sede')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('roles.name')
                    ->label('Rol')
                    ->formatStateUsing(fn (User $record) => $record->getFullRoleName())
                    ->searchable()
                    ->sortable(),

                TextColumn::make('is_approved')
                    ->label('Estado')
                    ->badge()
                    ->color(fn ($state): string => match ($state) {
                        true, 1, '1' => 'success',
                        false, 0, '0', null => 'danger',
                        default => 'danger',
                    })
                    ->formatStateUsing(fn ($state): string => match ($state) {
                        true, 1, '1' => 'Aprobado',
                        false, 0, '0', null => 'Pendiente',
                        default => 'Pendiente',
                    }),

                TextColumn::make('is_active')
                    ->label('Activo')
                    ->badge()
                    ->color(fn ($state): string => $state ? 'success' : 'danger')
                    ->formatStateUsing(fn ($state): string => $state ? 'Sí' : 'No'),
            ])
            ->filters([
                SelectFilter::make('roles')
                    ->relationship('roles', 'name')
                    ->options([
                        'area_manager' => 'Jefe de Área',
                        'teacher' => 'Profesor'
                    ])
                    ->label('Rol'),

                SelectFilter::make('site')
                    ->relationship('site', 'name')
                    ->label('Sede')
                    ->searchable()
                    ->preload(),

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
            return $query->whereHas('roles', function ($query) {
                $query->where('name', 'teacher');
            })->where('site_id', auth()->user()->site_id);
        }

        return $query->where('id', auth()->id());
    }
}
