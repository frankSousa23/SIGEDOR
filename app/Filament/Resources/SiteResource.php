<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiteResource\Pages;
use App\Filament\Resources\SiteResource\RelationManagers;
use App\Models\Site;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use App\Models\Teacher;
use App\Filament\Forms\Components\Select2;

class SiteResource extends Resource
{
    protected static ?string $model = Site::class;
    protected static ?string $navigationLabel = 'Sede';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Asesoría Académica';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Selección de docente
                Forms\Components\Select::make('teacher_id')
                    ->relationship('teacher', 'cdi')
                    ->label('Docente')
                    ->options(function () {
                        return Teacher::whereDoesntHave('site')->pluck('cdi', 'id');
                    })
                    ->required(),

                // Sede (Input Text)
                TextInput::make('site')
                    ->label('Sede')
                    ->required()
                    ->placeholder('Ingrese la sede'),

                // Área Académica (Input Text)
                TextInput::make('area')
                    ->label('Área Académica')
                    ->required()
                    ->placeholder('Ingrese el área académica'),

                // Programa (Input Text)
                TextInput::make('program')
                    ->label('Programa')
                    ->required()
                    ->placeholder('Ingrese el programa'),

                // Unidad Curricular (Input Text)
                TextInput::make('uc') // Cambia 'uc' para reflejar múltiples
                    ->label('Unidad Curricular')
                    ->required()
                    ->placeholder('Ingrese la unidad curricular'),

                // Horas/Semana
                TextInput::make('weekHours')
                    ->label('Horas/Semana')
                    ->required()
                    ->numeric(),

                // Secciones
                TextInput::make('sections')
                    ->label('Secciones')
                    ->required()
                    ->numeric(),

                // Observaciones
                TextInput::make('info')
                    ->label('Observaciones')
                    ->maxLength(255)
                    ->default(null),
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
                Tables\Columns\TextColumn::make('site')
                    ->label('Sede')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('area')
                    ->label('Área Académica')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('program')
                    ->label('Programa')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('uc') // Mostrar las unidades curriculares seleccionadas
                    ->label('Unidad Curricular')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('weekHours')
                    ->label('Horas/Semana')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sections')
                    ->label('Secciones')
                    ->numeric()
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
            'index' => Pages\ListSites::route('/'),
            'create' => Pages\CreateSite::route('/create'),
            'edit' => Pages\EditSite::route('/{record}/edit'),
        ];
    }
}
