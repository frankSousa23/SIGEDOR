## 1. Limpieza del Repositorio (git hygiene)

- [x] 1.1 Agregar `storage/api-docs/` al `.gitignore` (al final del archivo, con comentario `# Generated Swagger docs`) y verificar que el archivo no aparece en `git status` tras el cambio.
- [x] 1.2 Ejecutar `git rm --cached storage/api-docs/api-docs.json` para dejar de trackear el archivo sin borrarlo localmente, y verificar que `git status` muestra el archivo como "untracked" y no como "deleted".
- [x] 1.3 Verificar que `php artisan l5-swagger:generate` sigue generando correctamente el archivo en local después de estos cambios.

## 2. Corrección del `.env.example`

- [x] 2.1 Cambiar `APP_TIMEZONE=America/Caracas` a `APP_TIMEZONE=UTC` y verificar que el archivo sigue siendo válido con `php artisan config:show app`.
- [x] 2.2 Cambiar `APP_URL=http://localhost:8000` a `APP_URL=http://localhost` (consistente con Laragon en puerto 80).
- [x] 2.3 Cambiar `MAIL_FROM_ADDRESS="no-reply@sigedor.com"` a `MAIL_FROM_ADDRESS="hello@example.com"` para reflejar el entorno de desarrollo local con `MAIL_MAILER=log`.
- [x] 2.4 Agregar `L5_SWAGGER_CONST_HOST=http://localhost/api` al final de la sección de variables de la app, y verificar que Swagger UI puede acceder al campo "Servers" tras regenerar los docs.

## 3. Creación del Workflow de GitHub Actions

- [x] 3.1 Crear el directorio `.github/workflows/` en la raíz del proyecto y verificar que existe.
- [x] 3.2 Crear `.github/workflows/ci.yml` con el job `test` que: hace checkout, configura PHP 8.3 con `shivammathur/setup-php`, instala dependencias con `composer install --no-interaction`, copia `.env.example` a `.env`, genera la app key con `php artisan key:generate`, ejecuta migraciones con `php artisan migrate --seed`, y corre `vendor/bin/pest`. Verificar que el workflow tiene sintaxis YAML válida.
- [x] 3.3 Agregar el job `lint` paralelo al job `test` en el mismo `ci.yml` que ejecuta `vendor/bin/pint --test` tras `composer install`. Verificar que ambos jobs aparecen correctamente definidos.
- [ ] 3.4 Hacer push del workflow al repositorio remoto y verificar en la pestaña "Actions" de GitHub que el pipeline se ejecuta automáticamente y los dos jobs pasan en verde.

## 4. Actualización del README

- [x] 4.1 Reemplazar el badge estático `[![Tests](https://img.shields.io/badge/Tests-100%25%20Passing-brightgreen...)]` por el badge dinámico de GitHub Actions: `[![CI](https://github.com/frankSousa23/SIGEDOR/actions/workflows/ci.yml/badge.svg)](https://github.com/frankSousa23/SIGEDOR/actions/workflows/ci.yml)` y verificar que el badge enlaza al historial de runs.
- [x] 4.2 Agregar una sección "⚡ Setup Rápido para Colaboradores" en el README (después de "Instalación Rápida") con los pasos explícitos: `cp .env.example .env`, `php artisan key:generate`, `php artisan migrate --seed`, `php artisan l5-swagger:generate`. Verificar que los comandos están en bloques de código y ordenados correctamente.
- [x] 4.3 Agregar una nota en la sección de Swagger/API del README indicando que `api-docs.json` no está en el repositorio y debe generarse localmente con `php artisan l5-swagger:generate`.

## 5. Verificación Final

- [x] 5.1 Ejecutar localmente `vendor/bin/pest` y confirmar que los 18 tests siguen pasando sin cambios en la lógica de negocio.
- [x] 5.2 Ejecutar `vendor/bin/pint --test` y verificar que no reporta errores de estilo (o corregirlos si los hay).
- [ ] 5.3 Confirmar en GitHub que el badge dinámico en el README muestra estado "passing" después del primer run exitoso del workflow.
- [ ] 5.4 Hacer un commit final con mensaje `ci: setup GitHub Actions pipeline and repo hygiene` que incluya todos los cambios de este change.
