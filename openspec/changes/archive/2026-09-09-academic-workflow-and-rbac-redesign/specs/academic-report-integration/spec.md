## ADDED Requirements

### Requirement: Expediente Integral 360 del Docente
La interfaz de edición y consulta de docentes DEBE incorporar gestores de relaciones dedicados para Permisos (`PermissionsRelationManager`), Categoría Académica y Dedicación Horaria, permitiendo auditar y gestionar el historial universitario completo del docente desde una única vista unificada.

#### Scenario: Consulta del expediente unificado de un profesor
- **WHEN** un administrador o jefe de área accede a la vista de edición o consulta de un docente
- **THEN** la interfaz despliega pestañas para consultar sus reportes oficiales, historial de permisos tramitados, escalafón docente y carga horaria asignada
