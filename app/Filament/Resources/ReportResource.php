<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReportResource\Pages;
use App\Models\Report;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ReportResource extends Resource
{
    protected static ?string $model = Report::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static ?string $navigationLabel = 'Reportes';
    protected static ?string $navigationGroup = 'Gestión Docente';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('teacher_id')
                    ->relationship('teacher', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->label('Nombre'),
                        Forms\Components\TextInput::make('ci')
                            ->required()
                            ->label('Cédula')
                            ->unique('teachers', 'ci'),
                        Forms\Components\TextInput::make('phone')
                            ->required()
                            ->label('Teléfono')
                            ->tel(),
                        Forms\Components\Textarea::make('address')
                            ->required()
                            ->label('Dirección'),
                    ])
                    ->createOptionAction(function (Forms\Components\Actions\Action $action) {
                        return $action
                            ->modalHeading('Crear nuevo docente')
                            ->modalButton('Crear docente')
                            ->modalWidth('lg');
                    })
                    ->label('Docente'),

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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('teacher.name')
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
            'index' => Pages\ListReports::route('/'),
            'create' => Pages\CreateReport::route('/create'),
            'edit' => Pages\EditReport::route('/{record}/edit'),
        ];
    }    
}
