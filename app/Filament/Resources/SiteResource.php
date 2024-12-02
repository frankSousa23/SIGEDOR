<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiteResource\Pages;
use App\Models\Site;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SiteResource extends Resource
{
    protected static ?string $model = Site::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationLabel = 'Sedes';
    protected static ?string $navigationGroup = 'Asignaciones';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Nombre')
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                Forms\Components\Select::make('area')
                    ->options([
                        'Administración' => 'Administración',
                        'Ingeniería' => 'Ingeniería',
                        'Humanidades' => 'Humanidades',
                        'Ciencias' => 'Ciencias',
                        'Educación' => 'Educación',
                    ])
                    ->required()
                    ->searchable()
                    ->label('Área'),

                Forms\Components\TextInput::make('program')
                    ->label('Programa')
                    ->maxLength(255),

                Forms\Components\TextInput::make('uc')
                    ->label('Unidades de Crédito')
                    ->maxLength(255),

                Forms\Components\TextInput::make('weekHours')
                    ->label('Horas Semanales')
                    ->numeric()
                    ->minValue(0),

                Forms\Components\TextInput::make('sections')
                    ->label('Secciones')
                    ->numeric()
                    ->minValue(0),

                Forms\Components\Textarea::make('info')
                    ->label('Información Adicional')
                    ->maxLength(255),

                Forms\Components\Toggle::make('is_active')
                    ->label('Activo')
                    ->default(true),

                Forms\Components\Toggle::make('is_available')
                    ->label('Disponible')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('area')
                    ->label('Área')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('program')
                    ->label('Programa')
                    ->searchable(),

                Tables\Columns\TextColumn::make('weekHours')
                    ->label('Horas Semanales')
                    ->sortable(),

                Tables\Columns\TextColumn::make('sections')
                    ->label('Secciones')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Activo')
                    ->boolean(),

                Tables\Columns\IconColumn::make('is_available')
                    ->label('Disponible')
                    ->boolean(),

                Tables\Columns\TextColumn::make('teachers_count')
                    ->label('Docentes')
                    ->counts('teachers'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('area')
                    ->options([
                        'Administración' => 'Administración',
                        'Ingeniería' => 'Ingeniería',
                        'Humanidades' => 'Humanidades',
                        'Ciencias' => 'Ciencias',
                        'Educación' => 'Educación',
                    ])
                    ->label('Área'),
                
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Activo'),
                
                Tables\Filters\TernaryFilter::make('is_available')
                    ->label('Disponible'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
