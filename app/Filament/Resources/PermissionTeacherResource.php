<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PermissionTeacherResource\Pages;
use App\Models\PermissionTeacher;
use App\Models\Teacher;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;

class PermissionTeacherResource extends Resource
{
    protected static ?string $model = PermissionTeacher::class;
    protected static ?string $modelLabel = 'Permiso';
    protected static ?string $pluralModelLabel = 'Permisos';
    protected static ?string $navigationIcon = 'heroicon-o-document-check';
    protected static ?string $navigationLabel = 'Permisos';
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
                Forms\Components\Section::make('Información del Docente')
                    ->description('Seleccione el docente e ingrese sus permisos')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Select::make('teacher_id')
                            ->relationship('teacher', 'cdi')
                            ->label('Docente')
                            ->options(function () {
                                return Teacher::whereDoesntHave('permissionTeachers')->pluck('cdi', 'id');
                            })
                            ->required()
                            ->reactive()
                            ->columnSpan('full'),
                    ]),

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
                    ->default('pending')
                    ->required()
                    ->label('Estado')
                    ->native(false),

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
                Tables\Columns\TextColumn::make('teacher.cdi')
                    ->label('Cédula')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('teacher.name')
                    ->label('Nombres')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('teacher.surName')
                    ->label('Apellidos')
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
                    ->relationship('teacher', 'cdi')
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
                    Tables\Actions\BulkAction::make('export')
                    ->label('Exportar a PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->action(function (Collection $records) {
                        $pdf = Pdf::loadView('pdf.permission_teachers', [
                            'permission_teachers' => $records
                        ])->setPaper('a4', 'landscape');

                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->output();
                        }, 'permission_teachers_'.now()->format('Ymd_His').'.pdf');
                    })
                    ->requiresConfirmation()
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
