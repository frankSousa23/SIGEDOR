# Documento de Diseño Técnico: Mejoras Secuenciales SIGEDOR

## Context

El sistema opera sobre Laravel 11.x y Filament v3.x con SQLite/MySQL. La base del sistema multi-inquilino con Sede y Área está estabilizada. Este diseño aborda la integración técnica de las 4 fases de mejora sin alterar los modelos de datos existentes ni romper las políticas de seguridad.

## Goals / Non-Goals

**Goals:**
- Implementar reactividad en vivo en TeacherResource usando la API nativa de Filament v3 (live(), fterStateUpdated(), Get , Set ).
- Añadir widgets gráficos de Chart.js (DoughnutChartWidget y BarChartWidget) en pp/Filament/Widgets/ compatibles con los permisos de rol.
- Implementar exportaciones a CSV mediante acciones nativas en tablas de Filament sin dependencias externas pesadas.
- Exponer controladores API bajo pp/Http/Controllers/Api/ con anotaciones @OA de L5-Swagger.

**Non-Goals:**
- Modificar el esquema de base de datos o migraciones de tablas núcleo.
- Romper la compatibilidad de los tests existentes de Pest.

## Decisions

1. **Reactividad Nativa de Filament v3:**
   - *Decisión:* Usar Select::make('user_id')->live()->afterStateUpdated(...) en lugar de AJAX manual o Livewire personalizado.
   - *Razón:* Mantiene la consistencia del framework y aprovecha la hidratación segura de estados.

2. **Widgets de Gráficos Nativos:**
   - *Decisión:* Extender Filament\\Widgets\\ChartWidget nativo de Filament (basado en Chart.js embebido).
   - *Razón:* Cero dependencias externas adicionales de npm; renderizado limpio y responsivo.

3. **Generación de CSV Nativa en PHP:**
   - *Decisión:* Usar streams de descarga (esponse()->streamDownload(...) con putcsv).
   - *Razón:* Máxima velocidad de exportación y bajo consumo de memoria RAM en el servidor.

4. **Documentación OpenAPI Centralizada:**
   - *Decisión:* Integrar los nuevos endpoints en outes/api.php bajo el prefijo 1 y regenerar con php artisan l5-swagger:generate.
   - *Razón:* Cumple el estándar OpenAPI 3.0 para la defensa técnica de la tesis.

## Risks / Trade-offs

- **[Riesgo]** Sobrecarga de peticiones AJAX en formularios con live() → **[Mitigación]** Usar debounce(500ms) en campos de texto y reactividad inmediata solo en selects.
- **[Riesgo]** Queries pesadas en widgets de gráficos para grandes volúmenes de datos → **[Mitigación]** Realizar agrupaciones con COUNT(*) y groupBy() a nivel de base de datos.

