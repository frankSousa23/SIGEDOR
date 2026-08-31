## Purpose

Expone una interfaz de programación de aplicaciones (API RESTful) documentada con OpenAPI/Swagger para interoperabilidad y consulta externa de expedientes y reportes.

## ADDED Requirements

### Requirement: Endpoint de Consulta de Docentes
El sistema DEBE proveer un endpoint GET /api/v1/teachers para consultar la lista paginada y filtrada de docentes.

#### Scenario: Petición autenticada de listado
- **WHEN** un cliente HTTP consulta GET /api/v1/teachers con cabecera de aceptación JSON
- **THEN** el sistema retorna código 200 con la estructura JSON de docentes documentada en Swagger

### Requirement: Endpoint de Reportes Académicos
El sistema DEBE proveer un endpoint GET /api/v1/reports para consultar los reportes universitarios emitidos.

#### Scenario: Petición de reportes
- **WHEN** un cliente consulta GET /api/v1/reports
- **THEN** el sistema responde con la lista serializada de reportes

