<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PermissionTeacherResource\Pages;
use App\Models\PermissionTeacher;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PermissionTeacherResource extends Resource
{
    protected static ?string $model = PermissionTeacher::class;
    protected static ?string $modelLabel = 'Permiso Docente';
    protected static ?string $pluralModelLabel = 'Permisos Docentes';
    protected static ?string $navigationIcon = 'heroicon-o-document-check';
    protected static ?string $navigationLabel = 'Permisos Docentes';
    protected static ?string $navigationGroup = 'Asignaciones';
    protected static ?int $navigationSort = 4;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('teacher_id')
                    ->relationship('teacher', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->label('Nombre'),
                        Forms\Components\TextInput::make('ci')
                            ->required()
                            ->label('Cédula')
                            ->unique('teachers', 'ci'),
                        Forms\Components\TextInput::make('phone')
                            ->required()
                            ->label('Teléfono')
                            ->tel(),
                        Forms\Components\Textarea::make('address')
                            ->required()
                            ->label('Dirección'),
                    ])
                    ->createOptionAction(function (Forms\Components\Actions\Action $action) {
                        return $action
                            ->modalHeading('Crear nuevo docente')
                            ->modalButton('Crear docente')
                            ->modalWidth('lg');
                    })
                    ->label('Docente'),

                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Nombre del Permiso')
                    ->maxLength(255),

                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pendiente',
                        'approved' => 'Aprobado',
                        'rejected' => 'Rechazado'
                    ])
                    ->required()
                    ->searchable()
                    ->label('Estado'),

                Forms\Components\DatePicker::make('start_date')
                    ->required()
                    ->label('Fecha de Inicio')
                    ->format('Y-m-d'),

                Forms\Components\DatePicker::make('end_date')
                    ->required()
                    ->label('Fecha de Fin')
                    ->format('Y-m-d')
                    ->after('start_date'),

                Forms\Components\Textarea::make('description')
                    ->required()
                    ->label('Descripción')
                    ->maxLength(500)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('teacher.name')
                    ->label('Docente')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre del Permiso')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Estado')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ])
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('start_date')
                    ->label('Fecha de Inicio')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('end_date')
                    ->label('Fecha de Fin')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Descripción')
                    ->limit(50),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pendiente',
                        'approved' => 'Aprobado',
                        'rejected' => 'Rechazado'
                    ])
                    ->label('Estado'),
                Tables\Filters\SelectFilter::make('teacher')
                    ->relationship('teacher', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Docente'),
                Tables\Filters\Filter::make('start_date')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('Desde'),
                        Forms\Components\DatePicker::make('until')
                            ->label('Hasta'),
                    ])
                    ->query(function ($query, array $data): mixed {
                        return $query
                            ->when(
                                $data['from'],
                                fn ($query, $date): mixed => $query->whereDate('start_date', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn ($query, $date): mixed => $query->whereDate('start_date', '<=', $date),
                            );
                    })
                    ->label('Fecha de Inicio'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListPermissionsTeachers::route('/'),
            'create' => Pages\CreatePermissionTeacher::route('/create'),
            'edit' => Pages\EditPermissionTeacher::route('/{record}/edit'),
        ];
    }    
}
