<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DedicationResource\Pages;
use App\Models\Dedication;
use App\Models\Teacher;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Tables\Columns\TextColumn;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;

class DedicationResource extends Resource
{
    protected static ?string $model = Dedication::class;
    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationLabel = 'Dedicaciones';
    protected static ?string $navigationGroup = 'Asignaciones';
    protected static ?string $modelLabel = 'Dedicación';
    protected static ?string $pluralModelLabel = 'Dedicaciones';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información del Docente')
                    ->description('Seleccione el docente e ingrese sus dedicaciones')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Select::make('teacher_id')
                            ->relationship('teacher', 'cdi')
                            ->label('Docente')
                            ->options(function () {
                                return Teacher::whereDoesntHave('dedication')->pluck('cdi', 'id');
                            })
                            ->required()
                            ->reactive()
                            ->columnSpan('full'),
                    ]),

                Forms\Components\Select::make('name')
                    ->options([
                        'TCV' => 'Tiempo Convencional',
                        'MT' => 'Medio Tiempo',
                        'TC' => 'Tiempo Completo',
                        'EX' => 'Exclusiva'
                    ])
                    ->required()
                    ->label('Dedicación')
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $set('hours', null);
                    })
                    ->searchable()
                    ->preload(),

                Forms\Components\Select::make('hours')
                    ->label('Horas')
                    ->options(function (callable $get) {
                        return Dedication::getValidHours($get('name'));
                    })
                    ->required()
                    ->searchable()
                    ->preload(),

                Forms\Components\Select::make('director')
                    ->options([
                        'Coordinador' => 'Coordinador',
                        'Jefe de Departamento' => 'Jefe de Departamento',
                        'Decano' => 'Decano'
                    ])
                    ->label('Cargo Directivo')
                    ->nullable()
                    ->searchable()
                    ->preload()
                    ->helperText('Seleccione si tiene algún cargo directivo'),

                Forms\Components\TextInput::make('studentNumber')
                    ->numeric()
                    ->label('Número de Estudiantes')
                    ->helperText('Número de estudiantes en asesoría (1-100)')
                    ->minValue(1)
                    ->maxValue(100)
                    ->nullable()
                    ->reactive()
                    ->afterStateUpdated(fn ($state, callable $set) =>
                        $set('studentHours', $state ? null : null)
                    ),

                Forms\Components\TextInput::make('studentHours')
                    ->numeric()
                    ->label('Horas de Asesoría')
                    ->helperText('Número de horas dedicadas a asesorías (1-100)')
                    ->minValue(1)
                    ->maxValue(100)
                    ->nullable()
                    ->hidden(fn (callable $get) => !$get('studentNumber')),

                Forms\Components\Textarea::make('info')
                    ->label('Observaciones')
                    ->nullable()
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

                Tables\Columns\TextColumn::make('name')
                    ->label('Dedicación')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'TCV' => 'Tiempo Convencional',
                        'MT' => 'Medio Tiempo',
                        'TC' => 'Tiempo Completo',
                        'EX' => 'Exclusiva',
                        default => $state,
                    })
                    ->searchable(),

                Tables\Columns\TextColumn::make('hours')
                    ->label('Horas'),

                Tables\Columns\TextColumn::make('director')
                    ->label('Cargo Directivo')
                    ->default('Sin Cargo'),

                Tables\Columns\TextColumn::make('studentNumber')
                    ->label('Estudiantes')
                    ->numeric(),

                Tables\Columns\TextColumn::make('studentHours')
                    ->label('Horas Asesoría')
                    ->numeric(),
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
                    Tables\Actions\BulkAction::make('export')
                        ->label('Exportar a PDF')
                        ->icon('heroicon-o-document-arrow-down')
                        ->action(function (Collection $records) {
                            $pdf = Pdf::loadView('pdf.teachers', [
                                'teachers' => $records
                            ])->setPaper('a4', 'landscape');

                            return response()->streamDownload(function () use ($pdf) {
                                echo $pdf->output();
                            }, 'docentes_'.now()->format('Ymd_His').'.pdf');
                        })
                        ->requiresConfirmation()
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
            'index' => Pages\ListDedications::route('/'),
            'create' => Pages\CreateDedication::route('/create'),
            'edit' => Pages\EditDedication::route('/{record}/edit'),
        ];
    }
}
