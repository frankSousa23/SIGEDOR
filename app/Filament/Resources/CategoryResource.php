<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
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
use App\Models\Teacher;
use Illuminate\Support\Carbon;
use Filament\Notifications\Notification;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Filament\Tables\Actions\EditAction;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;


class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'Categorías';
    protected static ?string $navigationGroup = 'Asignaciones';
    protected static ?string $modelLabel = 'Categoría';
    protected static ?string $pluralModelLabel = 'Categorías';
    protected static ?int $navigationSort = 2;

    protected static function shouldEnableAutoAssistant($preTitle, $lastTitle): bool
    {
        if (empty($preTitle) || empty($lastTitle)) {
            return false;
        }

        $preTitle = mb_strtolower($preTitle);
        $lastTitle = mb_strtolower($lastTitle);

        $keywords = [
            'doctorado' => 4,
            'doctor' => 4,
            'phd' => 4,
            'ph.d' => 4,
            'maestria' => 3,
            'maestría' => 3,
            'magister' => 3,
            'magíster' => 3,
            'master' => 3,
            'especialista' => 2,
            'especialización' => 2,
            'especializacion' => 2
        ];

        $preLevel = 1;
        $lastLevel = 1;

        foreach ($keywords as $keyword => $level) {
            if (str_contains($preTitle, $keyword)) {
                $preLevel = max($preLevel, $level);
            }
            if (str_contains($lastTitle, $keyword)) {
                $lastLevel = max($lastLevel, $level);
            }
        }

        return $lastLevel > $preLevel;
    }

    protected static function getRequiredTitlesForHigherCategories(): array
    {
        return [
            'doctorado',
            'doctor',
            'phd',
            'ph.d',
        ];
    }

    protected static function hasValidTitleForHigherCategory(string $title): bool
    {
        $title = mb_strtolower($title);
        foreach (static::getRequiredTitlesForHigherCategories() as $required) {
            if (str_contains($title, $required)) {
                return true;
            }
        }
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información del Docente')
                    ->description('Seleccione el docente e ingrese sus títulos académicos')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Select::make('teacher_id')
                            ->relationship('teacher', 'cdi')
                            ->label('Docente')
                            ->options(function () {
                                return Teacher::whereDoesntHave('category')->pluck('cdi', 'id');
                            })
                            ->required()
                            ->rules([
                    Rule::unique('categories', 'teacher_id')
                ])
                        ->validationMessages([
                            'required' => 'Debe seleccionar un docente',
                            'unique' => 'Este docente ya tiene una categoría asignada'
                ])
                        ->reactive()
                        ->columnSpan('full'),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('preTitle')
                                    ->label('Título de Pregrado')
                                    ->placeholder('Ej: Licenciado en Matemáticas')
                                    ->helperText('Ingrese el título de pregrado completo')
                                    ->live(debounce: 2000)
                                    ->required()
                                    ->maxLength(255)
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                        if (!$get('lastTitle')) {
                                            $set('lastTitle', $state);
                                        }
                                    }),

                                Forms\Components\TextInput::make('lastTitle')
                                    ->label('Título Actual')
                                    ->placeholder('Ej: Doctor en Educación')
                                    ->helperText('Ingrese el título más alto obtenido')
                                    ->live(debounce: 2000)
                                    ->required()
                                    ->maxLength(255)
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                        if ($state && $get('preTitle')) {
                                            $shouldEnable = static::shouldEnableAutoAssistant($get('preTitle'), $state);
                                            if ($shouldEnable) {
                                                $set('disable_assistant_rule', true);
                                            }
                                        }
                                    }),
                            ]),

                        Forms\Components\Toggle::make('disable_assistant_rule')
                            ->label('Promoción Inmediata')
                            ->helperText('Active para habilitar la promoción inmediata a Asistente')
                            ->live(),
                    ]),

                Forms\Components\Section::make('Fechas de Categorías')
                    ->description('Ingrese las fechas de cada categoría docente')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\DatePicker::make('instructor')
                                    ->label('Fecha de Instructor')
                                    ->required()
                                    ->live(debounce: 2000)
                                    ->rules(['required', 'date', 'after_or_equal:1980-01-01'])
                                    ->validationMessages([
                                'required' => 'La fecha de instructor es obligatoria',
                                'after_or_equal' => 'La fecha no puede ser anterior a 1980'
                        ])
                                    ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                        if ($state) {
                                            $set('current_category', 'Instructor');
                                            if ($get('disable_assistant_rule')) {
                                                $set('asistente', $state);
                                                $set('current_category', 'Asistente');
                                            }
                                        }
                                    }),

                                Forms\Components\DatePicker::make('asistente')
                                    ->label('Fecha de Asistente')
                                    ->live(debounce: 2000)
                                    ->disabled(fn ($get) => !$get('instructor'))
                                    ->dehydrated()
                                    ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                        if ($state && !$get('disable_assistant_rule')) {
                                            $instructorDate = $get('instructor');
                                            if ($instructorDate) {
                                                $minDate = Carbon::parse($instructorDate)->addYears(2)->startOfDay();
                                                if (Carbon::parse($state)->startOfDay()->lessThan($minDate)) {
                                                    Notification::make()
                                                        ->warning()
                                                        ->title('Aviso')
                                                        ->body('Se recomienda que la fecha sea al menos 2 años después de la fecha del instructor.')
                                                        ->duration(10000)
                                                        ->send();
                                                }
                                                $set('current_category', 'Asistente');
                                            }
                                        }
                                    }),

                                Forms\Components\DatePicker::make('agregado')
                                    ->label('Fecha de Agregado')
                                    ->live(debounce: 2000)
                                    ->disabled(fn ($get) => !$get('asistente'))
                                    ->dehydrated()
                                    ->rules([
                                        fn ($get) => function (string $attribute, $value, $fail) use ($get) {
                                            $asistenteDate = $get('asistente');
                                            if ($asistenteDate && Carbon::parse($value)->startOfDay()->lessThan(Carbon::parse($asistenteDate)->addYears(4)->startOfDay())) {
                                                $fail('La fecha de agregado debe ser al menos 4 años después de la fecha de asistente.');
                                            }
                                        }
                                    ]),

                                Forms\Components\DatePicker::make('asociado')
                                    ->label('Fecha de Asociado')
                                    ->live(debounce: 2000)
                                    ->disabled(fn ($get) => !$get('agregado'))
                                    ->dehydrated()
                                    ->rules([
                                        fn ($get) => function (string $attribute, $value, $fail) use ($get) {
                                            $agregadoDate = $get('agregado');
                                            if ($agregadoDate && Carbon::parse($value)->startOfDay()->lessThan(Carbon::parse($agregadoDate)->addYears(4)->startOfDay())) {
                                                $fail('La fecha de asociado debe ser al menos 4 años después de la fecha de agregado.');
                                            }
                                        },
                                        fn ($get) => function (string $attribute, $value, $fail) use ($get) {
                                            if (!static::hasValidTitleForHigherCategory($get('lastTitle'))) {
                                                $fail('Para la categoría de Asociado se requiere un título de Doctorado, Ph.D o similar.');
                                            }
                                        }
                                    ]),

                                    Forms\Components\DatePicker::make('titular')
                                    ->label('Fecha de Titular')
                                    ->live(debounce: 2000)
                                    ->disabled(fn ($get) => !$get('asociado'))
                                    ->dehydrated()
                                    ->rules([
                                        fn ($get) => function (string $attribute, $value, $fail) use ($get) {
                                            $asociadoDate = $get('asociado');
                                            if ($asociadoDate && Carbon::parse($value)->startOfDay()->lessThan(Carbon::parse($asociadoDate)->addYears(5)->startOfDay())) {
                                                $fail('La fecha de titular debe ser al menos 5 años después de la fecha de asociado.');
                                            }
                                        },
                                        fn ($get) => function (string $attribute, $value, $fail) use ($get) {
                                            if (!static::hasValidTitleForHigherCategory($get('lastTitle'))) {
                                                $fail('Para la categoría de Titular se requiere un título de Doctorado, Ph.D o similar.');
                                            }
                                        }
                                    ]),




                            ]),
                    ]),

                Forms\Components\Section::make('Información Adicional')
                    ->schema([
                        Forms\Components\TextInput::make('info')
                            ->label('Observaciones')
                            ->maxLength(255),

                            Forms\Components\Hidden::make('current_category') // Ocultamos current_category
    ->afterStateHydrated(function ($state, callable $get, callable $set) {
        // Actualizar current_category basado en las fechas ingresadas
        if ($get('titular')) {
            $set('current_category', 'Titular');
        } elseif ($get('asociado')) {
            $set('current_category', 'Asociado');
        } elseif ($get('agregado')) {
            $set('current_category', 'Agregado');
        } elseif ($get('asistente')) {
            $set('current_category', 'Asistente');
        } elseif ($get('instructor')) {
            $set('current_category', 'Instructor');
        } else {
            $set('current_category', '');
        }
    }),




                    
                                        ]),
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
                Tables\Columns\TextColumn::make('current_category')
                    ->label('Categoría Actual')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('preTitle')
                    ->label('Título de Pregrado')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('lastTitle')
                    ->label('Título Actual')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('instructor')
                    ->label('Instructor')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('asistente')
                    ->label('Asistente')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('agregado')
                    ->label('Agregado')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('asociado')
                    ->label('Asociado')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('titular')
                    ->label('Titular')
                    ->date()
                    ->sortable(),
                Tables\Columns\IconColumn::make('disable_assistant_rule')
                    ->label('Ascenso Inmediato')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('teacher_id')
        ->label('Docente')
        ->relationship('teacher', 'cdi')
        ->searchable()
        ->getOptionLabelFromRecordUsing(fn ($record) => $record->name.' '.$record->surName),

    Tables\Filters\SelectFilter::make('current_category')
        ->label('Categoría Actual')
        ->options([
            'Instructor' => 'Instructor',
            'Asistente' => 'Asistente',
            'Agregado' => 'Agregado',
            'Asociado' => 'Asociado',
            'Titular' => 'Titular'
        ]),

    Tables\Filters\Filter::make('date_range')
        ->form([
            Forms\Components\DatePicker::make('start_date'),
            Forms\Components\DatePicker::make('end_date'),
        ])
        ->query(function (Builder $query, array $data) {
            return $query
                ->when($data['start_date'],
                    fn($q) => $q->whereDate('created_at', '>=', $data['start_date']))
                ->when($data['end_date'],
                    fn($q) => $q->whereDate('created_at', '<=', $data['end_date']));
        }),
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
                        $pdf = Pdf::loadView('pdf.categories', [
                            'categories' => $records
                        ])->setPaper('a4', 'landscape');

                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->output();
                        }, 'categories_'.now()->format('Ymd_His').'.pdf');
                    })
                    ->requiresConfirmation()
            ]),

            ]);
    }


    protected function getTableQuery(): Builder
{
    $user = \Illuminate\Support\Facades\Auth::user();

    if (\Illuminate\Support\Facades\Auth::user()->hasRole('admin')) {
        return Category::query(); // Admin ve todo
    }

    if (\Illuminate\Support\Facades\Auth::user()->hasRole('area_manager')) {
        return Category::query()
            ->where('sede_id', $user->sede_id)
            ->where('area_id', $user->area_id); // Area Manager ve solo su sede y área
    }

    return Category::where('user_id', $user->id); // Teacher ve solo su propia información
}

protected function getTableActions(): array
{
    return [
        EditAction::make()
            ->visible(fn (Category $record): bool => \Illuminate\Support\Facades\Auth::user()->hasRole('admin') ||
                (\Illuminate\Support\Facades\Auth::user()->hasRole('area_manager') &&
                 $record->sede_id === \Illuminate\Support\Facades\Auth::user()->sede_id &&
                 $record->area_id === \Illuminate\Support\Facades\Auth::user()->area_id)), // Solo admin o area_manager con misma sede/área puede editar
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
