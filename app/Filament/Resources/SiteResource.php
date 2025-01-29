<?php

namespace App\Filament\Resources;

use App\Models\Site;
use App\Models\Teacher;
use App\Filament\Resources\SiteResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use App\Models\SiteOption;
use App\Models\AreaOption;

class SiteResource extends Resource
{
    protected static ?string $model = Site::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationLabel = 'Sedes';
    protected static ?string $navigationGroup = 'Asignaciones';
    protected static ?int $navigationSort = 0;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información del Docente')
                    ->description('Seleccione el docente para asignar el site')
                    ->collapsible()
                    ->schema([
                        Select::make('teacher_id')
                            ->label('Docente')
                            ->options(
                                Teacher::with('user')->get()->mapWithKeys(fn ($teacher) => [
                                    $teacher->id => $teacher->user->name
                                ])
                            )
                            ->required(),
                    ]),

                Select::make('site_option_id')
                    ->label('Sede')
                    ->options(SiteOption::all()->pluck('name', 'id'))
                    ->required()
                    ->columnSpanFull(),

                Select::make('area_id')
                    ->label('Área')
                    ->relationship('area', 'name')
                    ->required(),

                Forms\Components\Select::make('program')
                    ->label('Programa')
                    ->options(Site::PROGRAMAS)
                    ->required()
                    ->searchable()
                    ->preload()
                    ->live()
                    ->native(false),

                Forms\Components\TextInput::make('uc')
                    ->label('Unidad Curricular')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('weekHours')
                    ->label('Horas Semanales')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(40),

                Forms\Components\TextInput::make('sections')
                    ->label('Secciones')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(10),

                Forms\Components\Textarea::make('info')
                    ->label('Información Adicional')
                    ->maxLength(255)
                    ->columnSpanFull(),

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
                Tables\Columns\TextColumn::make('siteOption.name')
                    ->label('Sede')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('teachers.cdi')
                    ->label('Docentes Asignados')
                    ->listWithLineBreaks()
                    ->limitList(3)
                    ->searchable(),

                Tables\Columns\TextColumn::make('area')
                    ->label('Área')
                    ->searchable(),

                Tables\Columns\TextColumn::make('program')
                    ->label('Programa')
                    ->searchable(),

                Tables\Columns\TextColumn::make('uc')
                    ->label('Unidad Curricular')
                    ->searchable(),

                Tables\Columns\TextColumn::make('weekHours')
                    ->label('Horas Semanales')
                    ->numeric(),

                Tables\Columns\TextColumn::make('sections')
                    ->label('Secciones')
                    ->numeric(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Activo')
                    ->boolean(),

                Tables\Columns\IconColumn::make('is_available')
                    ->label('Disponible')
                    ->boolean(),

                // Tables\Columns\TextColumn::make('teachers_count')->counts('teachers'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('area')
                    ->options([
                        'Ingeniería' => 'Ingeniería',
                        'Ciencias Básicas' => 'Ciencias Básicas',
                        'Humanidades' => 'Humanidades'
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

    public static function getModelLabel(): string
    {
        return 'Sedes';
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['teacher', 'area', 'siteOption'])
            ->where('site_option_id', auth()->user()->site_option_id);
    }

    public static function canCreate(): bool
    {
        return true;
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->hasRole('admin');
    }
}
