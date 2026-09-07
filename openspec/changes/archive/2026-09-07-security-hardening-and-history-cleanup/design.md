# Design: security-hardening-and-history-cleanup

## Context

El repositorio SIGEDOR se encuentra en un estado funcional óptimo (29 tests pasando, estilo formateado). No obstante, el commit histórico `06ad5222c929c0c20271ef2ea2437c56c64ca669` ("Cargar BD") contiene 5 archivos CSV con 872 filas de datos personales reales (cédulas, nombres, correos y adscripciones académicas reales de la universidad). Además, el análisis de seguridad detectó omisiones en `.gitignore`, permisividad en Composer (`"secure-http": false`), telemetría sin enmascarar en Telescope, indexación indiscriminada en `robots.txt` y exposición de PII en los controladores públicos de la API REST.

## Goals / Non-Goals

**Goals:**
- Eliminar de manera definitiva y trazable cualquier presencia de los CSVs con 872 registros de datos reales del historial de Git.
- Desacoplar y proteger la información personal (PII) en `TeacherApiController` y `ReportApiController` mediante API Resources dedicados (`TeacherPublicResource` y `ReportPublicResource`).
- Fortalecer `.gitignore` con exclusiones universales de entornos, volcados de base de datos y temporales.
- Restablecer `"secure-http": true` en `composer.json`.
- Enmascarar contraseñas y cabeceras de autorización en `TelescopeServiceProvider`.
- Restringir la indexación de robots en `public/robots.txt`.
- Añadir un job de escaneo automatizado con Gitleaks en `.github/workflows/ci.yml`.

**Non-Goals:**
- No se requiere revocar credenciales de proveedores cloud externos (AWS, OpenAI, Stripe), dado que la auditoría confirmó que nunca han existido claves activas en el repositorio.
- No se modificará la lógica de negocio de asignación docente ni las migraciones de base de datos.

## Decisions

### 1. Estrategia de Purga del Historial de Git
- **Decisión**: Utilizar `git-filter-repo` (la herramienta estándar recomendada por Git) o un rebase interactivo automatizado para reescribir el commit `06ad522` sustituyendo los archivos CSV con 872 registros reales por los archivos canónicos anonimizados de 25 registros.
- **Alternativas consideradas**:
  - *Re-crear el repositorio desde cero (`git init`)*: Es rápido y seguro, pero elimina todo el historial de commits intermedios de los workflows.
  - *`git-filter-repo`*: Preserva la cronología de commits pero purga quirúrgicamente los blobs sensibles del árbol de Git.

### 2. Protección de Datos en API REST: API Resources vs Atributos `$hidden`
- **Decisión**: Implementar `TeacherPublicResource` y `ReportPublicResource` en `app/Http/Resources/Api/` en lugar de definir `$hidden` en el modelo Eloquent `Teacher`.
- **Razón**: Modificar `$hidden` en el modelo global puede tener efectos colaterales imprevistos en Filament o en serializaciones internas. Los API Resources desacoplan la capa de representación pública, garantizando que campos como `phone`, `birthDate` y `user_id` nunca se serialicen hacia clientes HTTP anónimos.

### 3. Detección de Secretos en CI/CD con Gitleaks
- **Decisión**: Incorporar el action oficial `gitleaks/gitleaks-action@v2` en el archivo `.github/workflows/ci.yml`.
- **Razón**: Es la herramienta estándar de la industria para prevenir fuga de credenciales, se ejecuta en menos de 5 segundos en GitHub Actions y bloquea cualquier commit entrante que contenga patrones de contraseñas, tokens o llaves privadas.

## Risks / Trade-offs

- **[Riesgo: Reescritura de Historial requiere Push Forzado]** → *Mitigación*: Se documentará claramente la ejecución de `git push --force origin main` y los pasos para sincronizar cualquier clon local secundario.
- **[Riesgo: Clientes externos de la API dependientes de campos suprimidos]** → *Mitigación*: Se documentará en el contrato Swagger que `phone` y `birthDate` son datos privados protegidos por políticas de privacidad de datos universitarios.
