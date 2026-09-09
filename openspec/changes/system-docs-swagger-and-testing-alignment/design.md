## Context

Véase `proposal.md` para la motivación general. El sistema SIGEDOR ha alcanzado un alto nivel de madurez técnica con soporte para expedientes 360°, plantillas institucionales UNERG, multi-inquilino estricto y auditoría con 46 pruebas automatizadas. Sin embargo, la documentación técnica en `docs/` se originó a partir de plantillas iniciales genéricas que nunca se sincronizaron con la implementación real. Adicionalmente, los recientes campos del modelo de reportes (`verification_code`, `status`, `created_by`) no fueron incorporados a los recursos públicos API ni al esquema de OpenAPI / Swagger.

## Goals / Non-Goals

**Goals:**
- Reescribir y sincronizar completamente los 7 archivos desfasados en `docs/` (`models.md`, `database-schema.md`, `filament-resources.md`, `security.md`, `system-flow.md`, `architecture.md`, `ui.md`) garantizando 100% de coherencia con el código de Laravel 11 y Filament v3.
- Actualizar `README.md` corrigiendo el conteo de migraciones (24), guías (15) y destacando las capacidades del expediente 360° y sincronización automática.
- Enriquecer `ReportPublicResource` y `App\Virtual\Models\Report` con `verification_code`, `status` y `created_by`.
- Documentar el endpoint `GET /api/ping` en Swagger mediante un controlador o anotaciones OpenAPI `@OA\Get` dedicadas.
- Regenerar el artefacto OpenAPI compilado (`storage/api-docs/api-docs.json`) vía `php artisan l5-swagger:generate`.
- Aclarar en los PHPDoc y comentarios de código clave la distinción entre `Site` (asignación y cátedra) y `Sede` (recinto universitario físico).
- Ampliar `tests/Feature/ApiTest.php` con aserciones sobre `verification_code` y `status`.

**Non-Goals:**
- No se alterarán los contratos de base de datos existentes ni se crearán nuevas migraciones estructurales, ya que el esquema de base de datos actual es completo y estable.
- No se modificará la lógica de negocio ni las reglas de autorización previamente aprobadas y testeadas.

## Decisions

### 1. Documentación Técnica en `docs/`: Precisión Real vs Snippets Ficticios
- **Decisión**: Reemplazar todo el contenido genérico de tutorial por la documentación técnica real y precisa de SIGEDOR. Cada tabla, modelo, política y recurso de Filament se documentará con sus nombres de columnas exactos, casts y métodos en uso.
- **Alternativa considerada**: Dejar `docs/` como referencia conceptual abstracta. Se descartó porque genera confusión crítica tanto para nuevos desarrolladores como para jurados de tesis o evaluadores de arquitectura.

### 2. Actualización de API Pública (`ReportPublicResource`)
- **Decisión**: Exponer `verification_code`, `status` y `created_by` como propiedades directas de primer nivel en `ReportPublicResource` y documentarlas en `App\Virtual\Models\Report`.
- **Alternativa considerada**: Ocultar el código de verificación en la API. Se descartó porque la verificación de autenticidad documental es precisamente uno de los principales casos de uso de interoperabilidad externa.

### 3. Documentación OpenAPI para HealthCheck (`/api/ping`)
- **Decisión**: Extraer la lógica de `/api/ping` hacia un controlador `App\Http\Controllers\Api\HealthApiController` con anotaciones `@OA\Get` estandarizadas de Swagger.
- **Alternativa considerada**: Dejar la ruta anónima en `routes/api.php` sin Swagger. Se descartó porque Swagger debe reflejar el 100% de los endpoints públicos del sistema.

### 4. Cobertura de Pruebas
- **Decisión**: Extender `tests/Feature/ApiTest.php` para validar que `/api/v1/reports` entregue los campos de verificación y estado, y añadir una prueba que verifique que el comando de Swagger regenere la documentación sin errores.

## Risks / Trade-offs

- **[Riesgo] Fuga involuntaria de PII en la API de Reportes** → *Mitigación*: `ReportPublicResource` continúa transformando la relación `teacher` a través de `TeacherPublicResource`, el cual ya filtra estrictamente teléfonos, fechas de nacimiento e ID de usuario.
- **[Riesgo] Discrepancia futura en documentación** → *Mitigación*: Mantener una relación directa entre los modelos reales y las tablas descritas en `database-schema.md` y `models.md`.
