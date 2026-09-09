## ADDED Requirements

### Requirement: Aislamiento Estricto de Reportes por Rol
El sistema DEBE aplicar políticas de autorización y filtrado en consultas que restrinjan a los usuarios con rol docente a ver y consultar exclusivamente los reportes emitidos para su propia cédula de identidad, a los jefes de área a los docentes de su sede y al administrador a nivel global.

#### Scenario: Docente consulta la lista de reportes
- **WHEN** un usuario con rol exclusivo de docente ingresa al recurso de reportes
- **THEN** la consulta filtra automáticamente el conjunto de datos mostrando solo aquellos registros cuyo `teacher_cdi` corresponda a su perfil docente

#### Scenario: Docente intenta visualizar reporte ajeno
- **WHEN** un docente intenta acceder directamente por URL a un reporte perteneciente a otro profesor
- **THEN** la política del sistema deniega el acceso con error HTTP 403
