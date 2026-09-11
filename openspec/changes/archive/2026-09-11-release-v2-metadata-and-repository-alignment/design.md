## Context

El repositorio ha completado con éxito todas las fases de refactorización y pruebas de SIGEDOR. Para preparar un release impecable hacia la comunidad de código abierto y la UNERG, se deben ajustar los metadatos a nivel de gestor de dependencias (`composer.json`), documentar el release en el `README.md`, optimizar el repositorio Git con empaquetado de objetos y generar el tag `v2.0.0`.

## Goals / Non-Goals

**Goals:**
- Actualizar `composer.json` con nombre de paquete `franksousa23/sigedor`, descripción oficial y keywords pertinentes.
- Reflejar en `README.md` el recuento de 49 tests (372 aserciones) y la insignia/mención del release `v2.0.0`.
- Optimizar la base de datos de objetos Git mediante `git gc --prune=now`.
- Generar el tag anotado `v2.0.0` y preparar las notas de lanzamiento completas.

**Non-Goals:**
- No alterar código de la aplicación ni dependencias externas.
- No modificar migraciones ni esquemas de datos.

## Decisions

### 1. Actualización de Metadatos en Composer
- **Decisión**: Reemplazar `"name": "laravel/laravel"` por `"name": "franksousa23/sigedor"`, proporcionando descripción y palabras clave específicas.
- **Alternativa considerada**: Dejar `laravel/laravel`; se descartó porque al clonar o inspeccionar el proyecto con Composer muestra datos genéricos que no corresponden a SIGEDOR.

### 2. Etiquetado SemVer `v2.0.0`
- **Decisión**: Utilizar `v2.0.0` como versión mayor debido a las mejoras acumuladas desde `v1.0.0` (rediseño completo de RBAC, expedientes 360°, OpenAPI 3.0, erradicación de N+1 y 49 pruebas).
- **Alternativa considerada**: `v1.1.0`; se descartó porque el alcance y los cambios arquitectónicos acumulados justifican un salto de versión mayor.

## Risks / Trade-offs

- **[Riesgo] Error en sintaxis JSON de composer.json** → **Mitigación**: Validar con `composer validate --no-check-all` o verificación de sintaxis de PHP/JSON antes de confirmar.
