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
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    
    protected static ?string $navigationGroup = 'Manejo de Usuario';

    protected static ?string $navigationLabel = 'Usuarios';

    protected static ?string $modelLabel = 'Usuario';

    protected static ?string $pluralModelLabel = 'Usuarios';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Nombre'),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                TextInput::make('password')
                    ->password()
                    ->required(fn ($context) => $context === 'create')
                    ->maxLength(255)
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->label('Contraseña'),
                TextInput::make('cdi')
                    ->label('CDI')
                    ->nullable()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Select::make('site_id')
                    ->relationship('site', 'name')
                    ->nullable()
                    ->label('Sede'),
                Select::make('roles')
                    ->relationship('roles', 'name')
                    ->preload()
                    ->required()
                    ->label('Rol')
                    ->options([
                        'area_manager' => 'Jefe de Área',
                        'teacher' => 'Profesor'
                    ])
                    ->default('teacher'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('Nombre'),
                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('cdi')
                    ->searchable()
                    ->sortable()
                    ->label('CDI'),
                TextColumn::make('site.name')
                    ->searchable()
                    ->sortable()
                    ->label('Sede'),
                TextColumn::make('roles.name')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'admin' => 'Administrador',
                        'area_manager' => 'Jefe de Área',
                        'teacher' => 'Profesor',
                        default => $state,
                    })
                    ->label('Rol'),
                TextColumn::make('is_approved')
                    ->badge()
                    ->color(fn (bool $state): string => match ($state) {
                        true => 'success',
                        false => 'danger',
                    })
                    ->formatStateUsing(fn (bool $state): string => match ($state) {
                        true => 'Aprobado',
                        false => 'Pendiente',
                    })
                    ->label('Estado'),
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
                    ->label('Sede'),
                SelectFilter::make('is_approved')
                    ->options([
                        '1' => 'Aprobado',
                        '0' => 'Pendiente'
                    ])
                    ->label('Estado'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('approve')
                    ->label('Aprobar')
                    ->icon('heroicon-o-check')
                    ->visible(fn (User $record) => 
                        auth()->user()->hasRole('admin') && 
                        !$record->is_approved && 
                        !$record->hasRole('admin') &&
                        auth()->id() !== $record->id
                    )
                    ->action(fn (User $record) => $record->update(['is_approved' => true])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
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
