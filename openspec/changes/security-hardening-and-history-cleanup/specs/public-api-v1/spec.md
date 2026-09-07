## MODIFIED Requirements

### Requirement: Endpoint de Consulta de Docentes
El sistema DEBE proveer un endpoint GET /api/v1/teachers para consultar la lista paginada y filtrada de docentes, suprimiendo de forma estricta los datos personales privados (teléfono, fecha de nacimiento, identificador de usuario) cuando la consulta se realiza de forma pública no autenticada.

#### Scenario: Petición autenticada de listado
- **WHEN** un cliente HTTP consulta GET /api/v1/teachers con cabecera de aceptación JSON
- **THEN** el sistema retorna código 200 con la estructura JSON de docentes documentada en Swagger sin exponer números telefónicos ni fechas de nacimiento privadas

### Requirement: Endpoint de Reportes Académicos
El sistema DEBE proveer un endpoint GET /api/v1/reports para consultar los reportes universitarios emitidos, protegiendo la identidad y datos sensibles del docente vinculado.

#### Scenario: Petición de reportes
- **WHEN** un cliente consulta GET /api/v1/reports
- **THEN** el sistema responde con la lista serializada de reportes conteniendo exclusivamente información pública del docente adscrito
