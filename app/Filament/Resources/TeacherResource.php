<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeacherResource\Pages;
use App\Filament\Resources\TeacherResource\RelationManagers;
use App\Models\Teacher;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class TeacherResource extends Resource
{
    protected static ?string $model = Teacher::class;
    protected static ?string $modelLabel = 'Docente';
    protected static ?string $pluralModelLabel = 'Docentes';
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'Docentes';
    protected static ?string $navigationGroup = 'Gestión Docente';
    protected static ?int $navigationSort = -1;

    public static function getNavigationGroup(): ?string
    {
        return 'Gestión Docente';
    }

    public static function getNavigationBadge(): ?string
    {
        if (auth()->user()->hasRole(['admin', 'area_manager'])) {
            return static::getEloquentQuery()->count();
        }
        return null;
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        
        if (auth()->user()->hasRole('admin')) {
            return $query;
        }

        if (auth()->user()->hasRole('area_manager')) {
            return $query->where('site_id', auth()->user()->site_id);
        }

        if (auth()->user()->hasRole('teacher')) {
            return $query->where('cdi', auth()->user()->cdi);
        }

        return $query->where('id', null); // No ver nada si no tiene rol
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('cdi')
                    ->label('Cédula de Identidad')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                Forms\Components\TextInput::make('name')
                    ->label('Nombres')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('surName')
                    ->label('Apellidos')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('genre')
                    ->label('Género')
                    ->options([
                        'F' => 'Femenino',
                        'M' => 'Masculino'
                    ])
                    ->required(),

                Forms\Components\TextInput::make('phone')
                    ->label('Teléfono')
                    ->tel()
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('email')
                    ->label('Correo Electrónico')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                Forms\Components\DatePicker::make('birthDate')
                    ->label('Fecha de Nacimiento')
                    ->required()
                    ->maxDate(now()->subYears(18)),

                Forms\Components\DatePicker::make('datePromotion')
                    ->label('Fecha de Promoción')
                    ->required(),

                Forms\Components\TextInput::make('asignaturePromotion')
                    ->label('Asignatura de Promoción')
                    ->maxLength(255),

                // Relaciones principales
                Forms\Components\Select::make('site_id')
                    ->relationship('site', 'name')
                    ->label('Sede')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->label('Nombre de la Sede')
                            ->maxLength(255),
                        Forms\Components\Select::make('area')
                            ->required()
                            ->options([
                                'Administración' => 'Administración',
                                'Ingeniería' => 'Ingeniería',
                                'Humanidades' => 'Humanidades',
                                'Ciencias' => 'Ciencias',
                                'Educación' => 'Educación',
                            ])
                            ->searchable()
                            ->allowHtml()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->label('Nueva Área')
                                    ->maxLength(255),
                            ])
                            ->createOptionUsing(function (array $data) {
                                return $data['name'];
                            })
                            ->createOptionAction(function (Forms\Components\Actions\Action $action) {
                                return $action
                                    ->modalHeading('Crear nueva área')
                                    ->modalButton('Agregar área');
                            }),
                    ])
                    ->createOptionUsing(function (array $data) {
                        return \App\Models\Site::create([
                            'name' => $data['name'],
                            'area' => $data['area'],
                        ])->id;
                    })
                    ->createOptionAction(function (Forms\Components\Actions\Action $action) {
                        return $action
                            ->modalHeading('Crear nueva sede')
                            ->modalButton('Agregar sede');
                    }),

                Forms\Components\Select::make('category_id')
                    ->relationship('category', 'current_category')
                    ->label('Categoría')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('current_category')
                            ->required()
                            ->label('Nombre de la Categoría')
                            ->maxLength(255),
                    ]),

                Forms\Components\Select::make('dedication_id')
                    ->relationship('dedication', 'name')
                    ->label('Dedicación')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        Forms\Components\Select::make('type')
                            ->required()
                            ->label('Tipo')
                            ->options([
                                'TCV' => 'Tiempo Convencional',
                                'MT' => 'Medio Tiempo',
                                'TC' => 'Tiempo Completo',
                                'EX' => 'Exclusiva'
                            ])
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(fn ($state, callable $set) => 
                                $set('name', match ($state) {
                                    'TCV' => 'Tiempo Convencional',
                                    'MT' => 'Medio Tiempo',
                                    'TC' => 'Tiempo Completo',
                                    'EX' => 'Exclusiva',
                                    default => null,
                                })
                            ),
                        Forms\Components\Hidden::make('name'),
                        Forms\Components\Select::make('hours')
                            ->required()
                            ->label('Horas')
                            ->options(function (callable $get) {
                                $type = $get('type');
                                
                                return match ($type) {
                                    'TCV' => array_combine(
                                        range(1, 17),
                                        array_map(fn ($hour) => "{$hour} Horas", range(1, 17))
                                    ),
                                    'MT' => ['18' => '18 Horas'],
                                    'TC' => ['30' => '30 Horas'],
                                    'EX' => [
                                        '35' => '35 Horas',
                                        '36' => '36 Horas'
                                    ],
                                    default => [],
                                };
                            })
                            ->searchable(),
                    ])
                    ->createOptionUsing(function (array $data) {
                        return \App\Models\Dedication::create([
                            'name' => $data['name'],
                            'type' => $data['type'],
                            'hours' => $data['hours'],
                        ])->id;
                    })
                    ->createOptionAction(function (Forms\Components\Actions\Action $action) {
                        return $action
                            ->modalHeading('Crear nueva dedicación')
                            ->modalButton('Agregar dedicación');
                    }),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('cdi')
                    ->label('Cédula')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombres')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('surName')
                    ->label('Apellidos')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Correo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Teléfono'),
                Tables\Columns\TextColumn::make('birthDate')
                    ->label('Fecha de Nacimiento')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('datePromotion')
                    ->label('Fecha de Promoción')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('site.name')
                    ->label('Sede')
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.current_category')
                    ->label('Categoría')
                    ->sortable(),
                Tables\Columns\TextColumn::make('dedication.name')
                    ->label('Dedicación')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_completed')
                    ->label('Completado')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('site')
                    ->relationship('site', 'name')
                    ->label('Sede'),
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'current_category')
                    ->label('Categoría'),
                Tables\Filters\SelectFilter::make('dedication')
                    ->relationship('dedication', 'name')
                    ->label('Dedicación'),
                Tables\Filters\SelectFilter::make('genre')
                    ->options([
                        'F' => 'Femenino',
                        'M' => 'Masculino',
                    ])
                    ->label('Género'),
                Tables\Filters\Filter::make('is_completed')
                    ->query(fn (Builder $query): Builder => $query->where('is_completed', true))
                    ->label('Completados')
                    ->toggle(),
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
                            // Aquí implementaremos la exportación a PDF
                            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.teachers', [
                                'teachers' => $records
                            ]);
                            
                            return response()->streamDownload(function () use ($pdf) {
                                echo $pdf->output();
                            }, 'docentes.pdf');
                        })
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
            'index' => Pages\ListTeachers::route('/'),
            'create' => Pages\CreateTeacher::route('/create'),
            'edit' => Pages\EditTeacher::route('/{record}/edit'),
        ];
    }    
}
