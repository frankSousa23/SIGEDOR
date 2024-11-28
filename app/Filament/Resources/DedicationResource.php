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
                    ->label('Cdi Docente')
                    ->options(function () {
                        return Teacher::whereDoesntHave('dedication')->pluck('cdi', 'id');
                    })
                    ->required(),
                Forms\Components\TextInput::make('dedication')
                    //->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('tcv')
                    //->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('mt')
                    //->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('tc')
                    //->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('ex')
                    //->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('info')
                    //->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('teacher_id')
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
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tcv')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('mt')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tc')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('exclusive')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('info')
                    ->searchable(),
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
