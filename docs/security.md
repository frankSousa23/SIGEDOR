# Características de Seguridad

## 1. Autenticación

### Sistema de Login
```php
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $maxAttempts = 5;
    protected $decayMinutes = 10;

    protected function authenticated(Request $request, $user)
    {
        activity()
            ->causedBy($user)
            ->log('login');
    }
}
```

### Características
- Límite de intentos
- Bloqueo temporal
- Registro de actividad
- Tokens seguros
- Sesiones cifradas

### Protección de Rutas
```php
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', DashboardController::class);
    Route::resource('teachers', TeacherController::class);
});
```

## 2. Autorización

### Spatie Laravel-Permission
```php
class User extends Authenticatable
{
    use HasRoles;
}
```

### Roles Predefinidos
1. Super Admin
   - Acceso total
   - Gestión de roles
   - Configuración sistema

2. Admin
   - Gestión usuarios
   - Reportes completos
   - Aprobaciones

3. Supervisor
   - Aprobación permisos
   - Reportes limitados
   - Seguimiento

4. Operador
   - Registro básico
   - Consultas
   - Reportes básicos

### Permisos
```php
return [
    'view_teachers',
    'create_teachers',
    'edit_teachers',
    'delete_teachers',
    'approve_permissions',
    'view_reports',
    'manage_users',
    'manage_roles'
];
```

### Implementación
```php
class TeacherPolicy
{
    public function view(User $user, Teacher $teacher)
    {
        return $user->hasPermissionTo('view_teachers');
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('create_teachers');
    }
}
```

## 3. Validación de Datos

### Reglas de Validación
```php
class TeacherRequest extends FormRequest
{
    public function rules()
    {
        return [
            'cdi' => 'required|unique:teachers,cdi,'.$this->id,
            'email' => 'required|email|unique:teachers,email,'.$this->id,
            'password' => [
                'required',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
            ]
        ];
    }
}
```

### Sanitización
```php
class Teacher extends Model
{
    protected $casts = [
        'email' => 'string',
        'phone' => 'string',
        'address' => 'string'
    ];

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }
}
```

## 4. Protección CSRF

### Middleware
```php
protected $middleware = [
    \App\Http\Middleware\VerifyCsrfToken::class,
];
```

### Implementación en Formularios
```html
<form method="POST" action="/teacher">
    @csrf
    <!-- campos del formulario -->
</form>
```

## 5. Protección XSS

### Escape Automático
```php
{{ $variable }}  // Escapado automático
{!! $variable !!}  // Sin escapar (usar con precaución)
```

### Middleware de Seguridad
```php
protected $middleware = [
    \App\Http\Middleware\TrimStrings::class,
    \App\Http\Middleware\ConvertEmptyStringsToNull::class,
];
```

## 6. Auditoría

### Registro de Actividad
```php
class Teacher extends Model
{
    use \Spatie\Activitylog\Traits\LogsActivity;

    protected static $logAttributes = [
        'cdi',
        'email',
        'first_name',
        'last_name'
    ];

    protected static $logOnlyDirty = true;
}
```

### Eventos Registrados
- Accesos al sistema
- Cambios en registros
- Operaciones críticas
- Errores de seguridad

## 7. Encriptación

### Configuración
```php
config([
    'app.cipher' => 'AES-256-CBC',
    'app.key' => env('APP_KEY')
]);
```

### Campos Sensibles
```php
class Teacher extends Model
{
    protected $encrypted = [
        'cdi',
        'address'
    ];
}
```

## 8. Seguridad en Base de Datos

### Migraciones Seguras
```php
Schema::create('teachers', function (Blueprint $table) {
    $table->id();
    $table->string('cdi')->unique();
    $table->string('email')->unique();
    $table->string('password');
    $table->timestamps();
    $table->softDeletes();
});
```

### Índices y Constraints
```php
Schema::table('teachers', function (Blueprint $table) {
    $table->index(['email', 'cdi']);
    $table->foreign('site_id')
          ->references('id')
          ->on('sites')
          ->onDelete('restrict');
});
```

## 9. API Security

### Autenticación API
```php
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('teachers', TeacherApiController::class);
});
```

### Rate Limiting
```php
Route::middleware(['throttle:api'])->group(function () {
    Route::get('/api/teachers', [TeacherApiController::class, 'index']);
});
```

## 10. Configuración del Servidor

### Headers de Seguridad
```php
Header set X-Frame-Options "SAMEORIGIN"
Header set X-XSS-Protection "1; mode=block"
Header set X-Content-Type-Options "nosniff"
```

### SSL/TLS
- Certificados válidos
- Forzar HTTPS
- Configuración segura

## 11. Backup y Recuperación

### Configuración
```php
return [
    'backup' => [
        'name' => 'SIGEDOR',
        'source' => [
            'files' => [
                base_path(),
            ],
            'databases' => [
                'mysql',
            ],
        ],
    ],
];
```

### Programación
- Backups diarios
- Retención 30 días
- Verificación integridad

## Mejores Prácticas

1. Autenticación
   - Contraseñas fuertes
   - Doble factor (2FA)
   - Sesiones seguras

2. Autorización
   - Principio de mínimo privilegio
   - Roles granulares
   - Validación constante

3. Datos
   - Validación estricta
   - Sanitización input
   - Encriptación sensible

4. Monitoreo
   - Logs detallados
   - Alertas seguridad
   - Auditoría regular

5. Mantenimiento
   - Actualizaciones regulares
   - Parches seguridad
   - Pruebas periódicas
