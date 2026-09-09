<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeacherResource\Pages;
use App\Filament\Resources\TeacherResource\RelationManagers;
use App\Models\Report;
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
 *
 * Características arquitecturales:
 * - Asistente de captura por etapas (Wizard en 3 pasos: Personales, Adscripción, Cátedra/Escalafón).
 * - Expediente 360° unificado mediante 4 RelationManagers (Permisos, Categoría, Dedicación, Reportes).
 * - Multi-tenant Query Scoping: Aislamiento por Sede territorial para Jefes de Área y visión global para Admin.
 * - Emisión rápida de Constancias de Trabajo oficiales UNERG en PDF con código de autenticidad.
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
        $query = parent::getEloquentQuery()->with(['sede', 'area', 'category', 'dedication']);
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
                            ->columnSpanFull()
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                if ($state) {
                                    $user = User::find($state);
                                    if ($user) {
                                        $parts = explode(' ', trim($user->name), 2);
                                        $set('name', $parts[0] ?? '');
                                        $set('surName', $parts[1] ?? '');
                                        $set('email', $user->email);
                                        if ($user->sede_id) {
                                            $set('sede_id', $user->sede_id);
                                        }
                                        if ($user->area_id) {
                                            $set('area_id', $user->area_id);
                                        }
                                    }
                                }
                            }),

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
                                    ->preload()
                                    ->live(),

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
                    ]),

                Section::make('Ingreso y Promoción Universitaria')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                DatePicker::make('birthDate')
                                    ->label('Fecha de Nacimiento')
                                    ->required()
                                    ->maxDate(now()->subYears(18)),

                                DatePicker::make('datePromotion')
                                    ->label('Fecha de Promoción / Ingreso')
                                    ->required()
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
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Cédula copiada al portapapeles'),

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
                    ->color(fn (?string $state): string => match ($state) {
                        'Titular' => 'purple',
                        'Asociado' => 'info',
                        'Agregado' => 'success',
                        'Asistente' => 'warning',
                        'Instructor' => 'gray',
                        default => 'gray',
                    })
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
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Correo copiado al portapapeles')
                    ->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('phone')
                    ->label('Teléfono')
                    ->toggleable(isToggledHiddenByDefault: true),
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

                Filter::make('created_at')
                    ->label('Rango de Registro')
                    ->form([
                        DatePicker::make('from')->label('Registrado desde'),
                        DatePicker::make('to')->label('Registrado hasta'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'], fn ($q) => $q->whereDate('created_at', '>=', $data['from']))
                            ->when($data['to'], fn ($q) => $q->whereDate('created_at', '<=', $data['to']));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->slideOver(),
                Tables\Actions\EditAction::make(),
                Action::make('generate_certificate')
                    ->label('Constancia')
                    ->icon('heroicon-o-document-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Emitir Constancia de Trabajo Oficial')
                    ->modalDescription('¿Desea generar y registrar una nueva Constancia de Trabajo oficial para este docente?')
                    ->modalSubmitActionLabel('Emitir y Descargar PDF')
                    ->action(function (Teacher $record) {
                        $memoNumber = 'CONST-'.now()->format('Ymd').'-'.strtoupper(substr($record->cdi, -4));
                        $report = Report::create([
                            'teacher_cdi' => $record->cdi,
                            'memoNumber' => $memoNumber,
                            'typeReport' => 'Constancia de Trabajo',
                            'report' => "Se hace constar formalmente que el ciudadano(a) {$record->full_name}, titular de la C.I. {$record->cdi}, presta sus servicios académicos como personal docente ordinario en el área {$record->area?->nombre} adscrito a la sede {$record->sede?->nombre}.",
                            'sede_id' => $record->sede_id,
                            'area_id' => $record->area_id,
                            'category_id' => $record->category_id,
                            'dedication_id' => $record->dedication_id,
                            'status' => 'issued',
                        ]);

                        $pdf = Pdf::loadView('pdf.report', ['report' => $report]);

                        return response()->streamDownload(
                            fn () => print ($pdf->output()),
                            "constancia_trabajo_{$record->cdi}.pdf"
                        );
                    }),
                Action::make('pdf_individual')
                    ->label('Expediente PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('danger')
                    ->action(function (Teacher $record) {
                        $pdf = Pdf::loadView('pdf.teacher-individual', ['teacher' => $record]);

                        return response()->streamDownload(
                            fn () => print ($pdf->output()),
                            "expediente_docente_{$record->cdi}.pdf"
                        );
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('export_csv')
                        ->label('Exportar a CSV / Excel')
                        ->icon('heroicon-o-table-cells')
                        ->color('success')
                        ->action(function (Collection $records) {
                            return response()->streamDownload(function () use ($records) {
                                $handle = fopen('php://output', 'w');
                                fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM
                                fputcsv($handle, ['Cédula', 'Nombres', 'Apellidos', 'Correo', 'Teléfono', 'Sede', 'Área', 'Programa', 'Categoría', 'Dedicación']);
                                $sanitizeCell = static function ($value): string {
                                    $str = (string) ($value ?? '');
                                    if (preg_match('/^[=\+\-@\t\r]/', $str)) {
                                        return "'".$str;
                                    }

                                    return $str;
                                };

                                foreach ($records as $teacher) {
                                    fputcsv($handle, array_map($sanitizeCell, [
                                        $teacher->cdi,
                                        $teacher->name,
                                        $teacher->surName,
                                        $teacher->email,
                                        $teacher->phone ?? '',
                                        $teacher->sede?->nombre ?? '',
                                        $teacher->area?->nombre ?? '',
                                        $teacher->programa?->nombre ?? '',
                                        $teacher->category?->current_category ?? 'Sin Asignar',
                                        $teacher->dedication?->name ?? 'Sin Asignar',
                                    ]));
                                }
                                fclose($handle);
                            }, 'docentes_'.now()->format('Ymd_His').'.csv', [
                                'Content-Type' => 'text/csv; charset=UTF-8',
                            ]);
                        }),
                    Tables\Actions\BulkAction::make('export')
                        ->label('Exportar Lista a PDF')
                        ->icon('heroicon-o-document-arrow-down')
                        ->action(function (Collection $records) {
                            $pdf = Pdf::loadView('pdf.teachers', [
                                'teachers' => $records,
                            ])->setPaper('a4', 'landscape');

                            return response()->streamDownload(
                                fn () => print ($pdf->output()),
                                'docentes_'.now()->format('Ymd_His').'.pdf'
                            );
                        })
                        ->requiresConfirmation(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ReportsRelationManager::class,
            RelationManagers\PermissionsRelationManager::class,
            RelationManagers\CategoryRelationManager::class,
            RelationManagers\DedicationRelationManager::class,
        ];
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
