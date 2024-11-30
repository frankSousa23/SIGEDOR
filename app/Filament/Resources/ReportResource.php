<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReportResource\Pages;
use App\Filament\Resources\ReportResource\RelationManagers;
use App\Models\Report;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use App\Models\Teacher;

class ReportResource extends Resource
{
    protected static ?string $model = Report::class;
    protected static ?string $navigationLabel = 'Reportes';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Generar Reporte';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('teacher_id')
                    ->relationship('teacher', 'cdi')
                    ->label('Docente')
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $teacher = \App\Models\Teacher::with(['category', 'dedication', 'permission', 'site'])->find($state);
                            if ($teacher) {
                                $set('category_id', $teacher->category_id);
                                $set('dedication_id', $teacher->dedication_id);
                                $set('permission_id', $teacher->permission_id);
                                $set('site_id', $teacher->site_id);
                                
                                // También establecemos los valores para mostrar
                                $set('category_name', $teacher->category?->name ?? 'N/A');
                                $set('dedication_name', $teacher->dedication?->name ?? 'N/A');
                                $set('permission_name', $teacher->permission?->name ?? 'N/A');
                                $set('site_name', $teacher->site?->name ?? 'N/A');
                            }
                        }
                    }),
                Forms\Components\TextInput::make('category_name')
                    ->label('Categoría')
                    ->disabled()
                    ->dehydrated(false),
                Forms\Components\Hidden::make('category_id'),
                
                Forms\Components\TextInput::make('dedication_name')
                    ->label('Dedicación')
                    ->disabled()
                    ->dehydrated(false),
                Forms\Components\Hidden::make('dedication_id'),
                
                Forms\Components\TextInput::make('permission_name')
                    ->label('Permiso')
                    ->disabled()
                    ->dehydrated(false),
                Forms\Components\Hidden::make('permission_id'),
                
                Forms\Components\TextInput::make('site_name')
                    ->label('Sede')
                    ->disabled()
                    ->dehydrated(false),
                Forms\Components\Hidden::make('site_id'),
                
                Forms\Components\TextInput::make('report')
                    ->label('Reporte')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('memoNumber')
                    ->label('Número de Memo')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('typeReport')
                    ->label('Tipo de Reporte')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->label('Correo Electrónico')
                    ->maxLength(255),
                Forms\Components\TextInput::make('info')
                    ->label('Información Adicional')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('teacher.cdi')
                    ->label('Docente')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('teacher.name')->label('Nombres')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('teacher.surName')->label('Apellidos')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Categoría')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('dedication.name')
                    ->label('Dedicación')
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
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                \Filament\Tables\Actions\Action::make('export')
                    ->label('Exportar PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->action(function ($livewire) {
                        // Aquí implementaremos la lógica de exportación
                        $reports = Report::with(['teacher', 'category', 'dedication', 'site'])->get();
                        
                        return response()->streamDownload(function () use ($reports) {
                            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.export', [
                                'reports' => $reports,
                            ]);
                            echo $pdf->output();
                        }, 'reporte-docentes.pdf');
                    })
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
