## Why

Actualmente, la documentación de la API generada con L5-Swagger es muy básica. Los endpoints documentados devuelven respuestas genéricas tipo `object`, ocultando la verdadera estructura de los datos que retorna el sistema (como los campos de `Teacher` y `Report`). Esto dificulta su consumo por parte de clientes externos. Mejorar esto proporciona un contrato claro, profesional y estricto, facilitando la integración y reduciendo la fricción para desarrolladores que usen la API.

## What Changes

- Definición de componentes y esquemas reutilizables (DTOs) en Swagger para los modelos de `Teacher`, `Report` y la estructura de `Pagination` nativa de Laravel.
- Refactorización de las anotaciones `@OA\Get` en `TeacherApiController` y `ReportApiController` para que usen los nuevos esquemas.
- Documentación de las respuestas de error comunes (404, 422, 500) en todos los endpoints expuestos, para establecer un contrato completo.
- (Opcional/Condicional) Definición de esquemas de seguridad si se decide proteger los endpoints en el futuro.

## Capabilities

### New Capabilities
*(Ninguna nueva funcionalidad core)*

### Modified Capabilities
- `public-api-v1`: Se fortalecerá el requerimiento de documentación, especificando que la API expone esquemas de datos estrictos (schemas completos) y códigos de error (404, 422, 500), no solo el caso feliz (200).

## Impact

- `app/Http/Controllers/Api/TeacherApiController.php`: Se actualizarán las anotaciones de Swagger.
- `app/Http/Controllers/Api/ReportApiController.php`: Se actualizarán las anotaciones de Swagger.
- `app/Http/Controllers/Controller.php` (o un nuevo archivo dedicado): Para albergar las anotaciones de componentes base y esquemas compartidos (e.g. `PaginationMeta`, `ErrorResponse`).
