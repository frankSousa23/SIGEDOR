# Tasks: security-hardening-and-history-cleanup

## 1. Fortalecimiento Perimetral y de Infraestructura

- [x] 1.1 Actualizar `.gitignore` para ignorar `.env.*`, volcados `*.sql`, `*.sqlite`, `*.dump`, archivos temporales `vite.config.js.timestamp*` y rutas de backup Spatie (`storage/app/backup-temp/`, `storage/app/Laravel/`), verificando con `git status`.
- [x] 1.2 Modificar `composer.json` cambiando `"secure-http": false` a `"secure-http": true` para garantizar descargas estrictamente sobre HTTPS.
- [x] 1.3 Configurar `app/Providers/TelescopeServiceProvider.php` para enmascarar `password`, `password_confirmation` y la cabecera `authorization` en `hideSensitiveRequestDetails()`.
- [x] 1.4 Actualizar `public/robots.txt` agregando directivas `Disallow: /admin/`, `Disallow: /telescope/` y `Disallow: /api/` para impedir la indexación de paneles y telemetría por motores de búsqueda.

## 2. Protección de Datos Personales (PII) en la API REST

- [x] 2.1 Crear `app/Http/Resources/Api/TeacherPublicResource.php` filtrando campos sensibles (omitiendo `phone`, `birthDate`, `user_id`) y exponiendo únicamente datos académicos públicos.
- [x] 2.2 Crear `app/Http/Resources/Api/ReportPublicResource.php` serializando reportes y vinculando al docente a través de `TeacherPublicResource`.
- [x] 2.3 Actualizar `TeacherApiController.php` y `ReportApiController.php` para utilizar `TeacherPublicResource::collection(...)` y `ReportPublicResource::collection(...)` respectivamente.
- [x] 2.4 Actualizar `tests/Feature/ApiTest.php` para verificar explícitamente que los campos `phone`, `birthDate` y `user_id` no estén presentes en las respuestas JSON públicas de `/api/v1/teachers` y `/api/v1/reports`.

## 3. Pipeline DevSecOps en CI/CD

- [x] 3.1 Actualizar `.github/workflows/ci.yml` incorporando un step de análisis estático con `gitleaks/gitleaks-action@v2` para detectar de forma automatizada cualquier token o credencial antes de permitir la fusión de código.

## 4. Purga de PII en el Historial de Git

- [ ] 4.1 Generar una etiqueta o rama de respaldo local (`git branch backup-pre-purge`) antes de iniciar la reescritura del historial.
- [ ] 4.2 Utilizar `git-filter-repo` (o rebase interactivo automatizado) para expurgar de todo el árbol histórico los CSVs que contenían los 872 registros de datos reales del commit `06ad522`, sustituyéndolos por las semillas canónicas anonimizadas.
- [ ] 4.3 Validar mediante `git log` y búsqueda de patrones que no exista ningún blob ni commit en el historial con las cédulas o nombres reales del archivo original.

## 5. Verificación Integral y Calidad

- [ ] 5.1 Ejecutar `vendor/bin/pest` y confirmar que el 100% de las pruebas automatizadas pasen en verde.
- [ ] 5.2 Ejecutar `vendor/bin/pint --test` para certificar el cumplimiento de estándares de código Laravel.
- [ ] 5.3 Ejecutar `openspec validate --all` para asegurar la total coherencia de las especificaciones y artefactos del cambio.
