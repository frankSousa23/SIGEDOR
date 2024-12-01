<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;
    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationLabel = 'Categorías';
    protected static ?string $navigationGroup = 'Gestión Docente';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('teacher_id')
                    ->relationship('teacher', 'name')
                    ->required()
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
                    })
                    ->label('Docente'),

                Forms\Components\Select::make('category')
                    ->options([
                        'Instructor' => 'Instructor',
                        'Asistente' => 'Asistente',
                        'Agregado' => 'Agregado',
                        'Asociado' => 'Asociado',
                        'Titular' => 'Titular'
                    ])
                    ->required()
                    ->searchable()
                    ->label('Categoría'),

                Forms\Components\Select::make('title')
                    ->options([
                        'TSU' => 'TSU',
                        'Licenciado' => 'Licenciado',
                        'Especialista' => 'Especialista',
                        'Magister' => 'Magister',
                        'Doctor' => 'Doctor'
                    ])
                    ->required()
                    ->searchable()
                    ->label('Título'),

                Forms\Components\DatePicker::make('start_date')
                    ->required()
                    ->label('Fecha de Inicio')
                    ->format('Y-m-d'),

                Forms\Components\Textarea::make('info')
                    ->label('Observaciones')
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

                Tables\Columns\TextColumn::make('category')
                    ->label('Categoría')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('start_date')
                    ->label('Fecha de Inicio')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('info')
                    ->label('Observaciones')
                    ->limit(50),
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }    
}
