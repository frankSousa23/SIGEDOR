## Why

La auditoría integral del ciclo de vida de datos en SIGEDOR evidenció desconexiones relacionales entre docentes y sus componentes académicos (categoría, dedicación y carga en sede que aparecen como "Sin asignar"), una brecha de escalada de privilegios en la tabla de usuarios donde jefes de área pueden aprobar cuentas mediante `ToggleColumn`, errores críticos en políticas de seguridad (`$user->cdi` inexistente y lecturas sin nullsafe), un fallo fatal al crear permisos por omisión del campo `name`, y la posibilidad de que los docentes auto-aprueben sus propias solicitudes de permisos. Esta propuesta rediseña secuencialmente el flujo completo de datos, garantizando integridad referencial bidireccional, blindaje estricto de roles (RBAC) y un expediente docente digital 360°.

## What Changes

- **Blindaje de Aprobación de Usuarios (RBAC)**: Deshabilitar la edición en línea (`ToggleColumn`) de `is_approved` e `is_active` en `UserResource` para roles no administradores y alinear la visibilidad en `Navigation.php`.
- **Saneamiento de Políticas de Seguridad**: Corregir `TeacherPolicy::update` para evaluar la relación docente real (`$user->teacher?->cdi` o `$teacher->user_id === $user->id`), incorporar operadores seguros nullsafe en `CategoryPolicy`, y permitir a los jefes de área registrar escalafón a docentes de su sede.
- **Sincronización Automática Bidireccional de Docentes**: Añadir hooks y observadores en `Category`, `Dedication` y `Site` para sincronizar automáticamente `teachers.category_id`, `teachers.dedication_id` y `teachers.site_id`, eliminando el estado "Sin asignar" en las tablas principales.
- **Corrección Crítica en Permisos Docentes**: Auto-generar o exponer de forma controlada el campo obligatorio `name` en `PermissionTeacherResource`, bloquear la edición del selector `status` e `is_paid` para docentes en la solicitud inicial, y conectar reactividad en fechas (`start_date` + `duration_type` -> `end_date`).
- **Validación Normativa de Dedicaciones**: Aplicar reglas estrictas de horas semanales según el tipo de dedicación UNERG (TC: 30h, MT: 18h, TCV: 1-17h, EX: 35-36h).
- **Expediente Docente 360°**: Incorporar `PermissionsRelationManager`, `CategoryRelationManager` y `DedicationRelationManager` dentro de `TeacherResource` para centralizar la historia académica completa del profesor.

## Capabilities

### Modified Capabilities
- `role-policy-authorization`: Enforzamiento estricto de roles en tablas administrativas, saneamiento de verificación por cédula en docentes y restricción de auto-aprobación en solicitudes de permisos.
- `filament-smart-forms`: Sincronización relacional automática entre docentes y componentes individuales (categoría, dedicación, carga), resolución del campo obligatorio en permisos y reactividad temporal en cálculos de vigencia.
- `academic-report-integration`: Ampliación del expediente docente en `TeacherResource` con gestores de relación completos para permisos, categorías y dedicaciones horarias.

## Impact

- **Modelos y Observadores**: `Category`, `Dedication`, `Site`, `Teacher`, `PermissionTeacher`, `User`.
- **Recursos Filament**: `UserResource`, `TeacherResource`, `PermissionTeacherResource`, `CategoryResource`, `DedicationResource`, `SiteResource`.
- **Políticas de Autorización**: `UserPolicy`, `TeacherPolicy`, `CategoryPolicy`, `DedicationPolicy`, `PermissionTeacherPolicy`.
- **Base de Datos**: Consistencia garantizada entre claves foráneas `id` y claves naturales `cdi`.
- **Pruebas Automatizadas**: Nuevas pruebas de integración y seguridad en `tests/Feature/` para cubrir el ciclo de vida sin vulnerabilidades.
