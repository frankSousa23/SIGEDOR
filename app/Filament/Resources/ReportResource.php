<?php

namespace App\Filament\Resources;

use App\Models\Report;
use App\Models\Teacher;
use App\Models\Category;
use App\Models\Dedication;
use App\Models\PermissionTeacher;
use App\Models\Site;
use App\Models\Programa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Resources\ReportResource\Pages;
use Filament\Actions\Exports\Models\Export;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;

class ReportResource extends Resource
{
    protected static ?string $model = Report::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static ?string $navigationLabel = 'Reportes';
    protected static ?string $navigationGroup = 'Reportes';
    protected static ?string $modelLabel = 'Reporte';
    protected static ?string $pluralModelLabel = 'Reportes';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            Forms\Components\Select::make('teacher_id')
                ->label('Docente')
                ->options(Teacher::pluck('cdi', 'id'))
                ->required()
                ->searchable()
                ->preload()
                ->live()
                ->afterStateUpdated(function ($state, Forms\Set $set) {
                    $teacher = Teacher::with([
                        'sede',
                        'area',
                        'site' => fn($q) => $q->with('programa')
                    ])->find($state);

                    if ($teacher) {
                        // Campos principales
                        $set('sede_id', $teacher->sede_id);
                        $set('area_id', $teacher->area_id);

                        // Campos de sede y área con null safety
                        $set('sede_nombre', optional($teacher->sede)->nombre ?? 'Sin Sede');
                        $set('area_nombre', optional($teacher->area)->nombre ?? 'Sin Área');

                        // Campos de programa con validación en cascada
                        $programaId = optional($teacher->site)->programa_id;
                        $programaNombre = 'Sin Programa';

                        if ($teacher->site && $teacher->site->programa) {
                            $programaNombre = $teacher->site->programa->nombre;
                        }

                        $set('programa_id', $programaId);
                        $set('programa_nombre', $programaNombre);

                        // Campos de categoría y dedicación
                        $category = Category::where('teacher_id', $state)->first();
                        $set('category_id', $category?->id);
                        $set('category_name', $category?->current_category ?? 'Sin Categoría');

                        $dedication = Dedication::where('teacher_id', $state)->first();
                        $set('dedication_id', $dedication?->id);
                        $set('dedication_name', $dedication?->name ?? 'Sin Dedicación');

                    } else {
                        // Reset completo de campos
                        $set('sede_id', null);
                        $set('area_id', null);
                        $set('sede_nombre', 'Sin Sede');
                        $set('area_nombre', 'Sin Área');
                        $set('programa_id', null);
                        $set('programa_nombre', 'Sin Programa');
                        $set('category_id', null);
                        $set('category_name', 'Sin Categoría');
                        $set('dedication_id', null);
                        $set('dedication_name', 'Sin Dedicación');
                    }
                }),
            Forms\Components\TextInput::make('memoNumber')
                ->label('Número de Memo')
                ->required()
                ->maxLength(255),
            Forms\Components\Select::make('typeReport')
                ->label('Tipo de Reporte')
                ->options([
                    'academic' => 'Académico',
                    'administrative' => 'Administrativo',
                    'research' => 'Investigación',
                    'extension' => 'Extensión'
                ])
                ->required()
                ->searchable(),
            Forms\Components\TextInput::make('report')
                ->label('Reporte')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('email')
                ->label('Correo')
                ->email()
                ->maxLength(255),
            Forms\Components\Textarea::make('info')
                ->label('Observaciones')
                ->maxLength(500)
                ->columnSpanFull(),
            Forms\Components\TextInput::make('sede_nombre')
                ->label('Sede')
                ->disabled(),
            Forms\Components\TextInput::make('area_nombre')
                ->label('Área')
                ->disabled(),
            Forms\Components\TextInput::make('programa_nombre')
                ->label('Programa')
                ->disabled(),
            Forms\Components\TextInput::make('category_name')
                ->label('Categoría')
                ->disabled(),
            Forms\Components\TextInput::make('dedication_name')
                ->label('Dedicación')
                ->disabled(),
            Forms\Components\Hidden::make('sede_id'),
            Forms\Components\Hidden::make('area_id'),
            Forms\Components\Hidden::make('programa_id'),
            Forms\Components\Hidden::make('category_id'),
            Forms\Components\Hidden::make('dedication_id'),
                            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('memoNumber')
                    ->label('Número de Memo')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('typeReport')
                    ->label('Tipo de Reporte')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('teacher.cdi')
                    ->label('Cédula')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sede.nombre')
                    ->label('Sede')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('area.nombre')
                    ->label('Área')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('programa.nombre')
                    ->label('Programa')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.current_category')
                    ->label('Categoría')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('dedication.name')
                    ->label('Dedicación')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('teacher.name')
                    ->label('Nombres')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('teacher.surName')
                    ->label('Apellidos')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('report')
                    ->label('Reporte')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Correo')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('info')
                    ->label('Observaciones')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('export')
                ->label('Exportar a PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->action(function (Report $record) {
                    $pdf = Pdf::loadView('pdf.report', [
                        'report' => $record
                    ])->setPaper('a4', 'landscape');

                    return response()->streamDownload(function () use ($pdf) {
                        echo $pdf->output();
                    }, 'report_'.$record->id.'_'.now()->format('Ymd_His').'.pdf');
                })
                ->requiresConfirmation()
            ])
            ->bulkActions([

                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('export')
                    ->label('Exportar a PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->action(function (Collection $records) {
                        $pdf = Pdf::loadView('pdf.reports', [
                            'reports' => $records
                        ])->setPaper('a4', 'landscape');

                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->output();
                        }, 'reports_'.now()->format('Ymd_His').'.pdf');
                    })
                    ->requiresConfirmation()
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
}
