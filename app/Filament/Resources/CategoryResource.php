<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;
    protected static ?string $navigationLabel = 'Categoría';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Asesoría Académica';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('teacher_id')
                    ->relationship('teacher', 'cdi')
                    ->required(),
                    //->numeric(),
                Forms\Components\TextInput::make('category')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('instructor'),
                    //->required(),
                Forms\Components\DatePicker::make('asistente'),
                    //->required(),
                Forms\Components\DatePicker::make('agregado'),
                    //->required(),
                Forms\Components\DatePicker::make('asociado'),
                    //->required(),
                Forms\Components\DatePicker::make('titular'),
                    //->required(),
                Forms\Components\TextInput::make('info')
                    //->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('teacher.cdi')
                    //->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('teacher.name')->label('Nombres'),
                Tables\Columns\TextColumn::make('teacher.surName')->label('Apellidos'),
                Tables\Columns\TextColumn::make('category')
                    ->searchable(),
                Tables\Columns\TextColumn::make('instructor')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('asistente')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('agregado')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('asociado')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('titular')
                    ->date()
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
