<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DedicationResource\Pages;
use App\Filament\Resources\DedicationResource\RelationManagers;
use App\Models\Dedication;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\Relationship;
use App\Models\Teacher;

class DedicationResource extends Resource
{
    protected static ?string $model = Dedication::class;
    protected static ?string $navigationLabel = 'Dedicación';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Asesoría Académica';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('teacher_id')
                    ->relationship('teacher', 'name')
                    ->required()
                    ->label('Docente')
                    ->searchable()
                    ->preload(),
                
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
                    }),

                Forms\Components\Select::make('hours')
                    ->label('Horas')
                    ->options(function (callable $get) {
                        $dedication = $get('dedication');
                        
                        switch ($dedication) {
                            case 'TCV':
                                return array_combine(range(1, 17), range(1, 17));
                            case 'MT':
                                return ['18' => '18'];
                            case 'TC':
                                return ['30' => '30'];
                            case 'EX':
                                return [
                                    '35' => '35',
                                    '36' => '36'
                                ];
                            default:
                                return [];
                        }
                    })
                    ->required(),

                Forms\Components\Select::make('director')
                    ->options([
                        '' => 'Sin Cargo Directivo',
                        'Coordinador' => 'Coordinador',
                        'Jefe de Departamento' => 'Jefe de Departamento',
                        'Decano' => 'Decano'
                    ])
                    ->label('Cargo Directivo')
                    ->nullable()
                    ->helperText('Seleccione si tiene algún cargo directivo'),

                Forms\Components\TextInput::make('studentNumber')
                    ->numeric()
                    ->label('Asesorías')
                    ->helperText('Número de estudiantes en asesoría (1-100)')
                    ->minValue(1)
                    ->maxValue(100)
                    ->nullable(),
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
                    })
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('hours')
                    ->label('Horas')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('director')
                    ->label('Cargo Directivo')
                    ->default('Sin Cargo')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('studentNumber')
                    ->label('Asesorías')
                    ->default('0')
                    ->sortable(),
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
