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
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\EditAction;
use Spatie\Permission\Models\Role;


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
                    ])->find($state);

                    \Log::info('Teacher ID State: ' . $state);

                    if ($teacher) {
                        \Log::info('Teacher found: ' . $teacher->toJson());
                        \Log::info('Teacher CDI: ' . $teacher->cdi);
                        // Campos principales
                        $set('sede_id', $teacher->sede_id);
                        $set('area_id', $teacher->area_id);

                        $set('sede_nombre', optional($teacher->sede)->nombre ?? 'Sin Sede');
                        $set('area_nombre', optional($teacher->area)->nombre ?? 'Sin Área');

                        
                        $category = Category::where('teacher_cdi', $teacher->cdi)->first();
                        \Log::info('Category: ' . ($category ? $category->toJson() : 'null'));
                        $set('category_id', $category?->id);
                        $set('category_name', $category?->current_category ?? 'Sin Categoría');

                        
                        $dedication = Dedication::where('teacher_cdi', $teacher->cdi)->first();
                        \Log::info('Dedication: ' . ($dedication ? $dedication->toJson() : 'null'));
                        $set('dedication_id', $dedication?->id);
                        $set('dedication_name', $dedication?->name ?? 'Sin Dedicación');
                    } else {
                        $set('sede_id', null);
                        $set('area_id', null);
                        $set('sede_nombre', 'Sin Sede');
                        $set('area_nombre', 'Sin Área');
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
            Forms\Components\TextInput::make('category_name')
                ->label('Categoría')
                ->disabled(),
            Forms\Components\TextInput::make('dedication_name')
                ->label('Dedicación')
                ->disabled(),
            Forms\Components\Hidden::make('sede_id'),
            Forms\Components\Hidden::make('area_id'),
            Forms\Components\Hidden::make('category_id'),
            Forms\Components\Hidden::make('dedication_id'),
                            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('memoNumber')
        ->label('N° Memo')
        ->searchable()
        ->sortable()
        ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('typeReport')
                    ->label('Tipo')
                    ->formatStateUsing(fn ($state) => match($state) {
                        'academic' => 'Académico',
                        'administrative' => 'Administrativo',
                        'research' => 'Investigación',
                        'extension' => 'Extensión'
                    })
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
                Tables\Columns\TextColumn::make('category.current_category')
                    ->label('Categoría')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('dedication.name')
                    ->label('Dedicación')
                    ->searchable()
                    ->sortable(),
                    Tables\Columns\TextColumn::make('teacher.full_name')
                    ->label('Nombre Completo')
                    ->sortable(query: function (Builder $query, string $direction) {
                        $query->orderBy('teachers.name', $direction)
                              ->orderBy('teachers.surName', $direction);
                    })
                    ->searchable(['teachers.name', 'teachers.surName']),
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
                Tables\Columns\IconColumn::make('has_programa')
                    ->label('Programa')
                    ->boolean()
                    ->getStateUsing(fn ($record) => !is_null($record->programa_id)),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('typeReport')
        ->label('Tipo de Reporte')
        ->options([
            'academic' => 'Académico',
            'administrative' => 'Administrativo',
            'research' => 'Investigación',
            'extension' => 'Extensión'
        ]),

    Tables\Filters\SelectFilter::make('teacher_id')
        ->label('Docente')
        ->relationship('teacher', 'cdi')
        ->searchable()
        ->preload(),

    Tables\Filters\Filter::make('created_at')
        ->form([
            Forms\Components\DatePicker::make('from')
                ->label('Desde'),
            Forms\Components\DatePicker::make('to')
                ->label('Hasta'),
        ])
        ->query(function ($query, array $data) {
            return $query
                ->when($data['from'],
                    fn($q) => $q->whereDate('created_at', '>=', $data['from']))
                ->when($data['to'],
                    fn($q) => $q->whereDate('created_at', '<=', $data['to']));
        })
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->icon('heroicon-m-eye'),
                    Tables\Actions\EditAction::make()
                        ->icon('heroicon-m-pencil-square'),
                    Tables\Actions\Action::make('pdf')
                        ->label('PDF')
                        ->icon('heroicon-m-arrow-down-tray')
                        ->url(fn ($record) => route('reports.pdf', $record))
                        ->openUrlInNewTab()
                ])
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

    protected function getTableQuery(): Builder
{
    $user = \Illuminate\Support\Facades\Auth::user();

    if ($user->hasRole('admin')) {
        return Report::query();
    }

    if ($user->hasRole('area_manager')) {
        return Report::query()
            ->where('sede_id', $user->sede_id)
            ->where('area_id', $user->area_id); // Area Manager ve solo su sede y área
    }

    return Report::where('user_id', $user->id); // Teacher ve solo sus propios reports
}

protected function getTableActions(): array
{
    return [
        EditAction::make()
            ->visible(fn (Report $record): bool => \Illuminate\Support\Facades\Auth::user()->hasRole('admin') ||
                \Illuminate\Support\Facades\Auth::user()->hasRole('area_manager') &&
                 $record->sede_id === \Illuminate\Support\Facades\Auth::user()->sede_id &&
                 $record->area_id === \Illuminate\Support\Facades\Auth::user()->area_id) ||
                \Illuminate\Support\Facades\Auth::user()->hasRole('teacher') &&
                 $record->user_id === \Illuminate\Support\Facades\Auth::user()->id // Solo admin, area_manager con misma sede/área o teacher dueño puede editar
    ];
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
