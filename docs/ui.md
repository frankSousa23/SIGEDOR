# Interfaz de Usuario

## 1. Panel Administrativo

### Diseño General
- Diseño responsivo
- Tema oscuro/claro
- Navegación intuitiva
- Breadcrumbs

### Estructura
```
Dashboard
├── Sidebar
│   ├── Navegación principal
│   ├── Accesos rápidos
│   └── Configuración
├── Header
│   ├── Búsqueda global
│   ├── Notificaciones
│   └── Perfil usuario
└── Contenido principal
    ├── Widgets
    ├── Tablas
    └── Formularios
```

## 2. Componentes Filament

### Forms
```php
class TeacherResource extends Resource
{
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Grid::make(['default' => 12])
                            ->schema([
                                TextInput::make('cdi')
                                    ->required()
                                    ->unique()
                                    ->columnSpan([
                                        'default' => 12,
                                        'md' => 6,
                                    ]),
                                
                                TextInput::make('email')
                                    ->email()
                                    ->required()
                                    ->unique()
                                    ->columnSpan([
                                        'default' => 12,
                                        'md' => 6,
                                    ]),
                                
                                Select::make('site_id')
                                    ->relationship('site', 'name')
                                    ->searchable()
                                    ->columnSpan(12),
                            ]),
                    ])
            ]);
    }
}
```

### Tables
```php
public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('cdi')
                ->searchable()
                ->sortable(),
            
            TextColumn::make('full_name')
                ->searchable()
                ->sortable(),
            
            BadgeColumn::make('status')
                ->colors([
                    'success' => 'active',
                    'danger' => 'inactive',
                ]),
        ])
        ->filters([
            SelectFilter::make('site')
                ->relationship('site', 'name'),
            
            Filter::make('active')
                ->query(fn ($query) => $query->where('active', true))
        ])
        ->actions([
            EditAction::make(),
            DeleteAction::make(),
        ])
        ->bulkActions([
            DeleteBulkAction::make(),
        ]);
}
```

## 3. Widgets Personalizados

### Stats Card
```php
class TeacherStatsWidget extends Widget
{
    protected static string $view = 'filament.widgets.teacher-stats';
    
    protected function getViewData(): array
    {
        return [
            'totalTeachers' => Teacher::count(),
            'activeTeachers' => Teacher::active()->count(),
            'inactiveTeachers' => Teacher::inactive()->count(),
        ];
    }
}
```

### Chart Widget
```php
class TeacherChartWidget extends LineChartWidget
{
    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Docentes por Mes',
                    'data' => $this->getMonthlyData(),
                ],
            ],
            'labels' => $this->getMonthLabels(),
        ];
    }
}
```

## 4. Formularios Reactivos

### Validación en Tiempo Real
```php
class TeacherForm extends Component
{
    public $cdi;
    public $email;
    
    protected $rules = [
        'cdi' => 'required|unique:teachers',
        'email' => 'required|email|unique:teachers',
    ];
    
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }
}
```

### Dependencias Dinámicas
```php
public static function form(Form $form): Form
{
    return $form->schema([
        Select::make('category_id')
            ->relationship('category', 'name')
            ->reactive()
            ->afterStateUpdated(fn ($state, callable $set) => 
                $set('dedication_id', null)
            ),
        
        Select::make('dedication_id')
            ->relationship('dedication', 'type')
            ->options(function (callable $get) {
                $categoryId = $get('category_id');
                
                if (!$categoryId) {
                    return [];
                }
                
                return Dedication::where('category_id', $categoryId)
                    ->pluck('type', 'id');
            }),
    ]);
}
```

## 5. Notificaciones UI

### Toast Notifications
```php
Notification::make()
    ->title('Docente Creado')
    ->success()
    ->send();
```

### Modal Alerts
```php
$this->dialog()
    ->success()
    ->title('Operación Exitosa')
    ->description('El docente ha sido registrado correctamente.')
    ->show();
```

## 6. Temas y Estilos

### Configuración
```php
class FilamentServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Filament::serving(function () {
            Filament::registerTheme(
                mix('css/filament.css')
            );
        });
    }
}
```

### Variables CSS
```css
:root {
    --primary: rgb(var(--primary-rgb));
    --primary-rgb: 79, 70, 229;
    
    --secondary: rgb(var(--secondary-rgb));
    --secondary-rgb: 161, 161, 170;
}
```

## 7. Responsive Design

### Breakpoints
```php
protected function getTableRecordsPerPageSelectOptions(): array
{
    return [
        10,
        25,
        50,
        100,
    ];
}

protected function getDefaultTableRecordsPerPageSelectOption(): int
{
    return config('filament.default_per_page', 10);
}
```

### Mobile Navigation
```php
protected function getNavigation(): array
{
    return [
        'dashboard' => [
            'label' => 'Dashboard',
            'icon' => 'heroicon-o-home',
            'activeIcon' => 'heroicon-s-home',
        ],
    ];
}
```

## 8. Accesibilidad

### ARIA Labels
```html
<button
    type="button"
    aria-label="Crear nuevo docente"
    class="filament-button"
>
    Nuevo Docente
</button>
```

### Keyboard Navigation
```js
document.addEventListener('keydown', function(e) {
    if (e.ctrlKey && e.key === 'k') {
        // Abrir búsqueda global
        e.preventDefault();
        openGlobalSearch();
    }
});
```

## 9. Performance UI

### Lazy Loading
```php
public static function getRelations(): array
{
    return [
        RelationManagers\PermissionsRelationManager::class,
    ];
}
```

### Debounce Search
```php
protected function getTableSearchDebounce(): int
{
    return 500;
}
```

## 10. Personalización

### Custom Views
```php
class TeacherResource extends Resource
{
    protected static ?string $recordTitleAttribute = 'full_name';
    
    public static function getGloballySearchableAttributes(): array
    {
        return ['cdi', 'first_name', 'last_name', 'email'];
    }
}
```

### Actions Personalizadas
```php
class ApprovePermissionAction extends Action
{
    public static function make(): static
    {
        return parent::make()
            ->label('Aprobar')
            ->icon('heroicon-o-check')
            ->color('success')
            ->requiresConfirmation();
    }
}
```

## Mejores Prácticas

1. Diseño
   - Mobile-first
   - Consistencia visual
   - Feedback claro

2. Performance
   - Lazy loading
   - Optimización imágenes
   - Cache client-side

3. Accesibilidad
   - ARIA labels
   - Contraste adecuado
   - Navegación teclado

4. UX
   - Mensajes claros
   - Validación inmediata
   - Retroalimentación visual
