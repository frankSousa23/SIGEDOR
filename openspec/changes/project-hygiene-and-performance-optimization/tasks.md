## 1. Repositorio, Git y Blindaje de Archivos Locales

- [x] 1.1 Desvincular de Git y eliminar `vite.config.js.timestamp-1734836980028-1f2a53d87c506.mjs` y verificar que no aparezca en `git status`
- [x] 1.2 Actualizar `.gitignore` con exclusiones estrictas de cachés (`.pest/`, `coverage/`, `.phpunit.cache`), SQLite local (`database/*.sqlite*`, `.sqlite-journal`) y archivos de SO (`Thumbs.db`, `.DS_Store`), verificando que no se capturen archivos espurios
- [x] 1.3 Eliminar el directorio residual `.agent/` redundante y verificar la permanencia intacta de `.agents/`

## 2. Purga y Saneamiento de Archivos Huérfanos y Duplicados

- [x] 2.1 Eliminar las 5 vistas Blade legacy huérfanas (`resources/views/admin/dashboard.blade.php`, `resources/views/area-manager/dashboard.blade.php`, `resources/views/teacher/dashboard.blade.php`, `resources/views/layouts/app.blade.php`, `resources/views/reports/export.blade.php`) y verificar que no existan referencias rotas
- [x] 2.2 Purgar las 9 plantillas PDF duplicadas/huérfanas en `resources/views/pdf/` (`base-simple`, `permission_teachers`, `permissionsteachers`, `teacher-details`, `teacher-simple`, `teacher`, `teachers-list-simple`, `teachers-list`, `teachers-massive`) y verificar que las 12 plantillas activas permanezcan funcionales
- [x] 2.3 Eliminar las clases PHP huérfanas `app/Providers/Filament/DashboardPanelProvider.php`, `app/Console/Commands/BackupCommand.php` y el modelo `app/Models/SiteTeacher.php`, verificando que no queden llamadas ni registros residuales
- [x] 2.4 Renombrar `tests/Feature/TeacherCreationTest.php` a `tests/Feature/PermissionAndDedicationTest.php` para cumplir PSR-4 y purgar la prueba boilerplate `tests/Unit/ExampleTest.php`, verificando la suite con Pest

## 3. Limpieza de Comentarios y Optimización de Rendimiento

- [x] 3.1 Limpiar comentarios triviales de scaffolding y código comentado inactivo en proveedores y controladores, preservando contratos de dominio UNERG, tipados PHPStan y anotaciones OpenAPI
- [x] 3.2 Implementar *eager loading* en `getEloquentQuery()` de `TeacherResource`, `UserResource`, `ReportResource`, `PermissionTeacherResource`, `SiteResource`, `CategoryResource` y `DedicationResource` para erradicar consultas N+1 en tablas administrativas
- [x] 3.3 Habilitar el modo SPA (`->spa()`) y advertencias de cambios sin guardar (`->unsavedChangesAlerts()`) en `AdminPanelProvider`

## 4. Verificación Integral y Calidad de Código

- [x] 4.1 Ejecutar `vendor/bin/pint` para validar el formateo y estilo de código de los archivos modificados
- [x] 4.2 Ejecutar la suite completa de pruebas automatizadas con Pest y verificar que todas las pruebas pasen satisfactoriamente
- [x] 4.3 Validar la compilación limpia de cachés con `artisan route:cache` y `artisan config:cache`
