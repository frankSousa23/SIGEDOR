<?php

namespace App\Filament\Resources;

use App\Models\Site;
use App\Models\Teacher;
use App\Models\Sede;
use App\Models\Area;
use App\Models\Programa;
use App\Filament\Resources\SiteResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class SiteResource extends Resource
{
    protected static ?string $model = Site::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationLabel = 'Sedes';
    protected static ?string $navigationGroup = 'Asignaciones';
    protected static ?string $modelLabel = 'Sede';
    protected static ?string $pluralModelLabel = 'Sedes';
    protected static ?int $navigationSort = 0;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información del Docente')
    ->description('Seleccione el docente para asignar Sede')
    ->collapsible()
    ->schema([
        Select::make('teacher_id')
            ->label('Docente')
            ->options(function ($record) {
                return Teacher::whereNull('site_id')  // Solo docentes sin site asignada
                    ->orWhere('id', $record?->teacher_id)  // Permite editar el registro actual
                    ->pluck('cdi', 'id');
            })
            ->required()
            ->searchable()
            ->preload()
            ->live()
            ->afterStateUpdated(function ($state, Forms\Set $set) {
                $teacher = Teacher::find($state);
                if ($teacher && $teacher->user) {
                    $set('sede_id', $teacher->user->sede_id);
                    $set('area_id', $teacher->user->area_id);
                }
            })
            ->rules([
                Rule::unique('sites', 'teacher_id')  // Validación en backend
            ])
            ->validationMessages([
                'unique' => 'Este docente ya tiene una sede asignada'
            ]),
    ]),


             Forms\Components\Select::make('sede_id')
                ->label('Sede')
                ->options(Sede::all()->pluck('nombre', 'id'))
                ->disabled(fn ($get) => $get('teacher_id') !== null)
                ->default(fn () => null)
                ->dehydrated(fn ($state) => filled($state)),

            Forms\Components\Select::make('area_id')
                ->label('Área')
                ->options(Area::all()->pluck('nombre', 'id'))
                ->disabled(fn ($get) => $get('teacher_id') !== null)
                ->default(fn () => null)
                ->dehydrated(fn ($state) => filled($state)),

                Forms\Components\Select::make('programa_id')
                ->label('Programa')
                ->options(Programa::all()->pluck('nombre', 'id'))
                ->required()
                ->searchable()
                ->preload()
                ->default(null)
                ->rules([
                    fn ($get) => Rule::unique('sites', 'programa_id')->where('teacher_id', $get('teacher_id'))
                ])
                ->validationMessages([
                    'unique' => 'Este docente ya tiene un programa asignado'
                ]),

            Forms\Components\TextInput::make('uc')
                ->label('Unidad Curricular')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('weekHours')
                ->label('Horas Semanales')
                ->numeric()
                ->minValue(1)
                ->maxValue(40),

            Forms\Components\TextInput::make('sections')
                ->label('Secciones')
                ->numeric()
                ->minValue(1)
                ->maxValue(10),

            Forms\Components\Textarea::make('info')
                ->label('Información Adicional')
                ->maxLength(255)
                ->columnSpanFull(),

            Forms\Components\Toggle::make('is_active')
                ->label('Activo')
                ->default(true),

            Forms\Components\Toggle::make('is_available')
                ->label('Disponible')
                ->default(true),
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
                Tables\Columns\TextColumn::make('teacher.full_name')
                    ->label('Docente')
                    ->sortable(query: function (Builder $query, string $direction) {
                        $query->orderBy('teachers.name', $direction)
                              ->orderBy('teachers.surName', $direction);
                    })
                    ->searchable(['teachers.name', 'teachers.surName']),
                Tables\Columns\TextColumn::make('sede.nombre')
                    ->label('Sede')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('area.nombre')
                    ->label('Área')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('programa.nombre')
                    ->label('Programa')
                    ->searchable()
                    ->sortable()
                    ->listWithLineBreaks(),
                Tables\Columns\TextColumn::make('uc')
                    ->label('Unidad Curricular')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('weekHours')
                    ->label('Horas Semanales')
                    ->searchable()
                    ->sortable()
                    ->numeric(),
                Tables\Columns\TextColumn::make('sections')
                    ->label('Secciones')
                    ->searchable()
                    ->sortable()
                    ->numeric(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Activo')
                    ->searchable()
                    ->sortable()
                    ->boolean(),

                Tables\Columns\IconColumn::make('is_available')
                    ->label('Disponible')
                    ->searchable()
                    ->sortable()
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('programa_id')
                ->label('Programa')
                ->relationship('programa', 'nombre')
                ->searchable(),

            Tables\Filters\SelectFilter::make('teacher_id')
                ->label('Docente')
                ->relationship('teacher', 'cdi')
                ->searchable()
                ->getOptionLabelFromRecordUsing(fn ($record) => $record->name.' '.$record->surName),

            Tables\Filters\TernaryFilter::make('is_active')
                ->label('Estado Activo'),

            Tables\Filters\Filter::make('created_at')
                ->form([
                    Forms\Components\DatePicker::make('from'),
                    Forms\Components\DatePicker::make('to'),
                ])
                ->query(fn ($query, $data) => $query
                    ->when($data['from'], fn($q) => $q->whereDate('created_at', '>=', $data['from']))
                    ->when($data['to'], fn($q) => $q->whereDate('created_at', '<=', $data['to']))
                ),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Activo'),

                Tables\Filters\TernaryFilter::make('is_available')
                    ->label('Disponible'),
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
                        $pdf = Pdf::loadView('pdf.sites', [
                            'sites' => $records
                        ])->setPaper('a4', 'landscape');

                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->output();
                        }, 'sites_'.now()->format('Ymd_His').'.pdf');
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
            'index' => Pages\ListSites::route('/'),
            'create' => Pages\CreateSite::route('/create'),
            'edit' => Pages\EditSite::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return 'Sedes';
    }
}
