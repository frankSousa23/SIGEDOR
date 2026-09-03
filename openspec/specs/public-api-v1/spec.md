# public-api-v1 Specification

## Purpose

Expone una interfaz de programación de aplicaciones (API RESTful) documentada con OpenAPI/Swagger para interoperabilidad y consulta externa de expedientes y reportes.

## Requirements

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

### Requirement: Documentación Estricta de Esquemas (OpenAPI)
El sistema DEBE proveer esquemas DTO reutilizables (Teacher, Report) en lugar de retornar objetos genéricos en Swagger.

#### Scenario: Visualización de modelos documentados
- **WHEN** un cliente accede a la interfaz de Swagger UI
- **THEN** los esquemas completos de Teacher y Report están definidos y pueden ser inspeccionados

### Requirement: Documentación de Códigos de Error (OpenAPI)
El sistema DEBE documentar las respuestas de error estándar (404, 422, 500) para cada endpoint de la API usando un esquema reutilizable (`ErrorResponse`).

#### Scenario: Visualización de casos de error
- **WHEN** un cliente visualiza un endpoint específico en Swagger UI
- **THEN** los posibles códigos de estado y el modelo unificado de error (ErrorResponse) son mostrados

### Requirement: Endpoint de Verificación de Salud (Health Check)
El sistema DEBE proveer un endpoint público GET /api/ping que permita verificar la disponibilidad operativa y conectividad de la API, retornando estado 200 y mensaje de confirmación de salud.

#### Scenario: Petición de chequeo de salud
- **WHEN** un cliente HTTP o monitor de disponibilidad envía una solicitud GET a /api/ping
- **THEN** el sistema retorna código HTTP 200 con un objeto JSON indicando `status: "pong"` o confirmación de operatividad

### Requirement: Limitación de Tasa de Peticiones (Rate Limiting)
Los endpoints públicos de la API DEBEN estar protegidos mediante middleware de limitación de tasa (`throttle`) para prevenir abusos, denegación de servicio o scraping automatizado no autorizado.

#### Scenario: Exceso de solicitudes en ventana de tiempo
- **WHEN** un cliente supera el umbral permitido de peticiones por minuto en las rutas de la API
- **THEN** el sistema bloquea temporalmente las peticiones subsiguientes respondiendo con código HTTP 429 Too Many Requests
