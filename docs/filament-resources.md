# Recursos de Filament

## TeacherResource

Recurso principal para la gestión de docentes.

### Estructura
```php
class TeacherResource extends Resource
{
    protected static ?string $model = Teacher::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Sección de Información Personal
                Section::make('Información Personal')
                    ->schema([
                        TextInput::make('cdi')
                            ->required()
                            ->unique(),
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(),
                        TextInput::make('first_name')
                            ->required(),
                        TextInput::make('last_name')
                            ->required(),
                        TextInput::make('phone'),
                        Textarea::make('address')
                    ]),

                // Sección de Asignaciones
                Section::make('Asignaciones')
                    ->schema([
                        Select::make('site_id')
                            ->relationship('site', 'name'),
                        Select::make('category_id')
                            ->relationship('category', 'name'),
                        Select::make('dedication_id')
                            ->relationship('dedication', 'type')
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('cdi'),
                TextColumn::make('full_name'),
                TextColumn::make('email'),
                TextColumn::make('site.name'),
                TextColumn::make('category.name'),
                TextColumn::make('dedication.type')
            ])
            ->filters([
                SelectFilter::make('site'),
                SelectFilter::make('category'),
                SelectFilter::make('dedication')
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make()
            ]);
    }
}
```

### Características Principales
- Formulario dividido en secciones
- Validaciones automáticas
- Relaciones interactivas
- Filtros avanzados
- Acciones personalizadas

## SiteResource

Gestión de sedes institucionales.

### Estructura
```php
class SiteResource extends Resource
{
    protected static ?string $model = Site::class;
    protected static ?string $navigationIcon = 'heroicon-o-building';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->unique(),
                Textarea::make('description'),
                Textarea::make('address')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('teachers_count')
                    ->counts('teachers'),
                TextColumn::make('created_at')
                    ->dateTime()
            ]);
    }
}
```

### Características
- Validación de unicidad
- Conteo de relaciones
- Timestamps formateados

## CategoryResource

Gestión de categorías docentes.

### Estructura
```php
class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required(),
                Textarea::make('description'),
                TextInput::make('level')
                    ->numeric()
                    ->required(),
                Textarea::make('requirements')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('level'),
                TextColumn::make('teachers_count')
                    ->counts('teachers')
            ])
            ->defaultSort('level', 'asc');
    }
}
```

### Características
- Ordenamiento por nivel
- Validación numérica
- Conteo de docentes

## DedicationResource

Gestión de dedicaciones docentes.

### Estructura
```php
class DedicationResource extends Resource
{
    protected static ?string $model = Dedication::class;
    protected static ?string $navigationIcon = 'heroicon-o-clock';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('type')
                    ->options([
                        'TCV' => 'Tiempo Convencional',
                        'MT' => 'Medio Tiempo',
                        'TC' => 'Tiempo Completo',
                        'EX' => 'Exclusiva'
                    ])
                    ->required(),
                TextInput::make('hours')
                    ->numeric()
                    ->required(),
                Textarea::make('description')
            ]);
    }
}
```

### Características
- Opciones predefinidas
- Validación de horas
- Descripción opcional

## PermissionTeacherResource

Gestión de permisos docentes.

### Estructura
```php
class PermissionTeacherResource extends Resource
{
    protected static ?string $model = PermissionTeacher::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-check';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('teacher_id')
                    ->relationship('teacher', 'full_name')
                    ->required(),
                DatePicker::make('start_date')
                    ->required(),
                DatePicker::make('end_date')
                    ->required(),
                Textarea::make('reason')
                    ->required(),
                Select::make('status')
                    ->options([
                        'pending' => 'Pendiente',
                        'approved' => 'Aprobado',
                        'rejected' => 'Rechazado'
                    ])
                    ->required()
            ]);
    }
}
```

### Características
- Selección de docente
- Rango de fechas
- Estados predefinidos

## Widgets y Dashboards

### TeacherStatsWidget
```php
class TeacherStatsWidget extends Widget
{
    protected static string $view = 'filament.widgets.teacher-stats';

    public function getStats(): array
    {
        return [
            'total' => Teacher::count(),
            'active' => Teacher::active()->count(),
            'with_permissions' => Teacher::has('permissions')->count()
        ];
    }
}
```

### SiteOverviewWidget
```php
class SiteOverviewWidget extends Widget
{
    protected static string $view = 'filament.widgets.site-overview';

    public function getSiteData(): Collection
    {
        return Site::withCount('teachers')->get();
    }
}
```

## Acciones Personalizadas

### ApprovePermissionAction
```php
class ApprovePermissionAction extends Action
{
    public static function make(): static
    {
        return parent::make()
            ->label('Aprobar')
            ->color('success')
            ->icon('heroicon-o-check')
            ->requiresConfirmation();
    }
}
```

## Políticas de Acceso

### Implementación
```php
class TeacherPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view_teachers');
    }

    public function create(User $user): bool
    {
        return $user->can('create_teachers');
    }
}
```

## Notas de Implementación

1. Todos los recursos implementan:
   - Navegación personalizada
   - Iconos descriptivos
   - Ordenamiento lógico
   - Filtros relevantes

2. Validaciones:
   - A nivel de modelo
   - A nivel de formulario
   - Mensajes personalizados

3. Optimización:
   - Eager loading de relaciones
   - Caché de consultas frecuentes
   - Paginación eficiente
