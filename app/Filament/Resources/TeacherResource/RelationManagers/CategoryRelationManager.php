<?php

namespace App\Filament\Resources\TeacherResource\RelationManagers;

use App\Filament\Resources\CategoryResource;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CategoryRelationManager extends RelationManager
{
    protected static string $relationship = 'categoryRecord';

    protected static ?string $title = 'Escalafón y Categoría';

    protected static ?string $modelLabel = 'Categoría';

    protected static ?string $pluralModelLabel = 'Escalafón Docente';

    public function form(Form $form): Form
    {
        return CategoryResource::form($form);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('current_category')
            ->columns([
                TextColumn::make('current_category')
                    ->label('Categoría Actual')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'Titular' => 'purple',
                        'Asociado' => 'info',
                        'Agregado' => 'success',
                        'Asistente' => 'warning',
                        'Instructor' => 'gray',
                        default => 'gray',
                    }),

                TextColumn::make('preTitle')
                    ->label('Título de Pregrado')
                    ->placeholder('No registrado'),

                TextColumn::make('lastTitle')
                    ->label('Último Título / Posgrado')
                    ->placeholder('No registrado'),

                TextColumn::make('instructor')
                    ->label('Instructor')
                    ->date('d/m/Y')
                    ->toggleable(),

                TextColumn::make('asistente')
                    ->label('Asistente')
                    ->date('d/m/Y')
                    ->toggleable(),

                TextColumn::make('agregado')
                    ->label('Agregado')
                    ->date('d/m/Y')
                    ->toggleable(),

                TextColumn::make('asociado')
                    ->label('Asociado')
                    ->date('d/m/Y')
                    ->toggleable(),

                TextColumn::make('titular')
                    ->label('Titular')
                    ->date('d/m/Y')
                    ->toggleable(),
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
