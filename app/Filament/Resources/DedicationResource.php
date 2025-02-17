<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DedicationResource\Pages;
use App\Models\Dedication;
use App\Models\Teacher;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Tables\Columns\TextColumn;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Filament\Tables\Actions\EditAction;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;
use App\Models\User;

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
                Forms\Components\Section::make('Información del Docente')
                ->schema([
                    Forms\Components\Select::make('teacher_id')
                        ->relationship('teacher', 'cdi')
                        ->label('Docente')
                        ->options(function () {
                            return Teacher::pluck('cdi', 'id');
                        })
                        ->required()
                        ->rules([
                            Rule::unique('dedications', 'teacher_id')->ignore(request()->record)
                        ])
                        ->validationMessages([
                            'required' => 'Debe seleccionar un docente',
                            'unique' => 'Este docente ya tiene una dedicación asignada'
                        ])
                        ->reactive()
                        ->columnSpan('full'),
                ]),

                Forms\Components\Select::make('name')
                ->options([
                    'TCV' => 'Tiempo Convencional',
                    'MT' => 'Medio Tiempo',
                    'TC' => 'Tiempo Completo',
                    'EX' => 'Exclusiva'
                ])
                ->required()
                ->label('Dedicación')
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set) {
                    $set('hours', null);
                })
                ->searchable()
                ->preload(),

                Forms\Components\Select::make('hours')
                ->label('Horas')
                ->options(function (callable $get) {
                    return Dedication::getValidHours($get('name'));
                })
                ->required()
                ->rules([
                    fn ($get) => function (string $attribute, $value, $fail) use ($get) {
                        $validHours = Dedication::getValidHours($get('name'));
                        if (!in_array($value, $validHours)) {
                            $fail('Las horas seleccionadas no son válidas para esta dedicación.');
                        }
                    }
                ])
                ->searchable()
                ->preload(),

                Forms\Components\Select::make('director')
                ->options([
                    'Coordinador' => 'Coordinador',
                    'Jefe de Departamento' => 'Jefe de Departamento',
                    'Decano' => 'Decano'
                ])
                ->label('Cargo Directivo')
                ->nullable()
                ->searchable()
                ->preload()
                ->helperText('Seleccione si tiene algún cargo directivo'),

                Forms\Components\TextInput::make('studentNumber')
                ->numeric()
                ->label('Número de Estudiantes')
                ->helperText('Número de estudiantes en asesoría (1-10)')
                ->minValue(1)
                ->maxValue(10)
                ->nullable()
                ->rules([
                    'nullable',
                    'integer',
                    'min:1',
                    'max:10'
                ]),

                Forms\Components\TextInput::make('studentHours')
                ->numeric()
                ->label('Horas de Asesoría')
                ->helperText('Número de horas dedicadas a asesorías (1-30)')
                ->minValue(1)
                ->maxValue(30)
                ->nullable()
                ->rules([
                    'nullable',
                    'integer',
                    'min:1',
                    'max:100'
                ]),

                Forms\Components\Textarea::make('info')
                ->label('Observaciones')
                ->nullable()
                ->maxLength(500)
                ->columnSpanFull(),
            ])
            ;
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
                    ->label('Nombre Completo')
                    ->sortable(query: function (Builder $query, string $direction) {
                        $query->orderBy('teachers.name', $direction)
                              ->orderBy('teachers.surName', $direction);
                    })
                    ->searchable(['teachers.name', 'teachers.surName']),
                Tables\Columns\TextColumn::make('name')
                    ->label('Dedicación')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'TCV' => 'Tiempo Convencional',
                        'MT' => 'Medio Tiempo',
                        'TC' => 'Tiempo Completo',
                        'EX' => 'Exclusiva',
                        default => $state,
                    })
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('hours')
                    ->label('Horas')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('director')
                    ->label('Cargo Directivo')
                    ->default('Sin Cargo')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('studentNumber')
                    ->label('Estudiantes Asesoría')
                    ->searchable()
                    ->sortable()
                    ->numeric(),

                Tables\Columns\TextColumn::make('studentHours')
                    ->label('Horas Asesoría')
                    ->searchable()
                    ->sortable()
                    ->numeric(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('director')
        ->label('Cargo Directivo')
        ->options([
            'Coordinador' => 'Coordinador',
            'Jefe de Departamento' => 'Jefe de Departamento',
            'Decano' => 'Decano'
        ]),

    Tables\Filters\SelectFilter::make('teacher_id')
        ->label('Docente')
        ->relationship('teacher', 'cdi')
        ->searchable()
        ->getOptionLabelFromRecordUsing(fn ($record) => $record->name.' '.$record->surName),

    Tables\Filters\Filter::make('hours_range')
        ->form([
            Forms\Components\TextInput::make('min_hours')
                ->label('Horas Mínimas')
                ->numeric(),
            Forms\Components\TextInput::make('max_hours')
                ->label('Horas Máximas')
                ->numeric()
        ])
        ->query(function (Builder $query, array $data) {
            return $query
                ->when($data['min_hours'],
                    fn($q) => $q->where('hours', '>=', $data['min_hours']))
                ->when($data['max_hours'],
                    fn($q) => $q->where('hours', '<=', $data['max_hours']));
        })
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
                        $pdf = Pdf::loadView('pdf.dedications', [
                            'dedications' => $records
                        ])->setPaper('a4', 'landscape');

                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->output();
                        }, 'dedications_'.now()->format('Ymd_His').'.pdf');
                    })
                    ->requiresConfirmation()
            ]),

            ]);
    }


    protected function getTableQuery(): Builder
    {
        $user = \Illuminate\Support\Facades\Auth::user();

        if ($user->hasRole('admin')) {
            return Dedication::query(); // Admin ve todo
        }

        if ($user->hasRole('area_manager')) {
            return Dedication::query()
                ->where('sede_id', $user->sede_id)
                ->where('area_id', $user->area_id); // Area Manager ve solo su sede y área
        }

        return Dedication::where('user_id', $user->id); // Teacher ve solo sus propios registros
    }


    protected function getTableActions(): array
{
    return [
        EditAction::make(),
    ];
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
            'index' => Pages\ListDedications::route('/'),
            'create' => Pages\CreateDedication::route('/create'),
            'edit' => Pages\EditDedication::route('/{record}/edit'),
        ];
    }
}
