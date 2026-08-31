## Why

SIGEDOR no cuenta con ningún pipeline de CI/CD ni tiene garantías automáticas de que el repositorio esté en un estado funcional para quienes lo clonen. Además, hay inconsistencias entre `.env` local y `.env.example`, y un artefacto generado (`storage/api-docs/api-docs.json`) está innecesariamente trackeado en git, lo que puede causar documentación desactualizada o conflictos. El proyecto necesita infraestructura básica de calidad reproducible antes de escalar en colaboradores o uso universitario.

## What Changes

- **[NUEVO]** `.github/workflows/ci.yml`: Pipeline de GitHub Actions que ejecuta automáticamente los tests (Pest) y el linter de estilo (Pint) en cada `push` y `pull_request`.
- **[CORRECCIÓN]** `.gitignore`: Agregar `storage/api-docs/` para excluir el JSON generado de Swagger del repositorio.
- **[CORRECCIÓN]** `.env.example`: Alinear con el `.env` local en los campos `APP_TIMEZONE`, `APP_URL`, `APP_TIMEZONE`, y `MAIL_FROM_ADDRESS`.
- **[MEJORA]** `README.md`: Reemplazar el badge estático de tests por un badge dinámico de GitHub Actions CI.
- **[MEJORA]** `README.md`: Agregar sección "Contribución local" con instrucciones de primer clone, setup de `.env` y generación de Swagger.
- **[CORRECCIÓN]** `.env.example`: Agregar variable `L5_SWAGGER_CONST_HOST` documentada, necesaria para el host en Swagger UI.

## Capabilities

### New Capabilities
- `ci-pipeline`: El sistema de CI/CD ejecuta los tests y linting automáticamente en GitHub al recibir nuevos commits, garantizando que el repositorio siempre esté en estado funcional para quien lo clone.

### Modified Capabilities
_(Ninguna — este cambio es infraestructura y configuración de entorno, no modifica comportamiento de la aplicación.)_

## Impact

- **`.github/`**: Directorio nuevo con workflow de GitHub Actions.
- **`.gitignore`**: Una línea agregada para excluir `storage/api-docs/`.
- **`.env.example`**: Corrección de 4 variables + adición de `L5_SWAGGER_CONST_HOST`.
- **`README.md`**: Badge de CI actualizado + sección de setup para colaboradores.
- **Sin impacto en lógica de negocio**: Ningún modelo, controlador, migración ni test es modificado.
- **Sin dependencias nuevas**: No se agrega ningún paquete Composer ni npm.
