## Purpose

Define el comportamiento observable del pipeline de integración continua (CI) del proyecto SIGEDOR: qué verifica automáticamente, cuándo se ejecuta, y qué garantías ofrece a cualquier colaborador que descargue el repositorio.

## ADDED Requirements

### Requirement: Pipeline de CI automático en GitHub
El sistema CI DEBE ejecutar la suite de tests completa y la verificación de estilo de código en cada `push` y `pull_request` al repositorio, de forma completamente automatizada y sin intervención manual.

#### Scenario: Push ejecuta tests automáticamente
- **WHEN** un colaborador hace `git push` a cualquier rama del repositorio
- **THEN** GitHub Actions inicia el job de tests que ejecuta `vendor/bin/pest` contra una base de datos SQLite en memoria y reporta el resultado del run en la interfaz de GitHub

#### Scenario: Push ejecuta verificación de estilo
- **WHEN** un colaborador hace `git push` a cualquier rama del repositorio
- **THEN** GitHub Actions inicia el job de linting que ejecuta `vendor/bin/pint --test` y reporta si el código cumple las convenciones de estilo del proyecto

#### Scenario: Badge de CI refleja el estado real
- **WHEN** un visitante accede al repositorio en GitHub
- **THEN** el badge de CI en el README muestra el estado actual (passing/failing) del último run de la rama `main`, enlazando directamente al historial de runs

### Requirement: Entorno reproducible para colaboradores
El repositorio DEBE proveer un `.env.example` que, copiado literalmente, permita ejecutar los tests localmente sin modificación adicional, y una `.gitignore` que excluya todos los artefactos generados del control de versiones.

#### Scenario: Colaborador clona y ejecuta tests
- **WHEN** un colaborador clona el repositorio y ejecuta el workflow de setup descrito en el README (copy env, key:generate, migrate, seed)
- **THEN** los 18 tests pasan exitosamente sin ninguna configuración adicional al entorno base del `.env.example`

#### Scenario: api-docs.json no genera conflictos
- **WHEN** dos colaboradores trabajan en ramas distintas y modifican anotaciones de Swagger
- **THEN** el archivo `storage/api-docs/api-docs.json` no genera conflictos de merge porque está excluido del repositorio y cada colaborador lo regenera localmente con `php artisan l5-swagger:generate`
