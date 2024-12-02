<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DedicationResource\Pages;
use App\Models\Dedication;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DedicationResource extends Resource
{
    protected static ?string $model = Dedication::class;
    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationLabel = 'Dedicaciones';
    protected static ?string $navigationGroup = 'Asignaciones';
    protected static ?int $navigationSort = 22;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('teacher_id')
                    ->relationship('teacher', 'name')
                    ->required()
                    ->label('Docente')
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
                    }),
                
                Forms\Components\Select::make('dedication')
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
                    ->searchable(),

                Forms\Components\Select::make('hours')
                    ->label('Horas')
                    ->options(function (callable $get) {
                        return Dedication::getValidHours($get('dedication'));
                    })
                    ->required()
                    ->searchable(),

                Forms\Components\Select::make('director')
                    ->options([
                        Dedication::DIRECTOR_COORDINATOR => 'Coordinador',
                        Dedication::DIRECTOR_DEPARTMENT_HEAD => 'Jefe de Departamento',
                        Dedication::DIRECTOR_DEAN => 'Decano'
                    ])
                    ->label('Cargo Directivo')
                    ->nullable()
                    ->searchable()
                    ->helperText('Seleccione si tiene algún cargo directivo'),

                Forms\Components\TextInput::make('studentNumber')
                    ->numeric()
                    ->label('Número de Estudiantes')
                    ->helperText('Número de estudiantes en asesoría (1-100)')
                    ->minValue(1)
                    ->maxValue(100)
                    ->nullable()
                    ->reactive()
                    ->afterStateUpdated(fn ($state, callable $set) => 
                        $set('studentHours', $state ? null : null)
                    ),

                Forms\Components\TextInput::make('studentHours')
                    ->numeric()
                    ->label('Horas de Asesoría')
                    ->helperText('Número de horas dedicadas a asesorías (1-100)')
                    ->minValue(1)
                    ->maxValue(100)
                    ->nullable()
                    ->hidden(fn (callable $get) => !$get('studentNumber')),

                Forms\Components\Textarea::make('info')
                    ->label('Observaciones')
                    ->nullable()
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

                Tables\Columns\TextColumn::make('dedication')
                    ->label('Dedicación')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'TCV' => 'Tiempo Convencional',
                        'MT' => 'Medio Tiempo',
                        'TC' => 'Tiempo Completo',
                        'EX' => 'Exclusiva',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('hours')
                    ->label('Horas'),

                Tables\Columns\TextColumn::make('director')
                    ->label('Cargo Directivo')
                    ->default('Sin Cargo'),

                Tables\Columns\TextColumn::make('studentNumber')
                    ->label('Estudiantes')
                    ->numeric(),

                Tables\Columns\TextColumn::make('studentHours')
                    ->label('Horas Asesoría')
                    ->numeric(),
            ])
            ->filters([
                //
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
            'index' => Pages\ListDedications::route('/'),
            'create' => Pages\CreateDedication::route('/create'),
            'edit' => Pages\EditDedication::route('/{record}/edit'),
        ];
    }    
}
