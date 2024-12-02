<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiteResource\Pages;
use App\Models\Site;
use App\Models\Teacher;
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
    protected static ?int $navigationSort = 0;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('teacher_id')
                    ->relationship(
                        name: 'teachers',
                        titleAttribute: 'cdi',
                        modifyQueryUsing: fn ($query) => $query
                            ->whereNull('site_id')
                            ->where('has_site', false)
                            ->where('is_completed', true)
                            ->orderBy('cdi')
                    )
                    ->label('Docente (C.I.)')
                    ->required()
                    ->searchable(['cdi', 'name', 'surName'])
                    ->getSearchResultsUsing(
                        fn (string $search) => Teacher::query()
                            ->whereNull('site_id')
                            ->where('has_site', false)
                            ->where('is_completed', true)
                            ->where(function ($query) use ($search) {
                                $query->where('cdi', 'like', "%{$search}%")
                                    ->orWhere('name', 'like', "%{$search}%")
                                    ->orWhere('surName', 'like', "%{$search}%");
                            })
                            ->limit(50)
                            ->get()
                            ->mapWithKeys(fn ($teacher) => [$teacher->id => $teacher->cdi])
                            ->toArray()
                    )
                    ->getOptionLabelUsing(fn ($value): ?string => Teacher::find($value)?->cdi)
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                        if ($state) {
                            $teacher = Teacher::find($state);
                            if ($teacher) {
                                $teacher->update(['has_site' => true]);
                            }
                        }
                    }),

                Forms\Components\Select::make('name')
                    ->label('Site')
                    ->required()
                    ->options([
                        'Álgebra Lineal' => 'Álgebra Lineal',
                        'Cálculo I' => 'Cálculo I',
                        'Cálculo II' => 'Cálculo II',
                        'Física I' => 'Física I',
                        'Física II' => 'Física II',
                    ])
                    ->searchable()
                    ->allowHtml()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->label('Nuevo Site')
                            ->maxLength(255),
                    ])
                    ->createOptionUsing(function (array $data) {
                        return $data['name'];
                    }),

                Forms\Components\Select::make('area')
                    ->label('Área')
                    ->required()
                    ->options([
                        'Administración' => 'Administración',
                        'Ingeniería' => 'Ingeniería',
                        'Humanidades' => 'Humanidades',
                        'Ciencias' => 'Ciencias',
                        'Educación' => 'Educación',
                    ])
                    ->searchable()
                    ->allowHtml()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->label('Nueva Área')
                            ->maxLength(255),
                    ])
                    ->createOptionUsing(function (array $data) {
                        return $data['name'];
                    }),

                Forms\Components\Select::make('program')
                    ->label('Programa')
                    ->required()
                    ->options([
                        'Administración' => 'Administración',
                        'Contaduría' => 'Contaduría',
                        'Ingeniería Civil' => 'Ingeniería Civil',
                        'Ingeniería de Sistemas' => 'Ingeniería de Sistemas',
                        'Derecho' => 'Derecho',
                        'Educación' => 'Educación',
                    ])
                    ->searchable()
                    ->allowHtml()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->label('Nuevo Programa')
                            ->maxLength(255),
                    ])
                    ->createOptionUsing(function (array $data) {
                        return $data['name'];
                    }),

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
                Tables\Columns\TextColumn::make('teachers.cdi')
                    ->label('Docente (C.I.)')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Site')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('area')
                    ->label('Área')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('program')
                    ->label('Programa')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('uc')
                    ->label('Unidad Curricular')
                    ->searchable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Activo')
                    ->boolean(),

                Tables\Columns\IconColumn::make('is_available')
                    ->label('Disponible')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
