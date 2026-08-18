# Modelos y Relaciones

## Teacher

El modelo principal para la gestión de docentes.

```php
class Teacher extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'cdi',
        'email',
        'first_name',
        'last_name',
        'phone',
        'address',
        'site_id',
        'category_id',
        'dedication_id'
    ];

    // Relaciones
    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function dedication()
    {
        return $this->belongsTo(Dedication::class);
    }

    public function permissions()
    {
        return $this->hasMany(PermissionTeacher::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
```

### Atributos Principales
- `cdi`: Identificación única del docente
- `email`: Correo electrónico único
- `first_name`: Nombre
- `last_name`: Apellido
- `phone`: Teléfono
- `address`: Dirección

### Relaciones
- Pertenece a una Sede (`Site`)
- Pertenece a una Categoría (`Category`)
- Pertenece a una Dedicación (`Dedication`)
- Tiene muchos Permisos (`PermissionTeacher`)
- Tiene muchos Reportes (`Report`)

## Site

Modelo para la gestión de sedes institucionales.

```php
class Site extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'address'
    ];

    // Relaciones
    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }
}
```

### Atributos Principales
- `name`: Nombre de la sede
- `description`: Descripción detallada
- `address`: Dirección física

### Relaciones
- Tiene muchos Docentes (`Teacher`)

## Category

Modelo para la gestión de categorías docentes.

```php
class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'level',
        'requirements'
    ];

    // Relaciones
    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }
}
```

### Atributos Principales
- `name`: Nombre de la categoría
- `description`: Descripción
- `level`: Nivel jerárquico
- `requirements`: Requisitos para la categoría

### Relaciones
- Tiene muchos Docentes (`Teacher`)

## Dedication

Modelo para la gestión de dedicaciones docentes.

```php
class Dedication extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'hours',
        'description'
    ];

    // Relaciones
    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }
}
```

### Atributos Principales
- `type`: Tipo de dedicación (TCV, MT, TC, EX)
- `hours`: Horas semanales
- `description`: Descripción detallada

### Relaciones
- Tiene muchos Docentes (`Teacher`)

## PermissionTeacher

Modelo para la gestión de permisos y licencias docentes.

```php
class PermissionTeacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'start_date',
        'end_date',
        'reason',
        'status'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date'
    ];

    // Relaciones
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
```

### Atributos Principales
- `start_date`: Fecha de inicio
- `end_date`: Fecha de finalización
- `reason`: Motivo del permiso
- `status`: Estado (pending, approved, rejected)

### Relaciones
- Pertenece a un Docente (`Teacher`)

## Traits y Scopes Comunes

### HasFactory
Utilizado en todos los modelos para facilitar la creación de datos de prueba.

### SoftDeletes
Implementado en modelos críticos para mantener histórico de registros eliminados.

### Scopes Personalizados
```php
// Ejemplo en Teacher
public function scopeActive($query)
{
    return $query->whereNull('deleted_at');
}

public function scopeBySite($query, $siteId)
{
    return $query->where('site_id', $siteId);
}
```

## Eventos y Observadores

### TeacherObserver
```php
class TeacherObserver
{
    public function created(Teacher $teacher)
    {
        // Lógica post-creación
    }

    public function updated(Teacher $teacher)
    {
        // Lógica post-actualización
    }
}
```

## Validación y Reglas de Negocio

### Reglas de Validación
```php
// Ejemplo en Teacher
public static $rules = [
    'cdi' => 'required|unique:teachers',
    'email' => 'required|email|unique:teachers',
    'first_name' => 'required|string|max:255',
    'last_name' => 'required|string|max:255'
];
```

## Notas de Implementación

1. Todos los modelos implementan timestamps automáticos
2. Uso de soft deletes en modelos críticos
3. Implementación de factory y seeder para cada modelo
4. Validación a nivel de modelo y controlador
5. Eventos y observadores para acciones críticas
