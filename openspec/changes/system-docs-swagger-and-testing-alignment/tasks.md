## 1. API Pública y Documentación Swagger / OpenAPI

- [ ] 1.1 Exponer `verification_code`, `status` y `created_by` en `ReportPublicResource.php` y verificar la serialización JSON del endpoint.
- [ ] 1.2 Actualizar el esquema Swagger en `app/Virtual/Models/Report.php` con las propiedades de autenticidad institucional, estado y autoría.
- [ ] 1.3 Crear `App\Http\Controllers\Api\HealthApiController` con anotaciones OpenAPI `@OA\Get` para `/api/ping` y actualizar `routes/api.php`.
- [ ] 1.4 Regenerar la especificación Swagger ejecutando `php artisan l5-swagger:generate` y verificar la presencia de `/api/ping` y los nuevos campos en `storage/api-docs/api-docs.json`.

## 2. Sincronización Fiel de Documentación Técnica (`docs/`)

- [ ] 2.1 Reescribir `docs/models.md` reflejando con exactitud los atributos, casts, hooks y relaciones reales de `Teacher`, `Category`, `Dedication`, `Site`, `PermissionTeacher`, `Report`, `User`, `Sede`, `Area` y `Programa`.
- [ ] 2.2 Reescribir `docs/database-schema.md` documentando las 24 migraciones reales, eliminando campos ficticios y detallando el esquema jerárquico UNERG.
- [ ] 2.3 Actualizar `docs/filament-resources.md` documentando el Wizard de 3 pasos, los 4 RelationManagers de `TeacherResource`, `ReportResource`, `UserResource` y los 5 widgets activos del panel.
- [ ] 2.4 Actualizar `docs/security.md` y `docs/system-flow.md` reflejando los 3 roles reales (`admin`, `area_manager`, `teacher`), el aislamiento multi-inquilino por sede y la sincronización bidireccional de expedientes.
- [ ] 2.5 Actualizar `docs/architecture.md` y `docs/ui.md` reflejando la arquitectura actual de Laravel 11 + Filament v3 y los widgets del escritorio.

## 3. Actualización de `README.md` y PHPDoc de Dominio

- [ ] 3.1 Actualizar `README.md` sincronizando los conteos de migraciones (24), guías (15) y destacando en las características principales el expediente docente 360° y la sincronización automática de escalafón y dedicación.
- [ ] 3.2 Enriquecer los docblocks y comentarios de dominio en `app/Models/Site.php` y `app/Filament/Resources/TeacherResource.php` clarificando la distinción entre Cátedra/Carga horaria (`Site`) y Recinto territorial universitario (`Sede`).

## 4. Pruebas Automatizadas y Verificación Integral

- [ ] 4.1 Ampliar `tests/Feature/ApiTest.php` validando que la respuesta de `/api/v1/reports` contenga `verification_code` y `status`.
- [ ] 4.2 Ejecutar la suite completa de pruebas Pest (`vendor/bin/pest`) confirmando que el 100% de las pruebas aprueben satisfactoriamente.
- [ ] 4.3 Ejecutar `vendor/bin/pint --test` y `openspec validate --all` confirmando el cumplimiento de los estándares de formato y especificaciones OpenSpec.
