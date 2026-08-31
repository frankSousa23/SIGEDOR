## ADDED Requirements

### Requirement: Documentación Estricta de Esquemas (OpenAPI)
El sistema DEBE exponer esquemas detallados en Swagger para todos los modelos de dominio retornados por la API (incluyendo estructuras de paginación y atributos tipados).

#### Scenario: Visualización de modelos en Swagger UI
- **WHEN** el usuario accede a la documentación de Swagger
- **THEN** se muestran los schemas reutilizables (DTOs) en la sección "Schemas"

### Requirement: Documentación de Códigos de Error (OpenAPI)
El sistema DEBE documentar las respuestas HTTP distintas a 200 (como 404, 422, 500) en cada endpoint relevante, detallando el esquema del error.

#### Scenario: Visualización de posibles errores por endpoint
- **WHEN** el usuario inspecciona un endpoint específico en Swagger UI
- **THEN** puede ver documentadas las respuestas 4xx y 5xx con sus respectivos ejemplos de error
