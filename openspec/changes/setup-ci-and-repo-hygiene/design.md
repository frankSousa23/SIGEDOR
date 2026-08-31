## Context

El proyecto ya tiene:
- 18 tests (Pest) pasando al 100%, configurados con SQLite in-memory en `phpunit.xml`
- `laravel/pint` instalado como dev-dependency lista para usar
- Repositorio en GitHub: `frankSousa23/SIGEDOR` (remoto `origin`)
- `storage/api-docs/api-docs.json` trackeado en git (artefacto generado)
- `.env.example` con 3–4 valores divergentes del `.env` local funcional

Ver `proposal.md` para motivación completa.

## Goals / Non-Goals

**Goals:**
- Configurar GitHub Actions para ejecutar tests + lint en cada push/PR de forma gratuita
- Corregir el `.env.example` para que sea fuente de verdad funcional al clonar
- Excluir `storage/api-docs/` del control de versiones
- Actualizar el badge de README a uno dinámico que refleje el estado real del CI

**Non-Goals:**
- Deploy automático a ningún servidor (el scope es local-first)
- Containerización con Docker/Sail
- Notificaciones de CI por Slack/email
- Matrix de versiones PHP (solo PHP 8.3 en CI)
- Cobertura de código o reportes de coverage

## Decisions

### D1: GitHub Actions sobre otras plataformas CI

**Decisión:** GitHub Actions.

**Rationale:** El repo vive en GitHub. GitHub Actions es gratuito para repositorios públicos (y 2000 min/mes para privados), no requiere cuenta adicional, los workflows son YAML en `.github/workflows/` (junto al código), y `ubuntu-latest` tiene PHP 8.3 disponible nativamente via `shivammathur/setup-php`.

**Alternativas descartadas:**
- CircleCI / Travis CI: requieren cuenta separada, configuración externa.
- GitLab CI: el repo no está en GitLab.

---

### D2: Dos jobs separados (test + lint) en lugar de uno combinado

**Decisión:** Jobs paralelos independientes — `test` y `lint`.

**Rationale:** Si el lint falla, los tests igual corren (y viceversa). El feedback es más granular: se sabe exactamente qué falló. Además, corren en paralelo reduciendo tiempo total.

**Alternativa descartada:** Un solo job secuencial — más simple pero provee feedback más lento y menos informativo.

---

### D3: `db:seed` incluido en el job de CI

**Decisión:** El job de CI corre `php artisan migrate --seed` en lugar de solo `migrate`.

**Rationale:** Los seeders leen los archivos CSV de `database/seeders/data/`. Que el CI valide que el seed funciona es valioso: si alguien rompe un seeder o un CSV, el CI lo detecta. Los tests individuales usan `RefreshDatabase` con sus propios datos, así que el seed de CI es adicional, no redundante.

---

### D4: `storage/api-docs/` al `.gitignore`

**Decisión:** Agregar la carpeta completa (no solo `api-docs.json`).

**Rationale:** El directorio es creado por `l5-swagger:generate`. Si en el futuro se agrega un segundo set de docs (ej. `api-docs-v2.json`), también quedaría excluido automáticamente.

**Consecuencia a documentar:** Quien clone el repo debe correr `php artisan l5-swagger:generate` para acceder a la UI de Swagger. Esto se documenta en el README.

---

### D5: Correcciones del `.env.example`

| Campo | Valor actual (incorrecto) | Valor correcto | Razón |
|---|---|---|---|
| `APP_TIMEZONE` | `America/Caracas` | `UTC` | Laravel 11 usa UTC por defecto; Carbon convierte según necesidad |
| `APP_URL` | `http://localhost:8000` | `http://localhost` | Laragon sirve en el puerto 80 por defecto |
| `MAIL_FROM_ADDRESS` | `no-reply@sigedor.com` | `"hello@example.com"` | En entorno local el mailer es `log`; un email genérico es más claro |
| `L5_SWAGGER_CONST_HOST` | _(ausente)_ | `http://localhost/api` | Sin este valor, Swagger UI no puede hacer peticiones de prueba |

## Risks / Trade-offs

- **[Riesgo] El badge dinámico tarda en aparecer** → GitHub Actions genera el badge solo después del primer run exitoso. Es cosmético; no afecta funcionalidad.

- **[Trade-off] `db:seed` en CI aumenta ~2-5s el tiempo de run** → Aceptable. El valor de validar los seeders supera el costo de tiempo.

- **[Riesgo] `storage/api-docs/` ya está trackeado en el historial de git** → Agregar al `.gitignore` lo excluye de futuros commits, pero el historial preserva la versión anterior. Para limpiar el historial haría falta un `git rm --cached`, que se incluye como tarea.

## Migration Plan

1. Agregar `storage/api-docs/` a `.gitignore` + `git rm --cached storage/api-docs/api-docs.json`
2. Corregir `.env.example`
3. Crear `.github/workflows/ci.yml`
4. Actualizar badge en README + agregar sección de primer setup
5. Hacer commit único etiquetado: `ci: setup GitHub Actions pipeline and repo hygiene`

No hay rollback requerido — todos los cambios son en archivos de configuración sin impacto en lógica de negocio.
