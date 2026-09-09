<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReportResource\Pages;
use App\Models\Report;
use App\Models\Sede;
use App\Models\Teacher;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Recurso Filament para Emisión de Informes Oficiales y Memorandos.
 */
class ReportResource extends Resource
{
    protected static ?string $model = Report::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';

    protected static ?string $navigationLabel = 'Reportes y Memos';

    protected static ?string $navigationGroup = 'Gestión Reportes';

    protected static ?string $modelLabel = 'Reporte';

    protected static ?string $pluralModelLabel = 'Reportes';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Identificación del Documento')
                    ->schema([
                        Select::make('teacher_cdi')
                            ->label('Docente')
                            ->options(function () {
                                $user = auth()->user();

                                return Teacher::query()
                                    ->when($user && $user->hasRole('teacher') && ! $user->hasAnyRole(['admin', 'area_manager']), function ($query) use ($user) {
                                        $query->where('user_id', $user->id);
                                    })
                                    ->when($user && $user->hasRole('area_manager') && ! $user->hasRole('admin'), function ($query) use ($user) {
                                        $query->where('sede_id', $user->sede_id);
                                    })
                                    ->select(['cdi', 'name', 'surName'])
                                    ->get()
                                    ->mapWithKeys(fn ($teacher) => [
                                        $teacher->cdi => "{$teacher->cdi} - {$teacher->name} {$teacher->surName}",
                                    ]);
                            })
                            ->default(fn () => auth()->user()?->hasRole('teacher') ? auth()->user()?->teacher?->cdi : null)
                            ->disabled(fn () => auth()->user()?->hasRole('teacher') && ! auth()->user()?->hasAnyRole(['admin', 'area_manager']))
                            ->dehydrated()
                            ->required()
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                $teacher = Teacher::where('cdi', $state)->first();
                                if ($teacher) {
                                    $set('sede_id', $teacher->sede_id);
                                    $set('area_id', $teacher->area_id);
                                    $set('category_id', $teacher->category_id);
                                    $set('dedication_id', $teacher->dedication_id);
                                    $set('email', $teacher->email);
                                }
                            }),

                        Grid::make(3)
                            ->schema([
                                TextInput::make('memoNumber')
                                    ->label('Número de Memorando / Oficio')
                                    ->required()
                                    ->maxLength(100),

                                Select::make('typeReport')
                                    ->label('Tipo de Informe')
                                    ->options([
                                        'Constancia de Trabajo' => 'Constancia de Trabajo',
                                        'Informe de Dedicación' => 'Informe de Dedicación',
                                        'Informe de Escalafón' => 'Informe de Escalafón',
                                        'Memorando Administrativo' => 'Memorando Administrativo',
                                    ])
                                    ->required(),

                                Select::make('status')
                                    ->label('Estado')
                                    ->options([
                                        'issued' => 'Emitido Oficialmente',
                                        'draft' => 'Borrador Preliminar',
                                        'annulled' => 'Anulado',
                                    ])
                                    ->default('issued')
                                    ->disabled(fn () => auth()->user()?->hasRole('teacher') && ! auth()->user()?->hasRole('admin'))
                                    ->required(),
                            ]),

                        Grid::make(3)
                            ->schema([
                                TextInput::make('email')
                                    ->label('Correo de Notificación')
                                    ->email()
                                    ->nullable(),

                                Select::make('sede_id')
                                    ->label('Sede')
                                    ->relationship('sede', 'nombre')
                                    ->searchable()
                                    ->preload(),

                                Select::make('area_id')
                                    ->label('Área Académica')
                                    ->relationship('area', 'nombre')
                                    ->searchable()
                                    ->preload(),
                            ]),

                        TextInput::make('verification_code')
                            ->label('Código Único de Verificación Documental')
                            ->disabled()
                            ->dehydrated(false)
                            ->visibleOn(['edit', 'view'])
                            ->columnSpanFull(),

                        Textarea::make('report')
                            ->label('Contenido del Reporte / Dictamen')
                            ->rows(5)
                            ->columnSpanFull(),

                        Textarea::make('info')
                            ->label('Observaciones Adicionales / Asunto')
                            ->rows(2)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('memoNumber')
                    ->label('Nº Memo')
                    ->copyable()
                    ->copyMessage('Número de memorando copiado')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('verification_code')
                    ->label('Código')
                    ->badge()
                    ->color('info')
                    ->searchable()
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('teacher.full_name')
                    ->label('Docente')
                    ->searchable(['name', 'surName'])
                    ->sortable(),

                TextColumn::make('typeReport')
                    ->label('Tipo')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Constancia de Trabajo' => 'success',
                        'Memorando Administrativo' => 'primary',
                        'Informe de Escalafón' => 'warning',
                        'Informe de Dedicación' => 'info',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'issued' => 'success',
                        'annulled' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'issued' => 'Emitido',
                        'annulled' => 'Anulado',
                        default => 'Borrador',
                    })
                    ->sortable(),

                TextColumn::make('sede.nombre')
                    ->label('Sede')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('area.nombre')
                    ->label('Área')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Fecha de Emisión')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('typeReport')
                    ->label('Tipo')
                    ->options([
                        'Constancia de Trabajo' => 'Constancia de Trabajo',
                        'Informe de Dedicación' => 'Informe de Dedicación',
                        'Informe de Escalafón' => 'Informe de Escalafón',
                        'Memorando Administrativo' => 'Memorando Administrativo',
                    ]),

                SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'issued' => 'Emitido',
                        'draft' => 'Borrador',
                        'annulled' => 'Anulado',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->slideOver(),
                Tables\Actions\EditAction::make(),
                Action::make('pdf')
                    ->label('PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('danger')
                    ->action(function (Report $record) {
                        $pdf = Pdf::loadView('pdf.report', ['report' => $record]);

                        return response()->streamDownload(
                            fn () => print ($pdf->output()),
                            "reporte_{$record->memoNumber}.pdf"
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
                                fputcsv($handle, ['N° Memo', 'Código Verificación', 'Tipo', 'Estado', 'Docente CDI', 'Docente Nombre', 'Sede', 'Área', 'Categoría', 'Dedicación', 'Reporte', 'Fecha']);
                                $sanitizeCell = static function ($value): string {
                                    $str = (string) ($value ?? '');
                                    if (preg_match('/^[=\+\-@\t\r]/', $str)) {
                                        return "'".$str;
                                    }

                                    return $str;
                                };

                                foreach ($records as $rep) {
                                    fputcsv($handle, array_map($sanitizeCell, [
                                        $rep->memoNumber,
                                        $rep->verification_code,
                                        $rep->typeReport,
                                        $rep->status,
                                        $rep->teacher?->cdi ?? '',
                                        $rep->teacher?->full_name ?? '',
                                        $rep->sede?->nombre ?? '',
                                        $rep->area?->nombre ?? '',
                                        $rep->category?->current_category ?? '',
                                        $rep->dedication?->name ?? '',
                                        $rep->report,
                                        $rep->created_at?->format('d/m/Y H:i') ?? '',
                                    ]));
                                }
                                fclose($handle);
                            }, 'reportes_'.now()->format('Ymd_His').'.csv', [
                                'Content-Type' => 'text/csv; charset=UTF-8',
                            ]);
                        }),
                    Tables\Actions\BulkAction::make('export_reports')
                        ->label('Exportar Seleccionados a PDF')
                        ->icon('heroicon-o-document-arrow-down')
                        ->action(function (Collection $records) {
                            $pdf = Pdf::loadView('pdf.reports', ['reports' => $records])
                                ->setPaper('a4', 'landscape');

                            return response()->streamDownload(
                                fn () => print ($pdf->output()),
                                'reporte_memorandos_'.now()->format('Ymd_His').'.pdf'
                            );
                        })
                        ->requiresConfirmation(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReports::route('/'),
            'create' => Pages\CreateReport::route('/create'),
            'edit' => Pages\EditReport::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->with(['teacher', 'sede', 'area']);
        $user = auth()->user();

        if (! $user) {
            return $query;
        }

        if ($user->hasRole('admin')) {
            return $query;
        }

        // El Jefe de Área solo ve reportes de docentes de su sede
        if ($user->hasRole('area_manager') && $user->sede_id) {
            return $query->where(function (Builder $q) use ($user) {
                $q->where('sede_id', $user->sede_id)
                    ->orWhereHas('teacher.user', fn ($sub) => $sub->where('sede_id', $user->sede_id))
                    ->orWhereHas('teacher', fn ($sub) => $sub->where('sede_id', $user->sede_id));
            });
        }

        // El Docente solo ve sus propios reportes
        if ($user->hasRole('teacher')) {
            $teacherCdi = $user->teacher?->cdi;

            return $query->where(function (Builder $q) use ($user, $teacherCdi) {
                if ($teacherCdi) {
                    $q->where('teacher_cdi', $teacherCdi);
                }
                $q->orWhereHas('teacher', fn ($sub) => $sub->where('user_id', $user->id));
            });
        }

        return $query;
    }
}
