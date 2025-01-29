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
use App\Filament\Resources\SiteResource;
use Spatie\Permission\Models\Role;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\Filter;
use App\Models\AreaOption;
use App\Models\SiteOption;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\BadgeColumn;
use App\Models\Teacher;

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
                TextInput::make('email')
                    ->label('Correo Electrónico')
                    ->required()
                    ->email()
                    ->rules(['regex:/@sigedor\.com$/'])
                    ->autocomplete('email')
                    ->helperText('El correo debe terminar en @sigedor.com'),
                TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255),
                TextInput::make('password')
                    ->label('Contraseña')
                    ->password()
                    ->required()
                    ->minLength(8)
                    ->visibleOn('create'),
                Section::make('Asignación de Sede y Área')
                    ->schema([
                        Select::make('site_option_id')
                            ->label('Sede')
                            ->options(SiteOption::all()->pluck('name', 'id'))
                            ->required(),
                        Select::make('area_option_id')
                            ->label('Área')
                            ->options(AreaOption::all()->pluck('name', 'id'))
                            ->required()
                            ->columnSpanFull(),
                    ]),
                Section::make('Asignación de Rol')
                    ->schema([
                        Select::make('role')
                            ->label('Rol')
                            ->options([
                                'admin' => 'Administrador',
                                'area_manager' => 'Gerente de Área',
                                'teacher' => 'Docente',
                            ])
                            ->required(),
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
                TextColumn::make('roles.name')
                    ->label('Rol')
                    ->formatStateUsing(fn ($state) => $state ?? 'Sin asignar')
                    ->searchable()
                    ->formatStateUsing(fn ($state) => $state ?? 'Sin asignar')
                    ->searchable(query: fn (Builder $query, string $search) =>
                        $query->whereHas('roles', fn ($q) => $q->where('name', 'like', "%{$search}%"))
                    ),
                TextColumn::make('siteOption.name')
                    ->label('Sede')
                    ->colors(['success' => 'Sin Sede'])
                    ->formatStateUsing(fn ($state) => $state ?? 'Sin asignar'),
                TextColumn::make('areaOption.name')
                    ->label('Área')
                    ->formatStateUsing(fn ($state) => $state ?? 'Sin asignar'),
                ToggleColumn::make('is_active')
                    ->label('Activo'),
                ToggleColumn::make('is_approved')
                    ->label('Aprobado'),
                TextColumn::make('activities.description'),
            ])
            ->filters([
                SelectFilter::make('role_id')
                    ->relationship('roles', 'name')
                    ->searchable()
                    ->preload(),

                Filter::make('site')
                    ->form([
                        Select::make('site_option_id')
                            ->options(SiteOption::pluck('name', 'id'))
                    ])
                    ->query(fn (Builder $query, array $data) => $query->where('site_option_id', $data['site_option_id'])),

                Filter::make('area')
                    ->form([
                        Select::make('area_option_id')
                            ->options(AreaOption::pluck('name', 'id'))
                    ])
                    ->query(fn (Builder $query, array $data) => $query->where('area_option_id', $data['area_option_id'])),

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

                Tables\Actions\CreateAction::make()
                    ->after(function (User $user) {
                        Teacher::create([
                            'user_id' => $user->id,
                            'site_option_id' => $user->site_option_id
                        ]);
                    }),
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
        return parent::getEloquentQuery()
            ->whereHas('roles', function ($query) {
                $query->where('name', 'admin');
            });
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->hasRole('admin');
    }

    public static function canCreate(): bool
    {
        return auth()->user()->hasRole('admin');
    }

    public static function canEdit($record): bool
    {
        return auth()->user()->hasRole('admin');
    }

    public static function canDelete($record): bool
    {
        return auth()->user()->hasRole('admin');
    }

    protected function handleRecordCreation(array $data): User
    {
        $user = parent::handleRecordCreation($data);

        // Post-creación con ID existente
        $user->sites()->updateOrCreate([
            'site_option_id' => $data['site_option_id'],
            'area_option_id' => $data['area_option_id']
        ], ['user_id' => $user->id]);

        return $user;
    }

    public static function query(): Builder
    {
        return parent::query()->where('id', '!=', auth()->id()); // Excluye al usuario actual si es necesario
    }
}
