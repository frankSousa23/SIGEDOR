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
                    ->relationship(name: 'teacher', titleAttribute: 'cdi')
                    ->label('Docente')
                    ->options(function () {
                        return Teacher::whereDoesntHave('dedication')->pluck('cdi', 'id');
                    })
                    ->required(),
                Forms\Components\Select::make('dedication')
                    ->label('Dedicación')
                    ->options([
                        'tcv' => 'TCV',
                        'mt' => 'MT',
                        'tc' => 'TC',
                        'ex' => 'EX',
                    ])
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $set('hours', null);
                    }),
                Forms\Components\Select::make('hours')
                    ->label('Horas')
                    ->options(function (callable $get) {
                        $selectedOption = $get('dedication');
                        switch ($selectedOption) {
                            case 'tcv':
                                return [
                                    1 => '1',
                                    2 => '2',
                                    3 => '3',
                                    4 => '4',
                                    5 => '5',
                                    6 => '6',
                                    7 => '7',
                                ];
                            case 'mt':
                                return [
                                    18 => '18',
                                ];
                            case 'tc':
                                return [
                                    30 => '30',
                                ];
                            case 'ex':
                                return [
                                    35 => '35',
                                    36 => '36',
                                ];
                            default:
                                return [];
                        }
                    })
                    ->required()
                    ->rules(['in:1,2,3,4,5,6,7,18,30,35,36']),
                Forms\Components\TextInput::make('info')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('teacher.cdi')
                    ->label('Docente')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('teacher.name')->label('Nombres')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('teacher.surName')->label('Apellidos')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('dedication')
                    ->label('Dedicación')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('hours')
                    ->label('Horas')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
