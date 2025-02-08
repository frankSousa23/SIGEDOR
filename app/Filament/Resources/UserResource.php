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
use App\Enums\Role as RoleEnum;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

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
                TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->label('Correo Electrónico')
                    ->email()
                    ->required()
                    ->maxLength(255),
                TextInput::make('password')
                    ->label('Contraseña')
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->same('passwordConfirmation')
                    ->required(fn ($livewire) => ($livewire instanceof Pages\CreateUser))
                    ->minLength(8)
                    ->maxLength(255),
                TextInput::make('passwordConfirmation')
                    ->password()
                    ->label('Confirmar Contraseña')
                    ->minLength(8)
                    ->maxLength(255)
                    ->dehydrated(false),
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
                        Select::make('roles')
                            ->multiple()
                            ->relationship('roles', 'name')
                            ->options(Role::all()->pluck('name', 'id')),
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
            ->query(User::query()->with('roles')->limit(50))
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Correo Electrónico')
                    ->searchable(),
                TextColumn::make('roles.name')
                    ->label('Rol')
                    ->badge()
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
                        auth()->user()->can('update', $record)
                    ),

                Tables\Actions\DeleteAction::make()
                    ->visible(fn (User $record) =>
                        auth()->user()->can('delete', $record)
                    ),

                Action::make('approve')
                    ->label('Aprobar')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn (User $record) =>
                        auth()->user()->can('update', $record) &&
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
                        auth()->user()->can('update', $record) &&
                        $record->is_active &&
                        !$record->hasRole('admin') &&
                        auth()->id() !== $record->id
                    )
                    ->action(fn (User $record) => $record->update(['is_active' => false])),

                Tables\Actions\CreateAction::make()
                    ->after(function (User $user) {
                        try {
                            Teacher::firstOrCreate(
                                ['user_id' => $user->id],
                                ['site_option_id' => $user->site_option_id]
                            );
                        } catch (\Throwable $th) {
                            logger()->error("Error creando Teacher: " . $th->getMessage());
                        }
                    }),

                Action::make('revokeRole')
                    ->label('Revoke Role')
                    ->requiresConfirmation()
                    ->action(function (User $record) {
                        $record->roles()->detach();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => auth()->user()->can('deleteAny', User::class)),
                ]),
            ])
            ->defaultSort('id', 'desc');
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

        if (auth()->user()->hasRole('area_manager')) {
            return $query->where('site_id', auth()->user()->site_id);
        }

        if (auth()->user()->hasRole('teacher')) {
            return $query->where('id', auth()->id());
        }

        return $query;
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->can('viewAny', User::class);
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can('create', User::class);
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()->can('update', $record);
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()->can('delete', $record);
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
    return parent::getEloquentQuery() // Usar getEloquentQuery() en lugar de query()
        ->where('id', '!=', auth()->id());
    }

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()
            ->when(
                auth()->user()->hasRole('area_manager'),
                fn (Builder $query) => $query->where('site_id', auth()->user()->site_id)
            )
            ->when(
                auth()->user()->hasRole('teacher'),
                fn (Builder $query) => $query->where('id', auth()->id())
            );
    }
}
