<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeacherResource\Pages;
use App\Filament\Resources\TeacherResource\RelationManagers;
use App\Models\Teacher;
use App\Models\Sede;
use App\Models\Area;
use Illuminate\Validation\Rule;
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
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Carbon\Carbon;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;

class TeacherResource extends Resource
{
    protected static ?string $model = Teacher::class;
    protected static ?string $modelLabel = 'Docente';
    protected static ?string $pluralModelLabel = 'Docentes';
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'Docentes';
    protected static ?string $navigationGroup = 'Gestión Docente';
    protected static ?int $navigationSort = -1;

    public static function getNavigationGroup(): ?string
    {
        return 'Gestión Docente';
    }

    public static function getNavigationBadge(): ?string
    {
        if (Auth::check() && (Auth::user()->hasRole('admin') || Auth::user()->hasRole('area_manager'))) {
            return static::getEloquentQuery()->count();
        }
        return null;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->when(Auth::user()->hasRole('area_manager'), fn ($query) =>
                $query->whereHas('sede', fn($q) => $q->where('id', Auth::user()->sede_id))
    );
            // ->with(['sede', 'areas', 'user']);
        }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Usuario')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->getSearchResultsUsing(fn (string $search) =>
                        User::query()
                            ->whereDoesntHave('teacher')  // Filtra usuarios sin relación con teacher
                            ->where('name', 'like', "%{$search}%")
                            ->limit(50)
                            ->pluck('name', 'id')
                    )
                    ->getOptionLabelUsing(fn ($value): ?string =>
                        User::find($value)?->name
                    )
                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                        $user = User::find($state);
                        if ($user) {
                            $set('sede_id', $user->sede_id);
                            $set('area_id', $user->area_id);
                        }
                    })
                    ->rules([
                        Rule::unique('teachers', 'user_id')
                    ])
                    ->validationMessages([
                        'unique' => 'Este usuario ya tiene un docente asignado'
                    ]),

                Forms\Components\TextInput::make('cdi')
                        ->label('Cédula de Identidad')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->minLength(7)
                        ->maxLength(10)
                        ->numeric()
                        ->validationMessages([
                            'required' => 'La cédula es obligatoria',
                            'unique' => 'La cédula ya está registrada',
                            'min' => 'La cédula debe tener al menos :min dígitos',
                            'max' => 'La cédula no puede exceder :max dígitos',
                            'numeric' => 'La cédula solo puede contener números'
                            ]),

                Forms\Components\TextInput::make('name')
                    ->label('Nombres')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('surName')
                    ->label('Apellidos')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('genre')
                    ->label('Género')
                    ->options([
                        'F' => 'Femenino',
                        'M' => 'Masculino'
                    ])
                    ->required(),

                Forms\Components\TextInput::make('phone')
                    ->label('Teléfono')
                    ->tel()
                    ->required()
                    ->minLength(7)
                    ->maxLength(15)
                    ->validationMessages([
                        'required' => 'El teléfono es obligatorio',
                        'min' => 'El teléfono debe tener al menos :min dígitos',
                        'max' => 'El teléfono no puede exceder :max dígitos'
                    ]),

                Forms\Components\TextInput::make('email')
                        ->label('Correo')
                        ->email()
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255)
                        ->validationMessages([
                            'required' => 'El correo es obligatorio',
                            'unique' => 'El correo ya está registrado',
                            'email' => 'Debe ser un correo válido'
                            ]),

                Forms\Components\DatePicker::make('birthDate')
                    ->label('Fecha de Nacimiento')
                    ->required()
                    ->maxDate(now()->subYears(18))
                    ->validationMessages([
                        'required' => 'La fecha de nacimiento es obligatoria',
                        'max' => 'Debe ser mayor de 18 años'
                    ]),

                Forms\Components\DatePicker::make('datePromotion')
                    ->label('Fecha de Promoción')
                    ->required()
                    ->minDate(now()->subYears(50))
                    ->maxDate(now())
                    ->validationMessages([
                        'required' => 'La fecha de promoción es obligatoria',
                        'min' => 'La fecha no puede ser anterior a :min',
                        'max' => 'La fecha no puede ser futura'
                    ]),

                Forms\Components\TextInput::make('asignaturePromotion')
                    ->label('Asignatura de Promoción')
                    ->maxLength(255),

                Forms\Components\Hidden::make('sede_id')
                    ->default(fn () => Auth::user()->sede_id)
                    ->required(),
                Forms\Components\Hidden::make('area_id')
                    ->default(fn () => Auth::user()->area_id)
                    ->required(),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('cdi')
                    ->label('Cédula')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Nombre Completo')
                    ->getStateUsing(fn ($record) => $record->name.' '.$record->surName)
                    ->searchable(['name', 'surName'])
                    ->sortable(['name', 'surName']),
                Tables\Columns\TextColumn::make('sede.nombre')
                    ->label('Sede')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('area.nombre')
                    ->label('Área')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Correo')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Teléfono')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('birthDate')
                    ->label('Fecha de Nacimiento')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('datePromotion')
                    ->label('Fecha de Promoción')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('genre')
                ->label('Género')
                ->options([
                    'F' => 'Femenino',
                    'M' => 'Masculino'
        ]),

    Tables\Filters\SelectFilter::make('sede_id')
        ->label('Sede')
        ->relationship('sede', 'nombre')
        ->searchable()
        ->preload(),

    Tables\Filters\SelectFilter::make('area_id')
        ->label('Área')
        ->relationship('area', 'nombre')
        ->searchable(),

    Tables\Filters\Filter::make('created_at')
        ->form([
            Forms\Components\DatePicker::make('from')
                ->label('Registro desde'),
            Forms\Components\DatePicker::make('to')
                ->label('Registro hasta'),
        ])
        ->query(function (Builder $query, array $data): Builder {
            return $query
                ->when($data['from'],
                    fn($q) => $q->whereDate('created_at', '>=', $data['from']))
                ->when($data['to'],
                    fn($q) => $q->whereDate('created_at', '<=', $data['to']));
        })
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
            'index' => Pages\ListTeachers::route('/'),
            'create' => Pages\CreateTeacher::route('/create'),
            'edit' => Pages\EditTeacher::route('/{record}/edit'),
        ];
    }

    public function isAdmin()
    {
        return Auth::user()?->hasRole('admin') ?? false;
    }

    public function isAreaManager()
    {
        return Auth::user()?->hasRole('area_manager') ?? false;
    }
}
