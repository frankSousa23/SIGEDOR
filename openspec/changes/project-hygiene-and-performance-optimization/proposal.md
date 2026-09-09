## Why

A lo largo del ciclo de desarrollo se han acumulado archivos huérfanos pre-Filament (vistas Blade obsoletas, layouts residuales), prototipos no utilizados de exportación PDF, un comando stub con llamada recursiva y un proveedor de panel no registrado. Asimismo, existen archivos temporales de compilación rastreados indebidamente en Git, reglas de exclusión incompletas en `.gitignore`, comentarios triviales que añaden ruido cognitivo en el código y cuellos de botella de rendimiento por consultas perezosas (*N+1 queries*) en los listados de Filament.

Es necesario sanear el repositorio, asegurar que solo permanezca en local lo que no debe subirse, purgar el código muerto, limpiar los comentarios redundantes y optimizar las consultas y la navegación del sistema.

## What Changes

- **Blindaje de Repositorio y `.gitignore`**:
  - Desvincular y eliminar definitivamente del índice de Git el artefacto de compilación rastreado `vite.config.js.timestamp-1734836980028-1f2a53d87c506.mjs`.
  - Reforzar `.gitignore` para excluir proactivamente cachés de testing (`.pest/`, `coverage/`), archivos de sistema operativo (`Thumbs.db`, `Desktop.ini`, `.DS_Store`), bases de datos locales (`database/*.sqlite*`, `.sqlite-journal`) y respaldos temporales.
  - Eliminar el directorio residual duplicado `.agent/`, consolidando el uso exclusivo de `.agents/`.
- **Saneamiento de Vistas y Código Huérfano**:
  - Purgar 5 vistas Blade monolíticas en desuso heredadas de etapas pre-Filament (`resources/views/admin/dashboard.blade.php`, `resources/views/area-manager/dashboard.blade.php`, `resources/views/teacher/dashboard.blade.php`, `resources/views/layouts/app.blade.php`, `resources/views/reports/export.blade.php`).
  - Purgar 9 plantillas PDF duplicadas o prototipos en `resources/views/pdf/` (`base-simple.blade.php`, `permission_teachers.blade.php`, `permissionsteachers.blade.php`, `teacher-details.blade.php`, `teacher-simple.blade.php`, `teacher.blade.php`, `teachers-list-simple.blade.php`, `teachers-list.blade.php`, `teachers-massive.blade.php`), manteniendo consolidadas las 12 plantillas oficiales activas y probadas.
  - Eliminar el proveedor no registrado y en conflicto `app/Providers/Filament/DashboardPanelProvider.php`.
  - Eliminar el comando stub roto `app/Console/Commands/BackupCommand.php`.
  - Eliminar el modelo pivote huérfano `app/Models/SiteTeacher.php`.
  - Renombrar `tests/Feature/TeacherCreationTest.php` a `tests/Feature/PermissionAndDedicationTest.php` para cumplir estrictamente con los estándares PSR-4 (concordancia entre nombre de archivo y clase).
  - Purgar la prueba unitaria boilerplate vacía `tests/Unit/ExampleTest.php`.
- **Limpieza de Comentarios Excesivos**:
  - Eliminar comentarios de scaffolding obvios (ej. llamadas obvias de constructores, comentarios de placeholders) y código comentado inactivo en proveedores y controladores.
  - Preservar rigurosamente los comentarios de dominio institucional (escalafón UNERG, equivalencias horarias), anotaciones OpenAPI/Swagger y docblocks de análisis estático (PHPStan).
- **Optimización de Rendimiento y Experiencia de Usuario**:
  - Implementar carga ansiosa (*eager loading*) en `getEloquentQuery()` de todos los recursos de Filament (`TeacherResource`, `UserResource`, `ReportResource`, `PermissionTeacherResource`, `SiteResource`, `CategoryResource`, `DedicationResource`) para erradicar las consultas N+1 en tablas administrativas.
  - Activar el modo SPA (`->spa()`) y advertencias de cambios sin guardar (`->unsavedChangesAlerts()`) en `AdminPanelProvider` para transiciones fluidas e instantáneas entre recursos.

## Capabilities

### New Capabilities
<!-- No se introducen nuevas capacidades funcionales de negocio; se optimizan y endurecen las existentes. -->

### Modified Capabilities
- `repository-security-hardening`: Actualiza el requerimiento de exclusión preventiva en `.gitignore` para cubrir artefactos de pruebas, volcados locales de base de datos y archivos de compilación, asegurando la desvinculación de temporales rastreados.
- `ui-ux-enhancements`: Actualiza el requerimiento de navegación y UI para incorporar navegación SPA nativa y erradicar la latencia por consultas N+1 en todas las tablas administrativas mediante eager loading.

## Impact

- **Código y Arquitectura**: Reducción neta de archivos en el repositorio (-17 archivos huérfanos/duplicados), alineación PSR-4 completa en el directorio de pruebas y código fuente más limpio y mantenible.
- **Base de Datos y Rendimiento**: Reducción masiva en el conteo de consultas SQL por renderizado de tabla (de decenas de consultas individuales a 1 o 2 consultas unificadas con `IN (...)`).
- **Navegación**: Transiciones instantáneas entre módulos de Filament sin recargar la página completa ni reevaluar scripts de cabecera.
- **Riesgo**: Cero impacto en las reglas de negocio; compatibilidad total asegurada con las 46 pruebas automatizadas existentes.
