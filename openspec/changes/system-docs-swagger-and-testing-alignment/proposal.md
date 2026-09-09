## Why

A lo largo de la evolución de SIGEDOR y los recientes rediseños de reportes institucionales UNERG, políticas RBAC, sincronización de expedientes 360° y seguridad multi-inquilino, varias áreas periféricas han quedado desfasadas respecto a la realidad del código fuente:
1. La documentación en `docs/` contiene esquemas y modelos genéricos de plantilla previa con atributos y clases inexistentes (`first_name`, `last_name`, `address`, `TeacherObserver`, `LoginController`).
2. La API pública RESTful (`ReportPublicResource`) y su especificación OpenAPI/Swagger (`App\Virtual\Models\Report`) no exponen los atributos de autenticidad y trazabilidad recién incorporados (`verification_code`, `status`, `created_by`), y el endpoint `/api/ping` carece de anotaciones Swagger.
3. El archivo `README.md` tiene conteos y descripciones desactualizadas (20 migraciones vs 24 reales; 12 guías vs 15 reales).
4. La suite de pruebas puede expandirse para blindar los nuevos contratos de la API y los RelationManagers de Filament.

Esta propuesta sincroniza de forma integral y secuencial toda la documentación técnica, esquemas OpenAPI, comentarios PHPDoc y pruebas automatizadas con el estado operativo actual del sistema.

## What Changes

- **Sincronización Integral de Documentación Técnica (`docs/`)**:
  - Reescritura fiel de `docs/models.md` y `docs/database-schema.md` reflejando los atributos reales de `Teacher`, `Category`, `Dedication`, `Site`, `PermissionTeacher`, `Report`, `User`, `Sede`, `Area` y `Programa`.
  - Actualización de `docs/filament-resources.md` documentando el Wizard de 3 pasos, los 4 RelationManagers de `TeacherResource` (`Permissions`, `Categories`, `Dedications`, `Reports`), `ReportResource` y `UserResource`.
  - Actualización de `docs/security.md` y `docs/system-flow.md` reflejando los 3 roles reales (`admin`, `area_manager`, `teacher`), el aislamiento por sede y la sincronización automática de claves foráneas.
  - Actualización de `docs/architecture.md` y `docs/ui.md` con los 5 widgets activos del Dashboard y la pila tecnológica moderna.
- **Actualización de `README.md`**:
  - Corrección de métricas del proyecto (24 migraciones, 15 guías documentadas).
  - Incorporación en las características destacadas del expediente docente 360° y la sincronización automática de escalafón y dedicación.
- **Actualización de OpenAPI / Swagger y Recursos API**:
  - Exposición de `verification_code`, `status` y `created_by` en `app/Http/Resources/Api/ReportPublicResource.php`.
  - Actualización del esquema Swagger en `app/Virtual/Models/Report.php`.
  - Creación de anotación OpenAPI o controlador para documentar formalmente `GET /api/ping` en Swagger UI.
  - Regeneración del artefacto OpenAPI (`php artisan l5-swagger:generate`).
- **Comentarios y PHPDoc en Bloques Clave**:
  - Clarificación del dominio entre `Site` (asignación de carga y cátedra) y `Sede` (recinto territorial físico universitario) en modelos y políticas.
- **Ampliación de la Suite de Pruebas (Testing)**:
  - Adición de aserciones en `tests/Feature/ApiTest.php` para validar la presencia de `verification_code` y `status` en la respuesta de reportes.
  - Creación de prueba para verificar la integridad de la documentación autogenerada de Swagger y el endpoint de healthcheck.

## Capabilities

### Modified Capabilities
- `public-api-v1`: Actualización de los requerimientos de entrega de datos en reportes académicos (código de autenticidad y estado) y cobertura completa de endpoints y esquemas en OpenAPI/Swagger.

## Impact

- **Código Afectado**:
  - `docs/*.md` (7 archivos de documentación técnica reescritos y alineados).
  - `README.md` (métricas y características sincronizadas).
  - `app/Http/Resources/Api/ReportPublicResource.php` y `app/Virtual/Models/Report.php`.
  - `app/Http/Controllers/Api/` y `routes/api.php`.
  - `tests/Feature/ApiTest.php`.
- **APIs**: La respuesta de `GET /api/v1/reports` entregará `verification_code`, `status` y `created_by` enriqueciendo la integración con terceros sin romper compatibilidad hacia atrás.
- **Dependencias**: Ninguna nueva dependencia requerida.
