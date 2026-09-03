## Why

La auditoría exhaustiva del sistema reveló múltiples puntos de falla críticos:
1. Las relaciones entre los datasets CSV de seeders están rotas debido a discrepancias en las cédulas (`cdi`), causando que las tablas de categorías, dedicaciones y asignaciones queden vacías (`0 registros`) tras el seed. Además, los correos generados no pertenecen a `@sigedor.com`, impidiendo el inicio de sesión.
2. Tres acciones de generación de reportes en PDF (`pdf_individual` en docentes, dedicaciones y reportes) arrojan errores fatales en tiempo de ejecución (HTTP 500) por variables no definidas (`$relations`), discrepancias en el enum `match()` y relaciones inexistentes (`$report->site->name`).
3. Existen brechas de autorización en Filament: los modelos `Dedication` y `PermissionTeacher` carecen de policies, un Jefe de Área puede elevar privilegios de usuarios en `UserResource`, y los desplegables de docentes en formularios cargan la totalidad de registros institucionales sin aislamiento por sede ni límites de memoria.
4. La columna `reports.report` está configurada como `VARCHAR(255)`, lo que ocasiona caídas en MySQL al ingresar dictámenes en el `Textarea`.
5. La ruta `@OA\Get(path="/api/ping")` documentada en Swagger no existe en el enrutador de la API.

Este cambio resuelve estos problemas estructurales para garantizar estabilidad, seguridad y coherencia en todo el ciclo de vida del sistema.

## What Changes

- **Integridad de Datos y Seeders:**
  - Sincronizar las cédulas (`cdi`) en `teachers.csv`, `categories.csv`, `dedications.csv` y `sites.csv` para que coincidan de forma consistente (`10101001` - `10101025`).
  - Corregir los correos de docentes en `teachers.csv` para pertenecer al dominio `@sigedor.com`.
  - Actualizar `CategorySeeder` eliminando referencias a la columna deprecada `disable_assistant_rule`.
- **Generación y Exportación de PDFs:**
  - Corregir `TeacherResource` y `pdf.teacher-individual` para renderizar datos institucionales (`sede`, `area`) directamente desde el modelo sin requerir `$relations`.
  - Corregir `pdf.dedication` para soportar de forma segura tanto nombres completos (`Tiempo Completo`, `Exclusiva`, etc.) como siglas, con rama `default`.
  - Corregir `pdf.report` y `pdf.reports` reemplazando `$report->site->name` por `$report->sede->nombre`.
  - Crear migración para cambiar `reports.report` de `VARCHAR(255)` a `TEXT` nullable.
- **Seguridad, Policies y Multi-Tenancy:**
  - Crear `DedicationPolicy` y `PermissionTeacherPolicy` con control granular por roles.
  - Registrar `DedicationPolicy`, `PermissionTeacherPolicy`, `CategoryPolicy` y `SitePolicy` en `AuthServiceProvider`.
  - Restringir los campos `roles` e `is_approved` en `UserResource` para que solo sean editables por usuarios con rol `admin`.
  - En los formularios de Filament (`CategoryResource`, `DedicationResource`, `PermissionTeacherResource`, `ReportResource`, `SiteResource`), aislar las opciones de `teacher_cdi` según la sede del usuario autenticado si es `area_manager`.
- **API y Limpieza General:**
  - Registrar e implementar la ruta de verificación `/api/ping` con rate limiting `throttle:api`.
  - Normalizar `CategoryObserver` para persistir la categoría en TitleCase (`Titular`, `Asociado`...) y asegurar la correspondencia con los badges de la UI.
  - Eliminar artefactos huérfanos sin rutas (`UserController.php`, `HomeController.php`, `AreaManagerMiddleware.php`).
- **Testing:**
  - Implementar pruebas automatizadas de renderizado de PDFs y verificación de integridad relacional en seeders.

## Capabilities

### New Capabilities
- `data-seeder-integrity`: Asegura que la ingesta de datos iniciales por CSV se complete con el 100% de integridad relacional entre docentes, categorías, dedicaciones y sedes, con correos bajo `@sigedor.com`.
- `document-pdf-export`: Garantiza la emisión libre de excepciones (HTTP 500) y con formato institucional de expedientes docentes, dedicaciones, permisos y reportes en PDF.
- `role-policy-authorization`: Implementa políticas de autorización completas en Filament, previene escalada de privilegios en gestión de usuarios y aísla selecciones por sede para jefes de área.

### Modified Capabilities
- `public-api-v1`: Incorpora la ruta `/api/ping` con verificación de salud del sistema y rate limiting en endpoints públicos.

## Impact

- **Modelos y Políticas:** Creación de `DedicationPolicy` y `PermissionTeacherPolicy`; actualización de `AuthServiceProvider`.
- **Base de Datos:** Nueva migración alterando `reports.report` a tipo `TEXT`.
- **Seeders y Datos:** Corrección de `teachers.csv` y `CategorySeeder`.
- **Vistas:** Corrección de plantillas Blade en `resources/views/pdf/`.
- **Controladores y Rutas:** Implementación de endpoint ping y aplicación de throttle en `routes/api.php`; eliminación de controladores obsoletos.
- **Tests:** Nuevos tests en `tests/Feature/` para PDFs, seeders y endpoint ping.
