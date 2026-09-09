## MODIFIED Requirements

### Requirement: Endpoint de Reportes Académicos
El sistema DEBE proveer un endpoint GET /api/v1/reports para consultar los reportes universitarios emitidos, serializando fielmente los campos de autenticidad institucional (`verification_code`), estado del trámite (`status`) y autoría (`created_by`), protegiendo la identidad y datos sensibles del docente vinculado.

#### Scenario: Petición de reportes
- **WHEN** un cliente consulta GET /api/v1/reports
- **THEN** el sistema responde con la lista serializada de reportes conteniendo exclusivamente información pública del docente adscrito

#### Scenario: Petición de reportes con datos de autenticidad y estado
- **WHEN** un cliente consulta GET /api/v1/reports
- **THEN** el sistema responde con la lista serializada de reportes conteniendo `verification_code`, `status`, `created_by` e información pública del docente adscrito sin exponer datos personales privados

### Requirement: Documentación Estricta de Esquemas (OpenAPI)
El sistema DEBE proveer esquemas DTO reutilizables (Teacher, Report) actualizados con los campos de autenticidad institucional y documentar todos los endpoints operativos expuestos, incluyendo el endpoint de comprobación de salud GET /api/ping en Swagger UI.

#### Scenario: Visualización de modelos documentados
- **WHEN** un cliente accede a la interfaz de Swagger UI
- **THEN** los esquemas completos de Teacher y Report están definidos y pueden ser inspeccionados

#### Scenario: Visualización de modelos y healthcheck en Swagger UI
- **WHEN** un cliente o evaluador accede a la interfaz interactiva de Swagger UI (/api/documentation)
- **THEN** los esquemas completos de Teacher y Report reflejan verification_code y status, y el endpoint GET /api/ping aparece documentado con sus respuestas correspondientes
