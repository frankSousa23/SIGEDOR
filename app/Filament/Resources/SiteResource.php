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
                Forms\Components\Select::make('teacher_id')
                    ->relationship(name: 'teacher', titleAttribute: 'cdi')
                    ->label('Cédula de Identidad')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('site')
                    ->label('Sede')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('area')
                    ->label('Área Académica')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('program')
                    ->label('Programa')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('uc')
                    ->label('Unidad Curricular')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('weekHours')
                    ->label('Horas/Semana')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('sections')
                    ->label('Secciones')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('info')
                    ->label('Observaciones')
                    ->maxLength(255)
                    ->default(null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('teacher_id')
                    ->label('Cédula de Identidad')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('site')
                    ->label('Sede')
                    ->searchable(),
                Tables\Columns\TextColumn::make('area')
                    ->label('Área Académica')
                    ->searchable(),
                Tables\Columns\TextColumn::make('program')
                    ->label('Programa')
                    ->searchable(),
                Tables\Columns\TextColumn::make('uc')
                    ->label('Unidad Curricular')
                    ->searchable(),
                Tables\Columns\TextColumn::make('weekHours')
                    ->label('Horas/Semana')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sections')
                    ->label('Secciones')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('info')
                    ->label('Observaciones')
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
            'index' => Pages\ListSites::route('/'),
            'create' => Pages\CreateSite::route('/create'),
            'edit' => Pages\EditSite::route('/{record}/edit'),
        ];
    }
}
