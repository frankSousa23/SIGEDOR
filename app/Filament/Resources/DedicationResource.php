<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DedicationResource\Pages;
use App\Models\Dedication;
use App\Models\Teacher;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

/**
 * Recurso Filament para Gestión de Dedicación y Carga Horaria Docente.
 */
class DedicationResource extends Resource
{
    protected static ?string $model = Dedication::class;
    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationLabel = 'Dedicaciones';
    protected static ?string $navigationGroup = 'Asignaciones';
    protected static ?string $modelLabel = 'Dedicación';
    protected static ?string $pluralModelLabel = 'Dedicaciones';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Información del Docente')
                    ->schema([
                        Select::make('teacher_cdi')
                            ->label('Docente')
                            ->options(function ($record) {
                                return Teacher::all()->mapWithKeys(fn ($teacher) => [
                                    $teacher->cdi => "{$teacher->cdi} - {$teacher->name} {$teacher->surName}",
                                ]);
                            })
                            ->required()
                            ->searchable()
                            ->preload()
                            ->unique(ignoreRecord: true)
                            ->columnSpanFull(),
                    ]),

                Section::make('Dedicación y Horas Semanales')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('name')
                                    ->label('Tipo de Dedicación')
                                    ->options(Dedication::DEDICATIONS)
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                                        $defaultHours = match ($state) {
                                            'Tiempo Convencional' => 12,
                                            'Medio Tiempo' => 18,
                                            'Tiempo Completo' => 30,
                                            'Exclusiva' => 36,
                                            default => 12,
                                        };
                                        $set('hours', $defaultHours);
                                    }),

                                TextInput::make('hours')
                                    ->label('Horas Semanales')
                                    ->numeric()
                                    ->required()
                                    ->minValue(1)
                                    ->maxValue(40),
                            ]),

                        Grid::make(3)
                            ->schema([
                                Select::make('director')
                                    ->label('Cargo Administrativo / Directivo')
                                    ->options([
                                        'Coordinador' => 'Coordinador',
                                        'Jefe de Departamento' => 'Jefe de Departamento',
                                        'Decano' => 'Decano',
                                        'Director' => 'Director',
                                        'Sub-Director' => 'Sub-Director',
                                    ])
                                    ->placeholder('Ninguno'),

                                TextInput::make('studentNumber')
                                    ->label('Número de Estudiantes')
                                    ->numeric()
                                    ->nullable(),

                                TextInput::make('studentHours')
                                    ->label('Horas de Asesoría / Tutoría')
                                    ->numeric()
                                    ->nullable(),
                            ]),

                        Textarea::make('info')
                            ->label('Observaciones')
                            ->rows(3),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('teacher_cdi')
                    ->label('Cédula')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('teacher.full_name')
                    ->label('Docente')
                    ->searchable(['name', 'surName'])
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Tipo de Dedicación')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Exclusiva' => 'success',
                        'Tiempo Completo' => 'primary',
                        'Medio Tiempo' => 'warning',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),

                TextColumn::make('hours')
                    ->label('Horas/Semana')
                    ->suffix(' hrs')
                    ->sortable(),

                TextColumn::make('director')
                    ->label('Cargo')
                    ->badge()
                    ->color('info')
                    ->placeholder('Docente ordinario')
                    ->searchable(),

                TextColumn::make('studentNumber')
                    ->label('Estudiantes')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('name')
                    ->label('Dedicación')
                    ->options(Dedication::DEDICATIONS),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('pdf')
                    ->label('PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('danger')
                    ->action(function (Dedication $record) {
                        $pdf = Pdf::loadView('pdf.dedication', ['dedication' => $record]);
                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            "dedicacion_{$record->teacher_cdi}.pdf"
                        );
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('export_dedications')
                        ->label('Exportar Seleccionados a PDF')
                        ->icon('heroicon-o-document-arrow-down')
                        ->action(function (Collection $records) {
                            $pdf = Pdf::loadView('pdf.dedications', ['dedications' => $records])
                                ->setPaper('a4', 'landscape');

                            return response()->streamDownload(
                                fn () => print($pdf->output()),
                                'reporte_dedicaciones_' . now()->format('Ymd_His') . '.pdf'
                            );
                        })
                        ->requiresConfirmation(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDedications::route('/'),
            'create' => Pages\CreateDedication::route('/create'),
            'edit' => Pages\EditDedication::route('/{record}/edit'),
        ];
    }
}
