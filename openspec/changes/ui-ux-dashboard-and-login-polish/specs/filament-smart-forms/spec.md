## ADDED Requirements

### Requirement: Autoselección de Docente en Solicitudes de Permisos
El formulario de solicitud de permisos DEBE detectar si el usuario autenticado tiene rol docente, preseleccionando automáticamente su cédula en el campo de docente solicitante e inhabilitando la selección de otros profesores para garantizar la integridad de la identidad.

#### Scenario: Docente ingresa al formulario de permisos
- **WHEN** un usuario con rol exclusivo de docente ingresa a crear una solicitud de permiso
- **THEN** el campo de docente solicitante aparece preseleccionado con su propia cédula y bloqueado para edición
