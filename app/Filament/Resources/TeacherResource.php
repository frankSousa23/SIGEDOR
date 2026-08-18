<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeacherResource\Pages;
use App\Models\Area;
use App\Models\Category;
use App\Models\Dedication;
use App\Models\Programa;
use App\Models\Sede;
use App\Models\Teacher;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

/**
 * Recurso Filament para Expediente y Gestión Integral del Docente.
 */
class TeacherResource extends Resource
{
    protected static ?string $model = Teacher::class;
    protected static ?string $modelLabel = 'Docente';
    protected static ?string $pluralModelLabel = 'Docentes';
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'Docentes';
    protected static ?string $navigationGroup = 'Gestión Docente';
    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::count();
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = Auth::user();

        if ($user && $user->isAreaManager()) {
            return $query->where('sede_id', $user->sede_id)
                ->where('area_id', $user->area_id);
        }

        return $query;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Vinculación de Usuario y Datos Personales')
                    ->schema([
                        Select::make('user_id')
                            ->label('Cuenta de Usuario')
                            ->relationship('user', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->unique(ignoreRecord: true)
                            ->columnSpanFull(),

                        Grid::make(3)
                            ->schema([
                                TextInput::make('cdi')
                                    ->label('Cédula de Identidad')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(20),

                                TextInput::make('name')
                                    ->label('Nombres')
                                    ->required()
                                    ->maxLength(100),

                                TextInput::make('surName')
                                    ->label('Apellidos')
                                    ->required()
                                    ->maxLength(100),
                            ]),

                        Grid::make(3)
                            ->schema([
                                Select::make('genre')
                                    ->label('Género')
                                    ->options([
                                        'F' => 'Femenino',
                                        'M' => 'Masculino',
                                    ])
                                    ->required(),

                                TextInput::make('phone')
                                    ->label('Teléfono de Contacto')
                                    ->tel()
                                    ->maxLength(25),

                                TextInput::make('email')
                                    ->label('Correo Electrónico')
                                    ->email()
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255),
                            ]),
                    ]),

                Section::make('Ubicación Institucional y Académica')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Select::make('sede_id')
                                    ->label('Sede')
                                    ->relationship('sede', 'nombre')
                                    ->required()
                                    ->searchable()
                                    ->preload(),

                                Select::make('area_id')
                                    ->label('Área Académica')
                                    ->relationship('area', 'nombre')
                                    ->required()
                                    ->searchable()
                                    ->preload(),

                                Select::make('programa_id')
                                    ->label('Programa / Carrera')
                                    ->relationship('programa', 'nombre')
                                    ->searchable()
                                    ->preload(),
                            ]),

                        Grid::make(3)
                            ->schema([
                                DatePicker::make('birthDate')
                                    ->label('Fecha de Nacimiento')
                                    ->maxDate(now()->subYears(18)),

                                DatePicker::make('datePromotion')
                                    ->label('Fecha de Promoción / Ingreso')
                                    ->maxDate(now()),

                                TextInput::make('asignaturePromotion')
                                    ->label('Cátedra o Asignatura de Promoción')
                                    ->maxLength(255),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('cdi')
                    ->label('Cédula')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('full_name')
                    ->label('Docente')
                    ->searchable(['name', 'surName'])
                    ->sortable(),

                TextColumn::make('sede.nombre')
                    ->label('Sede')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('area.nombre')
                    ->label('Área')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('category.current_category')
                    ->label('Categoría')
                    ->badge()
                    ->color('primary')
                    ->placeholder('Sin asignar')
                    ->sortable(),

                TextColumn::make('dedication.name')
                    ->label('Dedicación')
                    ->badge()
                    ->color('warning')
                    ->placeholder('Sin asignar')
                    ->sortable(),

                TextColumn::make('email')
                    ->label('Correo')
                    ->searchable(),

                TextColumn::make('phone')
                    ->label('Teléfono'),
            ])
            ->filters([
                SelectFilter::make('genre')
                    ->label('Género')
                    ->options([
                        'F' => 'Femenino',
                        'M' => 'Masculino',
                    ]),

                SelectFilter::make('sede_id')
                    ->label('Sede')
                    ->relationship('sede', 'nombre')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('area_id')
                    ->label('Área')
                    ->relationship('area', 'nombre')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('pdf_individual')
                    ->label('Expediente PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('danger')
                    ->action(function (Teacher $record) {
                        $pdf = Pdf::loadView('pdf.teacher-individual', ['teacher' => $record]);
                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            "expediente_docente_{$record->cdi}.pdf"
                        );
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('export')
                        ->label('Exportar Lista a PDF')
                        ->icon('heroicon-o-document-arrow-down')
                        ->action(function (Collection $records) {
                            $pdf = Pdf::loadView('pdf.teachers', [
                                'teachers' => $records,
                            ])->setPaper('a4', 'landscape');

                            return response()->streamDownload(
                                fn () => print($pdf->output()),
                                'docentes_' . now()->format('Ymd_His') . '.pdf'
                            );
                        })
                        ->requiresConfirmation(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTeachers::route('/'),
            'create' => Pages\CreateTeacher::route('/create'),
            'edit' => Pages\EditTeacher::route('/{record}/edit'),
        ];
    }
}
