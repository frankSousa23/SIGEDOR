<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use App\Models\Teacher;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Recurso Filament para Escalafón y Categorización Docente.
 */
class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'Categorías';
    protected static ?string $navigationGroup = 'Asignaciones';
    protected static ?string $modelLabel = 'Categoría';
    protected static ?string $pluralModelLabel = 'Categorías';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Información del Docente')
                    ->description('Seleccione el docente e ingrese sus títulos académicos')
                    ->schema([
                        Select::make('teacher_cdi')
                            ->label('Docente')
                            ->options(function ($record) {
                                return Teacher::all()->mapWithKeys(fn ($teacher) => [
                                    $teacher->cdi => "{$teacher->cdi} - {$teacher->name} {$teacher->surName}",
                                ]);
                            })
                            ->required()
                            ->searchable()
                            ->preload()
                            ->unique(ignoreRecord: true)
                            ->columnSpanFull(),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('preTitle')
                                    ->label('Título de Pregrado')
                                    ->placeholder('Ej: Licenciado en Matemáticas')
                                    ->maxLength(255),

                                TextInput::make('lastTitle')
                                    ->label('Último Título / Posgrado')
                                    ->placeholder('Ej: Doctor en Ciencias de la Educación')
                                    ->maxLength(255),
                            ]),

                        Select::make('current_category')
                            ->label('Categoría Actual')
                            ->options(Category::CATEGORIES)
                            ->required()
                            ->default('Instructor'),

                        Select::make('direct_promotion_rule')
                            ->label('Regla de Ascenso Directo por Mérito Académico')
                            ->options([
                                'none' => 'Ninguno (Base: Instructor)',
                                'specialty_master' => 'Especialización o Maestría (Ascenso a Asistente)',
                                'doctorate' => 'Doctorado (Ascenso a Agregado)',
                            ])
                            ->default('none')
                            ->helperText('Determina el ascenso directo y registro automático de fechas al crear el escalafón.'),
                    ]),

                Section::make('Fechas de Escalafón')
                    ->description('Registro histórico de ascensos en el escalafón universitario. Las fechas de ascenso determinan automáticamente la categoría actual y la antigüedad requerida por la normativa universitaria (mínimo 2 a 5 años entre escalafones).')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                DatePicker::make('instructor')->label('Fecha Instructor'),
                                DatePicker::make('asistente')->label('Fecha Asistente'),
                                DatePicker::make('agregado')->label('Fecha Agregado'),
                                DatePicker::make('asociado')->label('Fecha Asociado'),
                                DatePicker::make('titular')->label('Fecha Titular'),
                            ]),
                    ]),

                Section::make('Observaciones')
                    ->schema([
                        Textarea::make('info')
                            ->label('Información Adicional')
                            ->rows(3),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('teacher_cdi')
                    ->label('Cédula')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('teacher.full_name')
                    ->label('Docente')
                    ->searchable(['name', 'surName'])
                    ->sortable(),

                TextColumn::make('current_category')
                    ->label('Categoría Actual')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Titular' => 'success',
                        'Asociado' => 'primary',
                        'Agregado' => 'info',
                        'Asistente' => 'warning',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),

                TextColumn::make('preTitle')
                    ->label('Título de Pregrado')
                    ->limit(30)
                    ->searchable(),

                TextColumn::make('lastTitle')
                    ->label('Posgrado')
                    ->limit(30)
                    ->searchable(),

                TextColumn::make('titular')
                    ->label('Fecha Titular')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('current_category')
                    ->label('Categoría')
                    ->options(Category::CATEGORIES),

                SelectFilter::make('teacher_id')
                    ->label('Docente')
                    ->relationship('teacher', 'cdi')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->name . ' ' . $record->surName)
                    ->searchable()
                    ->preload(),

                Filter::make('date_range')
                    ->label('Rango de Fechas (Registro)')
                    ->form([
                        DatePicker::make('start_date')->label('Desde'),
                        DatePicker::make('end_date')->label('Hasta'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['start_date'], fn ($q) => $q->whereDate('created_at', '>=', $data['start_date']))
                            ->when($data['end_date'], fn ($q) => $q->whereDate('created_at', '<=', $data['end_date']));
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('pdf')
                    ->label('PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('danger')
                    ->action(function (Category $record) {
                        $pdf = Pdf::loadView('pdf.category', ['category' => $record]);
                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            "categoria_{$record->teacher_cdi}.pdf"
                        );
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('export_categories')
                        ->label('Exportar Seleccionados a PDF')
                        ->icon('heroicon-o-document-arrow-down')
                        ->action(function (Collection $records) {
                            $pdf = Pdf::loadView('pdf.categories', ['categories' => $records])
                                ->setPaper('a4', 'landscape');

                            return response()->streamDownload(
                                fn () => print($pdf->output()),
                                'reporte_categorias_' . now()->format('Ymd_His') . '.pdf'
                            );
                        })
                        ->requiresConfirmation(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $user = auth()->user();
        
        // El Jefe de Área solo ve categorías de docentes de su sede
        if ($user && $user->hasRole('area_manager') && $user->sede_id) {
            return $query->whereHas('teacher.user', function ($q) use ($user) {
                $q->where('sede_id', $user->sede_id);
            });
        }

        return $query;
    }
}
