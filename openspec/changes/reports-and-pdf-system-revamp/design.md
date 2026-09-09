## Context

El sistema SIGEDOR utiliza Laravel 11, Filament v3 y DomPDF (`barryvdh/laravel-dompdf`). El modelo `Report` registra informes oficiales asociados a un docente mediante `teacher_cdi`. Actualmente, las políticas de seguridad en `ReportPolicy` intentaban consultar un atributo inexistente `$report->user_id`, y el recurso `ReportResource` no restringía las consultas del rol `teacher`. Adicionalmente, las plantillas PDF en `resources/views/pdf/` carecían de estandarización visual con el membrete institucional de la Universidad Nacional Experimental "Rómulo Gallegos" (UNERG), sellos, firmas y códigos de validación documental.

Para la motivación general del cambio, véase `proposal.md`.

## Goals / Non-Goals

**Goals:**
- Enforzar aislamiento de datos multi-inquilino en `ReportPolicy` y `ReportResource` para Administrador, Jefe de Área y Docente.
- Incorporar trazabilidad documental mediante código de verificación único (`verification_code`), autoría (`created_by`) y estado (`status`) en `reports`.
- Rediseñar la plantilla base (`pdf.base`) y las vistas especializadas (`pdf.report`, `pdf.reports`) con membrete oficial UNERG, logo nítido, tipografía formal y cajas reglamentarias de firmas y sellos.
- Integrar reportes en el expediente docente (`TeacherResource`) mediante un RelationManager (`ReportsRelationManager`) y acción rápida de constancia.
- Integrar la emisión de memorando oficial desde el módulo de permisos (`PermissionTeacherResource`) tras la aprobación de una solicitud.

**Non-Goals:**
- Reemplazar el motor DomPDF por Browsershot/Puppeteer (se mantiene DomPDF para evitar dependencias pesadas de Node/Chromium en servidores locales).
- Implementar firma electrónica criptográfica PKI/X.509 avanzada (se utiliza verificación documental por código único de auditoría institucional y representación visual formal de firmas y sellos).

## Decisions

### 1. Generación de Código de Verificación Documental en Modelo Eloquent
- **Decisión**: Autogenerar `verification_code` en el evento Eloquent `creating` del modelo `Report` con formato `UNERG-SIGEDOR-{AÑO}-{RANDOM_HEX}`, almacenándolo en la columna indexada `verification_code`.
- **Alternativa descartada**: Generar el código en el controlador/acción de Filament. Se descartó porque no cubriría reportes creados mediante comandos, seeders o APIs externas.

### 2. Aislamiento por Roles en Eloquent Query Scopes
- **Decisión**: Implementar el filtrado en `ReportResource::getEloquentQuery()`:
  - `admin`: ve todos los reportes.
  - `area_manager`: filtra por `sede_id === $user->sede_id`.
  - `teacher`: filtra donde `teacher_cdi` coincida con el perfil docente del usuario autenticado (`$user->teacher?->cdi`).
- **Alternativa descartada**: Confiar exclusivamente en Filament Table Filters. Los filtros de tabla son seleccionables por el usuario y no previenen acceso directo ni filtran de manera obligatoria.

### 3. Plantillas Especializadas con Herencia Limpia en Blade
- **Decisión**: Modularizar `resources/views/pdf/report.blade.php` para renderizar condicionalmente según `$report->typeReport`:
  - `Constancia de Trabajo`: texto formal tipo certificación institucional.
  - `Memorando Administrativo`: formato oficial de memorando (`DE:`, `PARA:`, `ASUNTO:`).
  - `Informe de Escalafón`: resumen tabular de ascensos y categorías.
  - `Informe de Dedicación`: carga horaria y funciones asignadas.
  Todas heredan de `pdf.base` que define el membrete UNERG, pie de página legal, logo y fuentes compatibles con UTF-8 (`Helvetica`, `Arial`).
- **Alternativa descartada**: Crear 4 archivos de vista separados para cada tipo de reporte. Se descartó para evitar duplicidad y mantener compatibilidad con las llamadas existentes `Pdf::loadView('pdf.report', ['report' => $record])`.

### 4. Relación `ReportsRelationManager` en `TeacherResource`
- **Decisión**: Añadir `ReportsRelationManager` a `TeacherResource::getRelations()`.
- **Alternativa descartada**: Agregar un modal simple. El `RelationManager` proporciona capacidades completas de paginación, filtros por tipo y descarga directa sin salir del contexto del docente.

## Risks / Trade-offs

- **[Riesgo] Migración en base de datos con registros existentes**: Si existen reportes previos sin `verification_code`.
  - *Mitigación*: La migración creará las columnas como `nullable` temporalmente o ejecutará un script que asigne códigos únicos retrospectivos a los registros existentes antes de aplicar restricciones de unicidad.
- **[Riesgo] Renderizado de imágenes en DomPDF**: Rutas relativas o URLs remotas pueden fallar si `enable_remote` está deshabilitado.
  - *Mitigación*: Se utiliza `public_path('images/LogoUnerg.png')` con verificación de existencia previa (`file_exists`), garantizando lectura desde el sistema de archivos local.
- **[Riesgo] Desbordamiento de texto en tablas PDF**:
  - *Mitigación*: CSS con `word-wrap: break-word`, anchos porcentuales fijos en `td/th` y directiva `page-break-inside: avoid` en bloques de firmas.

## Migration Plan

1. Ejecutar migración `add_verification_and_status_to_reports_table`.
2. Actualizar modelo `Report` y política `ReportPolicy`.
3. Actualizar vistas Blade de PDF (`pdf.base`, `pdf.report`, `pdf.reports`).
4. Actualizar `ReportResource`, `TeacherResource` y `PermissionTeacherResource`.
5. Ejecutar suite de pruebas Pest y validar no regresión.
