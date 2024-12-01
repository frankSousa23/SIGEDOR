<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeacherResource\Pages;
use App\Filament\Resources\TeacherResource\RelationManagers;
use App\Models\Teacher;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;

class TeacherResource extends Resource
{
    protected static ?string $model = Teacher::class;
    protected static ?string $modelLabel = 'Docente';
    protected static ?string $pluralModelLabel = 'Docentes';
    protected static ?string $navigationLabel = 'Docentes';
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Gestión docente';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('cdi')
                    ->label('CDI')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(10)
                    ->helperText('Ingrese la Cédula de Identidad del docente sin puntos ni espacios'),

                Forms\Components\TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255)
                    ->helperText('Ingrese el primer nombre del docente'),

                Forms\Components\TextInput::make('surName')
                    ->label('Apellido')
                    ->required()
                    ->maxLength(255)
                    ->helperText('Ingrese el primer apellido del docente'),

                Forms\Components\Select::make('genre')
                    ->label('Género')
                    ->options([
                        'F' => 'Femenino',
                        'M' => 'Masculino'
                    ])
                    ->required()
                    ->helperText('Seleccione el género del docente'),

                Forms\Components\TextInput::make('phone')
                    ->label('Teléfono')
                    ->tel()
                    ->required()
                    ->maxLength(11)
                    ->helperText('Ingrese el número de teléfono en formato: 0414xxxxxxx')
                    ->mask('0000-0000000')
                    ->placeholder('0414-1234567'),

                Forms\Components\TextInput::make('email')
                    ->label('Correo Electrónico')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->helperText('Ingrese un correo electrónico válido'),

                Forms\Components\DatePicker::make('birthDate')
                    ->label('Fecha de Nacimiento')
                    ->required()
                    ->maxDate(now()->subYears(18))
                    ->helperText('Seleccione la fecha de nacimiento (debe ser mayor de 18 años)'),

                Forms\Components\DatePicker::make('datePromotion')
                    ->label('Fecha de Promoción')
                    ->required()
                    ->helperText('Seleccione la fecha de la última promoción del docente'),

                Forms\Components\TextInput::make('asignaturePromotion')
                    ->label('Asignatura de Promoción')
                    ->required()
                    ->maxLength(255)
                    ->helperText('Ingrese la asignatura o área en la que se promovió el docente'),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        if ($user->isAreaManager()) {
            $query->whereHas('site', function ($query) use ($user) {
                $query->where('id', $user->getSiteId())
                      ->where('area', $user->getArea());
            });
        } elseif ($user->isTeacher()) {
            $query->where('user_id', $user->id);
        }

        return $query->with(['site']);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('cdi')
                    ->label('Cédula')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombres')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('surName')
                    ->label('Apellidos')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('genre')
                    ->label('Género')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Teléfono')
                    ->formatStateUsing(function ($state) {
                        return substr($state, 0, 4) . '-' . substr($state, 4);
                    })
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Correo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('birthDate')
                    ->label('Fecha de Nacimiento')
                    ->date()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('datePromotion')
                    ->label('Fecha de Ingreso')
                    ->date()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('asignaturePromotion')
                    ->label('Asignatura de Promoción')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('export_pdf')
                    ->label('Exportar PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->action(function (Teacher $record) {
                        $user = auth()->user();
                        
                        if (!$user->can('view', $record)) {
                            return;
                        }

                        $pdf = Pdf::loadView('pdf.teacher-details', [
                            'teacher' => $record,
                            'user' => $user,
                        ]);

                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->output();
                        }, "teacher_{$record->id}.pdf");
                    })
                    ->visible(fn (Teacher $record) => auth()->user()->can('view', $record)),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('export_selected')
                    ->label('Exportar Seleccionados')
                    ->action(function (Collection $records) {
                        $user = auth()->user();
                        $records = $records->filter(fn ($record) => $user->can('view', $record));
                        
                        $pdf = Pdf::loadView('pdf.teachers-list', [
                            'teachers' => $records,
                            'user' => $user,
                        ]);

                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->output();
                        }, 'teachers_export.pdf');
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
            'index' => Pages\ListTeachers::route('/'),
            'create' => Pages\CreateTeacher::route('/create'),
            'edit' => Pages\EditTeacher::route('/{record}/edit'),
        ];
    }    
}
