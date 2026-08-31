<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PermissionTeacherResource\Pages;
use App\Models\PermissionTeacher;
use App\Models\Teacher;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Recurso Filament para Solicitudes y Gestión de Permisos Docentes.
 */
class PermissionTeacherResource extends Resource
{
    protected static ?string $model = PermissionTeacher::class;

    protected static ?string $modelLabel = 'Permiso';

    protected static ?string $pluralModelLabel = 'Permisos';

    protected static ?string $navigationIcon = 'heroicon-o-document-check';

    protected static ?string $navigationLabel = 'Permisos';

    protected static ?string $navigationGroup = 'Gestión Reportes';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Información del Docente y Solicitud')
                    ->schema([
                        Select::make('teacher_cdi')
                            ->label('Docente Solicitante')
                            ->options(function ($record) {
                                return Teacher::all()->mapWithKeys(fn ($teacher) => [
                                    $teacher->cdi => "{$teacher->cdi} - {$teacher->name} {$teacher->surName}",
                                ]);
                            })
                            ->required()
                            ->searchable()
                            ->preload()
                            ->columnSpanFull(),

                        Grid::make(3)
                            ->schema([
                                TextInput::make('memo_number')
                                    ->label('Nº de Memorando')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(100),

                                Select::make('type')
                                    ->label('Tipo de Permiso')
                                    ->options(array_combine(PermissionTeacher::TYPES, PermissionTeacher::TYPES))
                                    ->required(),

                                Select::make('duration_type')
                                    ->label('Modalidad de Duración')
                                    ->options(PermissionTeacher::DURATION_TYPES)
                                    ->required()
                                    ->default('semestral'),
                            ]),

                        Grid::make(2)
                            ->schema([
                                DatePicker::make('start_date')
                                    ->label('Fecha de Inicio')
                                    ->required(),

                                DatePicker::make('end_date')
                                    ->label('Fecha de Finalización')
                                    ->nullable(),
                            ]),

                        Grid::make(2)
                            ->schema([
                                Select::make('status')
                                    ->label('Estado de la Solicitud')
                                    ->options([
                                        'pending' => 'Pendiente',
                                        'approved' => 'Aprobado',
                                        'rejected' => 'Rechazado',
                                    ])
                                    ->default('pending')
                                    ->required(),

                                Toggle::make('is_paid')
                                    ->label('Permiso Remunerado')
                                    ->default(true),
                            ]),

                        Textarea::make('description')
                            ->label('Motivo / Justificación')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('memo_number')
                    ->label('Nº Memo')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('teacher.full_name')
                    ->label('Docente')
                    ->searchable(['name', 'surName'])
                    ->sortable(),

                TextColumn::make('type')
                    ->label('Tipo de Permiso')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'warning',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'approved' => 'Aprobado',
                        'rejected' => 'Rechazado',
                        default => 'Pendiente',
                    })
                    ->sortable(),

                TextColumn::make('start_date')
                    ->label('Inicio')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('end_date')
                    ->label('Fin')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('is_paid')
                    ->label('Remunerado')
                    ->badge()
                    ->color(fn (bool $state): string => $state ? 'success' : 'gray')
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Sí' : 'No'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'pending' => 'Pendiente',
                        'approved' => 'Aprobado',
                        'rejected' => 'Rechazado',
                    ]),

                SelectFilter::make('type')
                    ->label('Tipo')
                    ->options(array_combine(PermissionTeacher::TYPES, PermissionTeacher::TYPES)),

                SelectFilter::make('duration_type')
                    ->label('Duración')
                    ->options([
                        'semestral' => 'Semestral',
                        'anual' => 'Anual',
                        'libre' => 'Libre',
                    ]),

                SelectFilter::make('teacher_id')
                    ->label('Docente')
                    ->relationship('teacher', 'cdi')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->name.' '.$record->surName)
                    ->searchable()
                    ->preload(),

                Filter::make('start_date_range')
                    ->label('Rango de Fecha de Inicio')
                    ->form([
                        DatePicker::make('from')->label('Desde'),
                        DatePicker::make('until')->label('Hasta'),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => $query
                        ->when($data['from'], fn ($q) => $q->whereDate('start_date', '>=', $data['from']))
                        ->when($data['until'], fn ($q) => $q->whereDate('start_date', '<=', $data['until']))
                    ),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('approve')
                    ->label('Aprobar')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn (PermissionTeacher $record) => $record->status === 'pending')
                    ->action(fn (PermissionTeacher $record) => $record->update(['status' => 'approved'])),
                Action::make('pdf')
                    ->label('PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('danger')
                    ->action(function (PermissionTeacher $record) {
                        $pdf = Pdf::loadView('pdf.permission', ['permission' => $record]);

                        return response()->streamDownload(
                            fn () => print ($pdf->output()),
                            "permiso_{$record->memo_number}.pdf"
                        );
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('export_permissions')
                        ->label('Exportar Seleccionados a PDF')
                        ->icon('heroicon-o-document-arrow-down')
                        ->action(function (Collection $records) {
                            $pdf = Pdf::loadView('pdf.permissionsteachers', ['permissions' => $records])
                                ->setPaper('a4', 'landscape');

                            return response()->streamDownload(
                                fn () => print ($pdf->output()),
                                'reporte_permisos_'.now()->format('Ymd_His').'.pdf'
                            );
                        })
                        ->requiresConfirmation(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPermissionsTeachers::route('/'),
            'create' => Pages\CreatePermissionTeacher::route('/create'),
            'edit' => Pages\EditPermissionTeacher::route('/{record}/edit'),
        ];
    }

    /**
     * Aislamiento de datos por rol:
     * - Admin: ve todos los permisos.
     * - Area Manager: solo los de docentes de su sede.
     * - Teacher: solo los propios.
     */
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        if ($user && $user->hasRole('area_manager') && $user->sede_id) {
            return $query->whereHas('teacher.user', fn ($q) => $q->where('sede_id', $user->sede_id));
        }

        if ($user && $user->hasRole('teacher')) {
            return $query->whereHas('teacher', fn ($q) => $q->where('user_id', $user->id));
        }

        return $query;
    }
}
