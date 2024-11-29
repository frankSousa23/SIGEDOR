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
use App\Models\Teacher;

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
                    ->label('Docente')
                    ->options(function () {
                        return Teacher::whereDoesntHave('category')->pluck('cdi', 'id');
                    })
                    ->required()
                    ->reactive(),
                Forms\Components\DatePicker::make('instructor')
                    ->label('Fecha de Instructor')
                    ->required()
                    ->reactive(),
                Forms\Components\DatePicker::make('asistente')
                    ->label('Fecha de Asistente')
                    ->after(fn ($get) => $get('instructor') ? now()->parse($get('instructor'))->addYears(2) : null)
                    ->disabled(fn ($get) => !$get('instructor'))
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $get, callable $set) {
                        if ($state) {
                            $instructorDate = $get('instructor');
                            if ($instructorDate && now()->parse($state)->lessThan(now()->parse($instructorDate)->addYears(2))) {
                                session()->flash('error', 'La fecha seleccionada debe ser al menos 2 años después de la fecha del instructor.');
                            }
                        }
                    }),
                Forms\Components\DatePicker::make('agregado')
                    ->label('Fecha de Agregado')
                    ->after(fn ($get) => $get('asistente') ? now()->parse($get('asistente'))->addYears(4) : null)
                    ->disabled(fn ($get) => !$get('asistente'))
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $get, callable $set) {
                        if ($state) {
                            $asistenteDate = $get('asistente');
                            if ($asistenteDate && now()->parse($state)->lessThan(now()->parse($asistenteDate)->addYears(4))) {
                                session()->flash('error', 'La fecha seleccionada debe ser al menos 4 años después de la fecha de asistente.');
                            }
                        }
                    }),
                Forms\Components\DatePicker::make('asociado')
                    ->label('Fecha de Asociado')
                    ->after(fn ($get) => $get('agregado') ? now()->parse($get('agregado'))->addYears(4) : null)
                    ->disabled(fn ($get) => !$get('agregado'))
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $get, callable $set) {
                        if ($state) {
                            $agregadoDate = $get('agregado');
                            if ($agregadoDate && now()->parse($state)->lessThan(now()->parse($agregadoDate)->addYears(4))) {
                                session()->flash('error', 'La fecha seleccionada debe ser al menos 4 años después de la fecha de agregado.');
                            }
                        }
                    }),
                Forms\Components\DatePicker::make('titular')
                    ->label('Fecha de Titular')
                    ->after(fn ($get) => $get('asociado') ? now()->parse($get('asociado'))->addYears(5) : null)
                    ->disabled(fn ($get) => !$get('asociado'))
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $get, callable $set) {
                        if ($state) {
                            $asociadoDate = $get('asociado');
                            if ($asociadoDate && now()->parse($state)->lessThan(now()->parse($asociadoDate)->addYears(5))) {
                                session()->flash('error', 'La fecha seleccionada debe ser al menos 5 años después de la fecha de asociado.');
                            }
                        }
                    }),
                Forms\Components\TextInput::make('info')
                    ->maxLength(255),
            ]);
    }

    public static function validationMessages(): array
    {
        return [
            'asistente.after' => 'La fecha seleccionada debe ser al menos 2 años después de la fecha del instructor.',
            'agregado.after' => 'La fecha seleccionada debe ser al menos 4 años después de la fecha de asistente.',
            'asociado.after' => 'La fecha seleccionada debe ser al menos 4 años después de la fecha de agregado.',
            'titular.after' => 'La fecha seleccionada debe ser al menos 5 años después de la fecha de asociado.',
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('teacher.cdi')
                    ->label('Docente')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('teacher.name')->label('Nombres')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('teacher.surName')->label('Apellidos')
                    ->searchable()
                    ->sortable(),
                //Tables\Columns\TextColumn::make('category')
                  //  ->searchable()
                    //->sortable(),
                Tables\Columns\TextColumn::make('instructor')
                    ->date()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('asistente')
                    ->date()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('agregado')
                    ->date()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('asociado')
                    ->date()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('titular')
                    ->date()
                    ->searchable()
                    ->sortable(),
                //Tables\Columns\TextColumn::make('info')
                  //  ->searchable(),
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
