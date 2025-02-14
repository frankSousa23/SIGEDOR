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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rule;
use Illuminate\Support\Carbon;
use Filament\Tables\Actions\EditAction;

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
                ->schema([
                    Forms\Components\Select::make('teacher_id')
    ->relationship('teacher', 'cdi')
    ->label('Docente')
    ->options(function () {
        return Teacher::pluck('cdi', 'id');
    })
    ->required()
    ->rules([
        Rule::unique('permissionsteachers', 'teacher_id')->ignore(request()->record) // Cambiado a 'permissionsteachers'
    ])
    ->validationMessages([
        'required' => 'Debe seleccionar un docente',
        'unique' => 'Este docente ya tiene un permiso asignado'
    ])
    ->reactive()
    ->columnSpan('full'),
                ]),

                Forms\Components\TextInput::make('memo_number')
    ->label('Nº Memo')
    ->required()
    ->unique(ignoreRecord: true)
    ->maxLength(255)
    ->rules([
        'required',
        'string',
        'max:255',
        'unique:permissionsteachers,memo_number,' . request()->record // Cambiado a 'permissionsteachers'
    ]),


    Forms\Components\Select::make('type')
    ->label('Tipo de Permiso')
    ->options(PermissionTeacher::TYPES)
    ->required()
    ->native(false),


                Forms\Components\TextInput::make('name')
                ->required()
                ->label('Nombre del Permiso')
                ->maxLength(255)
                ->rules([
                    'required',
                    'string',
                    'max:255'
                ]),


            Forms\Components\Select::make('status')
                ->options([
                    'pending' => 'Pendiente',
                    'approved' => 'Aprobado',
                    'rejected' => 'Rechazado'
                ])
                ->default('pending')
                ->required()
                ->label('Estado')
                ->native(false)
                ->rules([
                    'required',
                    'in:pending,approved,rejected'
                ]),

                Forms\Components\Select::make('duration_type')
                ->label('Duración')
                ->options([
                    'semestral' => 'Semestral (6 meses)',
                    'anual' => 'Anual (12 meses)',
                    'libre' => 'Libre'
                ])
                ->default('semestral')
                ->required()
                ->live()
                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                    $startDate = $get('start_date');
                    if ($startDate && in_array($state, ['semestral', 'anual'])) {
                        $endDate = Carbon::parse($startDate);
                        $months = $state === 'semestral' ? 6 : 12;
                        $set('end_date', $endDate->addMonths($months)->format('Y-m-d'));
                    }
                }),

                Forms\Components\DatePicker::make('start_date')
    ->required()
    ->label('Fecha de Inicio')
    ->format('Y-m-d')
    ->afterStateUpdated(function ($state, callable $set, callable $get) {
        $durationType = $get('duration_type');
        if ($state && in_array($durationType, ['semestral', 'anual'])) {
            $endDate = Carbon::parse($state);
            $months = $durationType === 'semestral' ? 6 : 12;
            $set('end_date', $endDate->addMonths($months)->format('Y-m-d'));
        }
    }),

                Forms\Components\DatePicker::make('end_date')
                ->required()
                ->label('Fecha de Fin')
                ->format('Y-m-d')
                ->rules([
                    'required',
                    'date',
                    'after_or_equal:start_date'
                ]),

                Forms\Components\Toggle::make('is_paid')
                ->label('Remunerado')
                ->default(false)
                ->inline(false)
                ->onColor('success')
                ->offColor('danger')
                ->onIcon('heroicon-o-check')
                ->offIcon('heroicon-o-x-mark'),

                Forms\Components\Textarea::make('description')
                ->label('Observaciones')
                ->maxLength(500)
                ->columnSpanFull()
                ->rules([
                    'nullable',
                    'string',
                    'max:500'
                ]),
            ])
            ->visible(fn () => auth()->user()->hasRole('admin'));
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


            Tables\Columns\TextColumn::make('memo_number')
                ->label('Nº Memo')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('type')
                ->label('Tipo')
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

                    Tables\Columns\TextColumn::make('duration_type')
                ->label('Duración')
                ->formatStateUsing(fn ($state) => match ($state) {
                    'semestral' => 'Semestral',
                    'anual' => 'Anual',
                    'libre' => 'Libre',
                    default => $state
                })
                ->badge()
                ->colors([
                    'primary' => 'semestral',
                    'success' => 'anual',
                    'warning' => 'libre',
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
                Tables\Columns\IconColumn::make('is_paid')
                    ->label('Remunerado')
                    ->boolean()
                    ->trueIcon('heroicon-o-check')
                    ->falseIcon('heroicon-o-x-mark')
                    ->trueColor('success')
                    ->falseColor('danger'),
                Tables\Columns\TextColumn::make('description')
                    ->label('Observaciones')
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


    protected function getTableQuery(): Builder
{
    $user = auth()->user();

    if ($user->hasRole('admin')) {
        return PermissionTeacher::query(); // Admin ve todo
    }

    if ($user->hasRole('area_manager')) {
        return PermissionTeacher::query()
            ->where('sede_id', $user->sede_id)
            ->where('area_id', $user->area_id); // Area Manager ve solo su sede y área
    }

    return PermissionTeacher::where('user_id', $user->id); // Teacher ve solo sus propios registros
}


protected function getTableActions(): array
{
    return [
        EditAction::make()
            ->visible(fn (PermissionTeacher $record): bool => auth()->user()->hasRole('admin') ||
                (auth()->user()->hasRole('area_manager') &&
                 $record->sede_id === auth()->user()->sede_id &&
                 $record->area_id === auth()->user()->area_id) ||
                (auth()->user()->hasRole('teacher') &&
                 $record->user_id === auth()->user()->id)), // Solo admin, area_manager con misma sede/área o teacher dueño puede editar
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
            'index' => Pages\ListPermissionsTeachers::route('/'),
            'create' => Pages\CreatePermissionTeacher::route('/create'),
            'edit' => Pages\EditPermissionTeacher::route('/{record}/edit'),
        ];
    }
}
