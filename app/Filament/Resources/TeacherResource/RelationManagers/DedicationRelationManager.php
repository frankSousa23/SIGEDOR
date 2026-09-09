<?php

namespace App\Filament\Resources\TeacherResource\RelationManagers;

use App\Filament\Resources\DedicationResource;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DedicationRelationManager extends RelationManager
{
    protected static string $relationship = 'dedicationRecord';

    protected static ?string $title = 'Dedicación y Carga Horaria';

    protected static ?string $modelLabel = 'Dedicación';

    protected static ?string $pluralModelLabel = 'Dedicación Docente';

    public function form(Form $form): Form
    {
        return DedicationResource::form($form);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->label('Modalidad')
                    ->badge()
                    ->color('warning'),

                TextColumn::make('type')
                    ->label('Código')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('hours')
                    ->label('Horas Semanales')
                    ->suffix(' hrs'),

                TextColumn::make('director')
                    ->label('Cargo Directivo')
                    ->placeholder('Ninguno'),

                TextColumn::make('studentNumber')
                    ->label('Estudiantes')
                    ->placeholder('0'),

                TextColumn::make('studentHours')
                    ->label('Horas Asesoría')
                    ->suffix(' hrs')
                    ->placeholder('0'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->slideOver(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
