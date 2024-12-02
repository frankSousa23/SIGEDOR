<?php

namespace App\Filament\Resources;

use App\Models\Report;
use App\Models\Teacher;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Resources\ReportResource\Pages;
use Filament\Actions\Exports\Models\Export;

class ReportResource extends Resource
{
    protected static ?string $model = Report::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static ?string $navigationLabel = 'Reportes';
    protected static ?string $navigationGroup = 'Reportes';
    protected static ?int $navigationSort = 3;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('teacher.cdi')
                    ->label('Docente')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'academic' => 'Académico',
                        'administrative' => 'Administrativo',
                        'research' => 'Investigación',
                        'extension' => 'Extensión',
                        default => $state,
                    })
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('report_date')
                    ->label('Fecha')
                    ->date()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Estado')
                    ->colors([
                        'warning' => 'draft',
                        'primary' => 'submitted',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ])
                    ->searchable()
                    ->sortable(),
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
                    Tables\Actions\ExportBulkAction::make()
                        ->formats([
                            'pdf'
                        ])
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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información del Docente')
                    ->description('Seleccione el docente para generar el reporte')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Select::make('teacher_id')
                            ->relationship('teacher', 'cdi')
                            ->label('Docente')
                            ->options(function () {
                                return Teacher::whereDoesntHave('report')->pluck('cdi', 'id');
                            })
                            ->required()
                            ->reactive()
                            ->columnSpan('full'),
                    ]),

                Forms\Components\Select::make('type')
                    ->options([
                        'academic' => 'Académico',
                        'administrative' => 'Administrativo',
                        'research' => 'Investigación',
                        'extension' => 'Extensión'
                    ])
                    ->required()
                    ->searchable()
                    ->label('Tipo de Reporte'),

                Forms\Components\TextInput::make('title')
                    ->required()
                    ->label('Título')
                    ->maxLength(255),

                Forms\Components\DatePicker::make('report_date')
                    ->required()
                    ->label('Fecha del Reporte')
                    ->format('Y-m-d'),

                Forms\Components\Select::make('status')
                    ->options([
                        'draft' => 'Borrador',
                        'submitted' => 'Enviado',
                        'approved' => 'Aprobado',
                        'rejected' => 'Rechazado'
                    ])
                    ->required()
                    ->searchable()
                    ->label('Estado'),

                Forms\Components\RichEditor::make('content')
                    ->required()
                    ->label('Contenido')
                    ->toolbarButtons([
                        'bold',
                        'italic',
                        'underline',
                        'strike',
                        'bulletList',
                        'orderedList',
                        'redo',
                        'undo'
                    ])
                    ->columnSpanFull(),

                Forms\Components\Textarea::make('observations')
                    ->label('Observaciones')
                    ->maxLength(500)
                    ->columnSpanFull(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }
}
