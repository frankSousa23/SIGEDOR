<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiteResource\Pages;
use App\Models\Programa;
use App\Models\Sede;
use App\Models\Site;
use App\Models\Teacher;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms;
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
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

/**
 * Recurso Filament para Gestión de Asignaciones de Sede/Área/Programa.
 */
class SiteResource extends Resource
{
    protected static ?string $model = Site::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $navigationLabel = 'Sedes y Cátedras';

    protected static ?string $navigationGroup = 'Asignaciones';

    protected static ?string $modelLabel = 'Asignación de Sede';

    protected static ?string $pluralModelLabel = 'Asignaciones de Sede';

    protected static ?int $navigationSort = 0;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Información del Docente')
                    ->description('Seleccione el docente para la asignación física y curricular')
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
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                $teacher = Teacher::where('cdi', $state)->first();
                                if ($teacher) {
                                    $set('sede_id', $teacher->sede_id);
                                    $set('area_id', $teacher->area_id);
                                }
                            })
                            ->columnSpanFull(),
                    ]),

                Section::make('Ubicación Académica')
                    ->schema([
                        Grid::make(3)
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

                                Select::make('programa_id')
                                    ->label('Programa / Carrera')
                                    ->relationship('programa', 'nombre')
                                    ->required()
                                    ->searchable()
                                    ->preload(),
                            ]),
                    ]),

                Section::make('Carga Académica')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('uc')
                                    ->label('Unidades de Crédito (UC)')
                                    ->numeric()
                                    ->default(3)
                                    ->minValue(1)
                                    ->maxValue(20),

                                TextInput::make('weekHours')
                                    ->label('Horas Semanales')
                                    ->numeric()
                                    ->default(6)
                                    ->minValue(1)
                                    ->maxValue(40),

                                TextInput::make('sections')
                                    ->label('Número de Secciones')
                                    ->numeric()
                                    ->default(1)
                                    ->minValue(1)
                                    ->maxValue(10),
                            ]),

                        Textarea::make('info')
                            ->label('Observaciones / Cátedra')
                            ->rows(3),

                        Toggle::make('is_active')
                            ->label('Asignación Activa')
                            ->default(true),
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

                TextColumn::make('sede.nombre')
                    ->label('Sede')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('area.nombre')
                    ->label('Área')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('programa.nombre')
                    ->label('Programa')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('weekHours')
                    ->label('Horas')
                    ->suffix('h')
                    ->sortable(),

                TextColumn::make('sections')
                    ->label('Secciones')
                    ->sortable(),

                ToggleColumn::make('is_active')
                    ->label('Activo')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('sede_id')
                    ->label('Sede')
                    ->relationship('sede', 'nombre')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('area_id')
                    ->label('Área')
                    ->relationship('area', 'nombre')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('pdf')
                    ->label('PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('danger')
                    ->action(function (Site $record) {
                        $pdf = Pdf::loadView('pdf.site', ['site' => $record]);

                        return response()->streamDownload(
                            fn () => print ($pdf->output()),
                            "sede_{$record->teacher_cdi}.pdf"
                        );
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('export_sites')
                        ->label('Exportar Seleccionados a PDF')
                        ->icon('heroicon-o-document-arrow-down')
                        ->action(function (Collection $records) {
                            $pdf = Pdf::loadView('pdf.sites', ['sites' => $records])
                                ->setPaper('a4', 'landscape');

                            return response()->streamDownload(
                                fn () => print ($pdf->output()),
                                'reporte_sedes_'.now()->format('Ymd_His').'.pdf'
                            );
                        })
                        ->requiresConfirmation(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSites::route('/'),
            'create' => Pages\CreateSite::route('/create'),
            'edit' => Pages\EditSite::route('/{record}/edit'),
        ];
    }
}
