# Proposal: security-hardening-and-history-cleanup

## Why

El repositorio de SIGEDOR requiere una elevación integral de su postura de seguridad (DevSecOps), tanto a nivel de código como de infraestructura e historial histórico. Específicamente, el commit histórico `06ad522` ("Cargar BD") introdujo archivos CSV que contenían datos personales reales (cédulas, nombres, correos institucionales y registros académicos) de más de 870 docentes universitarios; aunque versiones recientes anonimizaron los archivos activos, la información sensible permanece accesible en el historial persistente de Git. Asimismo, la auditoría reveló oportunidades críticas de blindaje en `.gitignore`, transporte seguro en Composer, protección de PII en APIs públicas, anonimización de logs en Telescope y políticas de rastreo en `robots.txt`.

## What Changes

- **Purga de PII en el Historial de Git**: Reescribir el historial de Git mediante `git-filter-repo` (o reinicio controlado y limpio) para extirpar permanentemente las versiones históricas de `database/seeders/data/*.csv` que contenían datos reales no anonimizados, conservando intacto el código fuente y las pruebas.
- **Blindaje Estricto de `.gitignore`**: Añadir reglas para ignorar cualquier variante de entorno (`.env.*`), archivos de volcado de base de datos (`*.sql`, `*.sqlite`, `*.dump`), archivos temporales de Vite (`vite.config.js.timestamp*`) y respaldos locales de Spatie.
- **Enforzamiento de Transporte Seguro en Composer**: Configurar `"secure-http": true` en `composer.json` para prevenir ataques de intermediario (Man-in-the-Middle) en la descarga de dependencias.
- **Protección de Datos Personales (PII) en API REST**: Transformar las respuestas de `/api/v1/teachers` y `/api/v1/reports` mediante recursos API (`JsonResource`) que filtren campos sensibles (teléfono, fecha de nacimiento, user_id) para consumidores anónimos.
- **Filtrado de Credenciales en Laravel Telescope**: Configurar `hideSensitiveRequestDetails()` en `TelescopeServiceProvider` para enmascarar contraseñas (`password`, `password_confirmation`) y cabeceras `Authorization` en las trazas.
- **Restricción de Indexación en `robots.txt`**: Desautorizar a motores de búsqueda y bots el rastreo de `/admin/`, `/telescope/` y `/api/`.
- **Integración de Secret Scanning en CI/CD**: Añadir un paso de análisis estático con Gitleaks en `.github/workflows/ci.yml` para bloquear commits accidentales con claves o credenciales.

## Capabilities

### New Capabilities
- `git-history-pii-purging`: Reglas y protocolos para garantizar que ningún dato personal real ni archivo de semilla histórico con PII persista en los árboles de Git del repositorio.
- `repository-security-hardening`: Mecanismos de protección perimetral del repositorio, incluyendo `.gitignore` blindado, HTTPS estricto en dependencias, protección contra indexación de paneles y sanitización de telemetría.

### Modified Capabilities
- `public-api-v1`: Modificación de los requisitos de exposición de datos en los endpoints `/api/v1/teachers` y `/api/v1/reports` para restringir la exposición de atributos personales identificables (PII) a clientes no autenticados.

## Impact

- **Historial de Git**: Reescritura del grafo de commits (`git filter-repo` / `git push --force`). Requiere que los colaboradores actualicen sus clones locales.
- **Dependencias y Build**: Actualización de `composer.json` (`secure-http: true`).
- **Controladores API**: Incorporación de `TeacherPublicResource` y `ReportPublicResource` en `app/Http/Resources/`.
- **CI/CD**: Adición del job/step de Gitleaks en GitHub Actions.
- **Pruebas Automatizadas**: Ajuste y extensión de `ApiTest.php` para validar que los campos PII no se filtren en las respuestas JSON.
