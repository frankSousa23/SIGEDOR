## Why

El módulo de reportes de SIGEDOR adolece de inconsistencias de seguridad y limitaciones visuales e institucionales:
1. `ReportPolicy` y `ReportResource` no aíslan adecuadamente las consultas para usuarios con rol `teacher`, permitiendo a docentes visualizar o intentar emitir documentos de toda la universidad.
2. Los formatos PDF actuales carecen de membrete oficial jerárquico de la UNERG, bloques de firmas reglamentarias, códigos únicos de verificación documental y especialización por tipo de trámite (constancias de trabajo, memorandos, dictámenes de escalafón).
3. El módulo de reportes opera de forma aislada, sin acceso directo desde el expediente del docente ni integración con permisos aprobados o el escritorio principal.

Esta modernización establece un estándar institucional seguro, auditable y con identidad gráfica fidedigna para la Universidad Nacional Experimental "Rómulo Gallegos".

## What Changes

- **Seguridad y Control de Acceso por Roles (RBAC)**:
  - Corrección de `ReportPolicy` asociando la autorización del docente a través de su cédula (`teacher_cdi`) y usuario vinculado.
  - Aislamiento estricto en `ReportResource::getEloquentQuery()`: Administrador (acceso total), Jefe de Área (solo su sede y área) y Docente (exclusivamente sus propios reportes y constancias).
  - Formulario inteligente: preselección y bloqueo del docente autenticado cuando accede un usuario con rol `teacher`.
- **Capa de Datos y Validación Documental**:
  - Nueva migración en `reports` añadiendo `verification_code` (código de autenticidad único), `created_by` (usuario emisor) y `status` (borrador, emitido, anulado).
- **Estandarización del Motor PDF Institucional UNERG**:
  - Rediseño de `resources/views/pdf/base.blade.php` con membrete jerárquico formal (República, Ministerio, UNERG, Vicerrectorado Académico), logotipo nítido (`LogoUnerg.png`), código de validación, fecha/hora legal venezolana (`America/Caracas`) y paginación formal.
  - Formatos especializados en `resources/views/pdf/report.blade.php`: redacción formal de Constancia de Trabajo, estructura de Memorando Administrativo, desglose de Carga Horaria e Informe de Escalafón.
  - Bloque reglamentario de doble y triple firma con sello húmedo (Jefe de Área, Vicerrectorado Académico/RRHH, Docente).
  - Rediseño de la exportación masiva `resources/views/pdf/reports.blade.php` heredando de la plantilla base en orientación apaisada.
- **Interconexión del Ecosistema de Reportes**:
  - `TeacherResource`: nuevo `ReportsRelationManager` en el expediente individual y acción rápida en tabla para emitir/descargar constancia de trabajo en 1-clic.
  - `PermissionTeacherResource`: acción para emitir memorando oficial tras la aprobación de un permiso.
  - `Dashboard`: widget de últimos reportes emitidos para agilizar el flujo de trabajo de administradores y coordinadores.

## Capabilities

### New Capabilities
- `academic-report-integration`: Integración bidireccional del módulo de reportes con el expediente del docente (`ReportsRelationManager`, emisión rápida de constancias), emisión de memorandos desde permisos aprobados y widget de monitoreo en el escritorio.

### Modified Capabilities
- `document-pdf-export`: Incorporación de membrete institucional UNERG, códigos únicos de verificación documental, formatos especializados por tipo de informe (Constancia, Memorando, Escalafón) y bloques de firmas reglamentarias.
- `role-policy-authorization`: Enforzamiento del aislamiento multi-inquilino y políticas de consulta/emisión de reportes por rol (Docente limitado a sus propios documentos, Jefe de Área limitado a su sede).

## Impact

- **Modelos y Base de Datos**: `Report` (`verification_code`, `created_by`, `status`), relaciones en `Teacher` y `User`.
- **Filament**: `ReportResource`, `TeacherResource` (nuevo `ReportsRelationManager`), `PermissionTeacherResource`, `Dashboard` (nuevo widget).
- **Vistas**: `resources/views/pdf/base.blade.php`, `report.blade.php`, `reports.blade.php` y vistas de exportación masiva.
- **Pruebas Automatizadas**: Actualización y ampliación de `tests/Feature/PdfGenerationTest.php` y creación de `tests/Feature/ReportSecurityTest.php`.
