## 1. Integridad de Datos y Seeders

- [x] 1.1 Sincronizar las cédulas (`cdi`) en `database/seeders/data/teachers.csv` al rango `10101001` - `10101025` y normalizar los correos al dominio `@sigedor.com` (ej: `oliver.pena@sigedor.com`), alineándolos con los usuarios en `users.csv`.
- [x] 1.2 Actualizar `database/seeders/CategorySeeder.php` para remover la asignación del campo inexistente `disable_assistant_rule`.
- [x] 1.3 Actualizar el comando `AnonymizeTeachersCsv.php` para preservar las cédulas canónicas y generar correos exclusivos `@sigedor.com`.
- [x] 1.4 Ejecutar `php artisan migrate:fresh --seed` y verificar en consola que se procesen 25 categorías, 25 dedicaciones y 25 asignaciones de sede con 100% de éxito.

## 2. Robustez en Generación de PDFs y Migración de Base de Datos

- [x] 2.1 Crear la migración `alter_reports_report_to_text` modificando la columna `report` en la tabla `reports` a tipo `text` nullable, y ejecutar `php artisan migrate`.
- [x] 2.2 Corregir `resources/views/pdf/teacher-individual.blade.php` para leer `$teacher->sede?->nombre` y `$teacher->area?->nombre` directamente del modelo sin requerir `$relations`.
- [x] 2.3 Refactorizar `resources/views/pdf/dedication.blade.php` para que la expresión `match($dedication->name)` contemple tanto los nombres descriptivos completos como las siglas, añadiendo una rama `default` para evitar `UnhandledMatchError`.
- [x] 2.4 Corregir `resources/views/pdf/report.blade.php` y `resources/views/pdf/reports.blade.php` reemplazando `$report->site->name` por `$report->sede?->nombre ?? 'N/A'`.
- [x] 2.5 Verificar la generación de PDFs descargando en memoria cada tipo de documento sin arrojar excepciones.

## 3. Seguridad, Policies y Control de Acceso Multi-Inquilino

- [x] 3.1 Crear `app/Policies/DedicationPolicy.php` y `app/Policies/PermissionTeacherPolicy.php` con autorización granular por roles (`admin` y `area_manager`).
- [x] 3.2 Registrar `DedicationPolicy`, `PermissionTeacherPolicy`, `CategoryPolicy` y `SitePolicy` en el array `$policies` de `app/Providers/AuthServiceProvider.php`.
- [x] 3.3 En `app/Filament/Resources/UserResource.php`, aplicar `disabled` y `dehydrated` sobre `roles` e `is_approved` condicionados al rol `admin`, e incorporar validación del dominio `@sigedor.com` en el campo `email`.
- [x] 3.4 Actualizar la selección de `teacher_cdi` en `CategoryResource`, `DedicationResource`, `PermissionTeacherResource`, `ReportResource` y `SiteResource` para filtrar por sede si el usuario es `area_manager` y evitar `Teacher::all()`.

## 4. API REST, Limpieza de Código y Observabilidad

- [x] 4.1 Registrar la ruta `GET /api/ping` en `routes/api.php` con middleware `throttle:60,1` y verificar que responde con status 200 y confirmación JSON.
- [x] 4.2 Aplicar middleware de rate limiting a las rutas de `routes/api.php` (`/v1/teachers` y `/v1/reports`).
- [x] 4.3 Actualizar `app/Observers/CategoryObserver.php` para persistir `$category->current_category` en TitleCase (`Titular`, `Asociado`, etc.) garantizando coherencia con los badges de Filament.
- [x] 4.4 Eliminar los archivos obsoletos y sin rutas asociadas: `app/Http/Controllers/UserController.php`, `app/Http/Controllers/HomeController.php` y `app/Http/Middleware/AreaManagerMiddleware.php`.
- [x] 4.5 Limpiar `composer.json` removiendo la dependencia redundante `"illuminate/queue": "*"`.

## 5. Pruebas Automatizadas y Verificación Final

- [x] 5.1 Crear `tests/Feature/PdfGenerationTest.php` validando que la emisión de expedientes individuales, dedicaciones, permisos y reportes se ejecute con código 200 sin errores 500.
- [x] 5.2 Crear `tests/Feature/SeederIntegrityTest.php` validando que tras el seeder todos los docentes posean categoría, dedicación y asignación de sede vinculada.
- [x] 5.3 Crear test para el endpoint `GET /api/ping` y su limitación de tasa.
- [x] 5.4 Ejecutar `vendor/bin/pest` y `vendor/bin/pint --test`, verificando que el 100% de los tests pasen en verde y el código esté perfectamente formateado.
