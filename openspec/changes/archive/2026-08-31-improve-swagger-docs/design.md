## Context

Actualmente L5-Swagger está instalado y genera la documentación, pero los tipos devueltos por la API (Teacher, Report) están declarados como genéricos (`type="object"`). Ver `proposal.md` para la motivación.

## Goals / Non-Goals

**Goals:**
- Estructurar esquemas reutilizables (`@OA\Schema`) para que los modelos sean fuertemente tipados en la documentación.
- Reutilizar la respuesta de paginación de Laravel en Swagger.
- Incluir las respuestas de error más comunes.

**Non-Goals:**
- No se implementarán middlewares ni sistemas de autenticación reales si no existen actualmente; solo se documentarán los esquemas de datos.

## Decisions

- **Ubicación de los Virtuales (Schemas DTO):** En lugar de contaminar los modelos de Eloquent (`app/Models`) con anotaciones `@OA\Property` (lo cual acopla la DB con la API externa), crearemos un archivo dedicado `app/Virtual/Models/Teacher.php` (y similares) o incluiremos los esquemas directamente en un bloque base en `app/Http/Controllers/Controller.php` o `app/Virtual/Resources/TeacherResource.php`.
   - *Decisión:* Se creará un namespace virtual en `app/Virtual/Models/` (para los DTOs) y `app/Virtual/Resources/` para evitar acoplamiento con Eloquent.
- **Esquema de Error:** Se creará un componente genérico `ErrorResponse` en `app/Virtual/Resources/ErrorResponse.php` para documentar estructuras 404, 422 y 500 de manera uniforme.

## Risks / Trade-offs

- **Risk:** [Mantenimiento Dual] → Si un campo cambia en la base de datos, habrá que actualizar manualmente las anotaciones de Swagger.
  - *Mitigation:* Se documentará este proceso como parte de los estándares del proyecto (no cubierto por este cambio directamente, pero es un riesgo conocido de Swagger usando anotaciones).
