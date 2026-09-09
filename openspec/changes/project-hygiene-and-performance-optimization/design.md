## Context

El proyecto SIGEDOR es una aplicación basada en Laravel 11 y Filament 3. A lo largo de iteraciones previas se han migrado componentes legacy a Filament, dejando archivos blade huérfanos, plantillas PDF duplicadas y providers abandonados. Además, las tablas de Filament actualmente realizan consultas perezosas (*lazy loading*) sobre las relaciones de docentes, sedes y áreas, produciendo el clásico problema de N+1 queries. Véase `proposal.md` para el contexto y motivación completa.

## Goals / Non-Goals

**Goals:**
- Desvincular del control de versiones cualquier artefacto temporal (`vite.config.js.timestamp-*`) y blindar `.gitignore` contra cachés de testing, bases de datos locales y archivos de sistema operativo.
- Eliminar de manera segura los 17 archivos redundantes/muertos identificados (5 vistas Blade legacy, 9 plantillas PDF duplicadas, `DashboardPanelProvider`, `BackupCommand` y `SiteTeacher`).
- Corregir la desalineación PSR-4 en `tests/Feature/TeacherCreationTest.php` renombrándolo a `PermissionAndDedicationTest.php`.
- Depurar comentarios obvios de scaffolding y código muerto comentado, salvaguardando las anotaciones OpenAPI, tipados de PHPStan y documentación de dominio UNERG.
- Implementar *eager loading* en `getEloquentQuery()` de todos los Resources de Filament para reducir drásticamente las consultas a base de datos.
- Habilitar el modo SPA (`->spa()`) y advertencias de cambios sin guardar (`->unsavedChangesAlerts()`) en `AdminPanelProvider`.

**Non-Goals:**
- No alterar las firmas ni la estructura de las rutas de la API pública v1 (`/api/v1/*`).
- No modificar el esquema de base de datos ni agregar migraciones destructivas.
- No alterar las reglas de autorización RBAC ni las políticas de seguridad existentes.

## Decisions

### 1. Desvinculación de Temporales y Blindaje de `.gitignore`
- **Decisión**: Ejecutar `git rm --cached` sobre `vite.config.js.timestamp-*` y agregar reglas granulares en `.gitignore` para cubrir `.pest/`, `coverage/`, `.phpunit.cache`, `*.sqlite*` y archivos del sistema operativo (`Thumbs.db`, `.DS_Store`).
- **Alternativa considerada**: Ignorar únicamente archivos futuros; se descartó porque Git continuaría rastreando el archivo de timestamp ya presente en el índice.

### 2. Purga y Consolidación de Plantillas PDF
- **Decisión**: Eliminar las 9 plantillas huérfanas/duplicadas en `resources/views/pdf/` conservando exclusivamente las 12 plantillas activas y verificadas en `tests/Feature/PdfGenerationTest.php`.
- **Alternativa considerada**: Mantener las plantillas simples como "fallback"; se descartó porque ninguna acción ni relación las invoca y duplican lógica desactualizada.

### 3. Saneamiento de Clases PHP Obsoletas y Alineación PSR-4
- **Decisión**:
  - Eliminar `app/Providers/Filament/DashboardPanelProvider.php` (duplicado no registrado).
  - Eliminar `app/Console/Commands/BackupCommand.php` (comando stub con llamada recursiva rota).
  - Eliminar `app/Models/SiteTeacher.php` (modelo huérfano sin uso).
  - Renombrar `tests/Feature/TeacherCreationTest.php` a `tests/Feature/PermissionAndDedicationTest.php` para cumplir con PSR-4.
- **Alternativa considerada**: Reparar `BackupCommand`; se descartó porque no forma parte del alcance operativo actual y el paquete Spatie Backup no está configurado.

### 4. Erradicación de Consultas N+1 en Filament
- **Decisión**: Añadir `with([...])` en `getEloquentQuery()` de los recursos principales:
  - `TeacherResource`: `with(['sede', 'area', 'category', 'dedication'])`
  - `UserResource`: `with(['roles', 'sede', 'area'])`
  - `ReportResource`: `with(['teacher', 'sede', 'area'])`
  - `PermissionTeacherResource`: `with(['teacher'])`
  - `SiteResource`: Implementar `getEloquentQuery()` con `with(['teacher', 'sede', 'area', 'programa'])`
  - `CategoryResource` / `DedicationResource`: `with(['teacher'])`
- **Alternativa considerada**: Configurar `relationship('...')` en columnas individuales únicamente; se descartó porque `getEloquentQuery()` asegura que cualquier acción, filtro o renderizado reutilice el eager loading sin queries adicionales.

### 5. Modo SPA en Filament Panel
- **Decisión**: Invocar `->spa()` y `->unsavedChangesAlerts()` en `AdminPanelProvider`.
- **Alternativa considerada**: Mantener navegación estándar por recarga completa; se descartó porque el modo SPA optimiza la respuesta percibida por el usuario y elimina parpadeos.

## Risks / Trade-offs

- **[Riesgo] Eliminación accidental de una vista PDF requerida** → **Mitigación**: Ejecutar la suite completa de pruebas Pest (`tests/Feature/PdfGenerationTest.php`), que valida la generación real de todos los PDFs activos.
- **[Riesgo] Eager loading consumiendo memoria excesiva** → **Mitigación**: Filament pagina todas las tablas por defecto (10 a 25 registros por página), por lo que el eager loading carga un número acotado de modelos relacionados, optimizando drásticamente la latencia de base de datos.
- **[Riesgo] Incompatibilidad de navegación SPA con scripts externos** → **Mitigación**: La suite de vistas y widgets utiliza exclusivamente componentes nativos de Filament y Blade Icons ya registrados.
