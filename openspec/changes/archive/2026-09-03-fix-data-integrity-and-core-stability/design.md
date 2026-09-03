## Context

Ver `proposal.md` para la motivación.
La auditoría técnica reveló 5 fallas estructurales que comprometen la operabilidad del sistema en producción:
1. Seeding desconectado (0 categorías, 0 dedicaciones, 0 asignaciones) debido a claves `teacher_cdi` dispares.
2. Caídas 500 al emitir expedientes individuales, dedicaciones y reportes en PDF.
3. Brecha de escalada de roles en `UserResource` y omisión de policies en `Dedication` y `PermissionTeacher`.
4. Riesgo de `Data too long` en `reports.report` al usar `VARCHAR(255)` para un `Textarea`.
5. Desalineación del contrato OpenAPI `/api/ping` y endpoints sin rate limiting.

## Goals / Non-Goals

**Goals:**
- Garantizar que `php artisan migrate:fresh --seed` pueble el 100% de las tablas relacionales de docentes y permita el login inmediato de todas las cuentas con `@sigedor.com`.
- Corregir el 100% de las vistas PDF para que rendericen sin errores fatales ni advertencias de PHP 8.3.
- Blindar el control de acceso en Filament: políticas completas, bloqueo de auto-escalada de privilegios y scoping por sede en desplegables.
- Implementar `/api/ping` con rate limiting y limpiar controladores/middleware huérfanos.
- Añadir tests automatizados que cubran la generación de PDFs y la integridad de los seeders.

**Non-Goals:**
- Rediseñar el aspecto visual de los PDFs con nuevo branding (solo corrección de contratos, variables y robustez).
- Reemplazar el motor DomPDF por Browsershot/Puppeteer.
- Crear autenticación OAuth/Sanctum completa para terceros (la API pública actual es de solo lectura y consulta).

## Decisions

### 1. Sincronización Canónica de Claves CDI en CSVs
* **Decisión:** Establecer el rango correlativo `10101001` a `10101025` como identificador institucional canónico en los 4 CSVs (`teachers.csv`, `categories.csv`, `dedications.csv`, `sites.csv`).
* **Justificación:** La arquitectura de SIGEDOR utiliza `teacher_cdi` como clave relacional en el esquema de base de datos (`categories.teacher_cdi`, `dedications.teacher_cdi`, `reports.teacher_cdi`, `permissionsteachers.teacher_cdi`). Mantener la coherencia correlativa permite que cada docente cargado cuente inmediatamente con su escalafón, carga horaria y ubicación territorial.
* **Corrección de correos:** Los docentes en `teachers.csv` tendrán correos con terminación `@sigedor.com` alineados con los nombres anonimizados (`ej: oliver.pena@sigedor.com`), cumpliendo la regla de `Login.php`.

### 2. Corrección del Contrato de Vistas Blade en DomPDF
* **Expediente Individual (`pdf.teacher-individual`):** Se actualiza para leer `$teacher->sede?->nombre` y `$teacher->area?->nombre` directamente del modelo Eloquent `$teacher`, eliminando la dependencia de un arreglo `$relations` externo inexistente.
* **Dedicación (`pdf.dedication`):** Se refactoriza la expresión `match()` para evaluar nombres completos (`'Tiempo Completo'`, `'Exclusiva'`, `'Medio Tiempo'`, `'Tiempo Convencional'`), códigos cortos (`'TC'`, `'EX'`, `'MT'`, `'TCV'`) y un fallback `default => $dedication->name ?? 'Sin Definir'`.
* **Reportes (`pdf.report`, `pdf.reports`):** Se sustituye la llamada errónea `$report->site->name` por `$report->sede?->nombre ?? 'N/A'`.

### 3. Migración de Base de Datos para `reports.report`
* **Decisión:** Crear migración `alter_reports_report_to_text` modificando la columna a `$table->text('report')->nullable()->change()`.
* **Justificación:** Un campo de dictamen o contenido de informe académico no puede estar limitado a 255 caracteres en MySQL.

### 4. Modelo de Políticas y Protección en Filament
* **Nuevas Policies:** Crear `DedicationPolicy` y `PermissionTeacherPolicy` mapeando permisos Spatie (`manage_dedications`, `manage_permissions`) y permitiendo acceso de lectura/edición contextual a `admin` y `area_manager`.
* **Registro:** Agregar `Category::class => CategoryPolicy::class`, `Site::class => SitePolicy::class`, `Dedication::class => DedicationPolicy::class` y `PermissionTeacher::class => PermissionTeacherPolicy::class` en `AuthServiceProvider::$policies`.
* **Escalada de Privilegios:** En `UserResource::form()`, agregar `disabled(fn () => !auth()->user()?->hasRole('admin'))` y `dehydrated(fn () => auth()->user()?->hasRole('admin'))` a los campos `roles` e `is_approved`.
* **Scoping Multi-Inquilino en Formularios:** En lugar de `Teacher::all()`, crear un helper de consulta `Teacher::query()->when(auth()->user()?->isAreaManager(), fn($q) => $q->where('sede_id', auth()->user()->sede_id))->select(['cdi', 'name', 'surName'])` con precarga controlada.

### 5. API REST, Rate Limiting y Limpieza
* **Endpoint `/api/ping`:** Registrar en `routes/api.php` bajo prefijo de API y aplicar `throttle:60,1`. Retorna `{ "status": "pong", "timestamp": "...", "version": "1.0.0" }`.
* **Depuración de Código Muerto:** Eliminar `app/Http/Controllers/UserController.php`, `app/Http/Controllers/HomeController.php` y `app/Http/Middleware/AreaManagerMiddleware.php`.
* **CategoryObserver:** Cambiar el array `$categoryHierarchy` a TitleCase (`'Titular' => 'titular'`, etc.) para coincidir con la base de datos y los badges de Filament.

## Risks / Trade-offs

- **[Riesgo] Modificación de tipos en migraciones con SQLite/MySQL** → `doctrine/dbal` ya no es estrictamente requerido en Laravel 11 para `change()`, pero la migración debe incluir bloques seguros y reversibles.
- **[Riesgo] Tests existentes de CategoryTest** → `CategoryTest` valida promociones directas; la normalización a TitleCase en el Observer preservará la compatibilidad total con `CategoryTest`.

## Migration Plan

1. Actualizar `database/seeders/data/teachers.csv` sincronizando CDIs con los demás CSVs y correos `@sigedor.com`.
2. Actualizar `CategorySeeder` quitando `disable_assistant_rule`.
3. Crear migración de columna `report` en tabla `reports`.
4. Corregir plantillas Blade de PDFs y recursos Filament asociados.
5. Crear y registrar Policies en `AuthServiceProvider`.
6. Proteger `UserResource` y optimizar desplegables `teacher_cdi`.
7. Registrar `/api/ping`, rate limiting y eliminar controladores muertos.
8. Crear tests para PDFs y seeders; verificar paso del 100% de la suite.
