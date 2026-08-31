## Why

Tras consolidar la estabilidad de la arquitectura multi-inquilino (Sede y Área), la suite de testing y la documentación histórica en SIGEDOR v1.0.0, es necesario elevar el sistema a su máximo estándar operativo y visual para la presentación de tesis y uso institucional. Este cambio estructura de forma secuencial 4 fases de mejoras:
1. Reactividad e Inteligencia en Formularios (Filament v3 UX).
2. Inteligencia Visual y Analítica en el Dashboard (Widgets de Gráficos).
3. Exportación Avanzada (Excel/CSV).
4. Trazabilidad y Endpoints de API RESTful documentados con Swagger.

## What Changes

- **Fase 1: Formularios Inteligentes y Reactividad**
  - Auto-completado en `TeacherResource`: al seleccionar `user_id`, se auto-rellenan instantáneamente nombre, apellido, correo institucional, `sede_id` y `area_id` con `->live()`.
  - Selector dependiente en cascada: al seleccionar `sede_id`, el selector de `area_id` y `programa_id` filtra reactivamente las opciones disponibles.
  - Asistente de escalafón en `CategoryResource`: indicador visual de elegibilidad para ascenso de categoría.

- **Fase 2: Analítica y Gráficos en Dashboard**
  - Implementación de `TeacherDistributionChart`: gráfico de dona con distribución de dedicaciones (Exclusiva, Tiempo Completo, Medio Tiempo, Convencional).
  - Implementación de `SedeStatsChart`: gráfico de barras comparativo de docentes y personal por núcleo universitario.

- **Fase 3: Exportación Masiva a Hojas de Cálculo**
  - Acción masiva de exportación en formato Excel / CSV para listados de docentes y reportes oficiales.

- **Fase 4: API RESTful Documentada**
  - Endpoints protegidos en `/api/v1/teachers` y `/api/v1/reports` con anotaciones Swagger OpenAPI 3.0.

## Capabilities

### New Capabilities
- `filament-smart-forms`: Reactividad, auto-llenado y selectores en cascada en formularios administrativos.
- `analytics-dashboard-charts`: Widgets de gráficos interactivos para visualización de estadísticas universitarias en el Dashboard.
- `academic-export-tools`: Herramientas de exportación masiva en formatos de hoja de cálculo (Excel/CSV).
- `public-api-v1`: Endpoints de API RESTful documentados con Swagger para consulta externa de expedientes y reportes.

### Modified Capabilities
<!-- No requirement changes to existing base specs -->

## Impact

- **Código:** Modificaciones en recursos de Filament (`TeacherResource`, `CategoryResource`, `UserResource`) y nuevos widgets en `app/Filament/Widgets/`.
- **APIs:** Nuevos controladores bajo `app/Http/Controllers/Api/` con documentación `@OA`.
- **Rendimiento y Dependencias:** Integración de Chart.js nativo de Filament sin sobrecarga de dependencias pesadas.

