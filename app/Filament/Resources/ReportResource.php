<?php

namespace App\Filament\Resources;

use App\Models\Report;
use App\Models\Teacher;
use App\Models\Category;
use App\Models\Dedication;
use App\Models\PermissionTeacher;
use App\Models\Site;
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
        $teacher = Teacher::with(['site', 'category', 'dedication', 'permissionTeachers'])->find($state);
        if ($teacher) {
            $set('site_name', $teacher->site ? $teacher->site->name : 'Sin Sede');
            $set('category_name', $teacher->category ? $teacher->category->current_category : 'Sin Categoría');
            $set('dedication_name', $teacher->dedication ? $teacher->dedication->name : 'Sin Dedicación');
            $set('permission_teacher_names', $teacher->permissionTeachers->isEmpty() ? [] : $teacher->permissionTeachers->pluck('name')->toArray());
        } else {
            $set('site_name', 'Sin Sede');
            $set('category_name', 'Sin Categoría');
            $set('dedication_name', 'Sin Dedicación');
            $set('permission_teacher_names', []);
        }
    }),

    Forms\Components\TextInput::make('site_name')
    ->label('Sede')
    ->disabled(),

Forms\Components\TextInput::make('category_name')
    ->label('Categoría')
    ->disabled(),

Forms\Components\TextInput::make('dedication_name')
    ->label('Dedicación')
    ->disabled(),

Forms\Components\Repeater::make('permission_teacher_names')
    ->label('Permisos del Docente')
    ->schema([
        Forms\Components\TextInput::make('name')
            ->label('Nombre del Permiso')
            ->required(),
    ])
    ->default([])
    ->dehydrated(fn ($state) => filled($state))
    ->hidden(fn (Forms\Get $get) => empty($get('permission_teacher_names'))),

                        Forms\Components\TextInput::make('report')
                            ->label('Reporte')
                            ->required()
                            ->maxLength(255),

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

                        Forms\Components\TextInput::make('email')
                            ->label('Correo Electrónico')
                            ->email()
                            ->maxLength(255),


                        Forms\Components\Textarea::make('info')
                            ->label('Información Adicional')
                            ->maxLength(500)
                            ->columnSpanFull(),
                            ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('teacher.cdi')
                    ->label('Docente')
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

                Tables\Columns\TextColumn::make('permissionTeacher.name')
                    ->label('Permiso')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('site.name')
                    ->label('Sede')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('report')
                    ->label('Reporte')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('memoNumber')
                    ->label('Número de Memo')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('typeReport')
                    ->label('Tipo de Reporte')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Correo Electrónico')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('info')
                    ->label('Información Adicional')
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
