## Context

En SIGEDOR coexisten dos esquemas de relación para el profesorado: la clave natural `cdi` (utilizada por `Category`, `Dedication`, `Site`, `PermissionTeacher` y `Report`) y las claves foráneas numéricas `category_id`, `dedication_id` y `site_id` en la tabla `teachers`. Debido a la ausencia de sincronización automática en el ciclo de vida de los modelos, el registro de escalafones o dedicaciones desde Filament no actualiza la tabla de docentes, provocando inconsistencias visuales y operativas ("Sin asignar"). Adicionalmente, el modelo `User` no posee columna `cdi`, generando fallos lógicos en políticas de seguridad, y la tabla `permissionsteachers` exige un campo `name` que no existe en el formulario administrativo.

## Goals / Non-Goals

**Goals:**
- Implementar sincronización automática bidireccional mediante eventos de Eloquent (`booted`) en `Category`, `Dedication` y `Site` para mantener actualizados `category_id`, `dedication_id` y `site_id` en `teachers`.
- Blindar las columnas interactivas (`ToggleColumn`) en `UserResource` para impedir la aprobación o activación de cuentas por usuarios con rol `area_manager`.
- Corregir las políticas `TeacherPolicy`, `CategoryPolicy` y `DedicationPolicy` asegurando evaluaciones nullsafe y validando la titularidad a través de `$user->teacher?->cdi` o `$teacher->user_id === $user->id`.
- Dotar al recurso `PermissionTeacherResource` del campo `name` con valor sugerido reactivo, cálculo automático de `end_date` y bloqueo de `status` para el rol docente.
- Integrar en `TeacherResource` los relation managers de Permisos, Escalafón y Carga Horaria para conformar un expediente 360°.
- Crear migración/comando de reparación de datos para sincronizar los registros históricos huérfanos.

**Non-Goals:**
- Eliminar las columnas `teacher_cdi` de las tablas secundarias (se conservan por compatibilidad estricta con seeders CSV e historial).
- Modificar el flujo de autenticación o el dominio institucional forzado `@sigedor.com`.

## Decisions

### 1. Sincronización a nivel de Modelo Eloquent (`booted`)
- **Decisión**: Los eventos `saved()` y `deleted()` en los modelos `Category`, `Dedication` y `Site` actualizarán directamente el `Teacher` vinculado a través de `teacher_cdi`.
- **Alternativa descartada**: Mutar únicamente los formularios Filament (`CreateRecord` / `EditRecord`). Se descartó porque dejaría sin sincronizar importaciones, seeders, pruebas y llamadas por API.
- **Detalle de implementación**:
  ```php
  static::saved(function ($model) {
      Teacher::where('cdi', $model->teacher_cdi)
          ->update(['category_id' => $model->id]);
  });
  ```

### 2. Auto-relleno de `name` y Reactividad de Fechas en Permisos
- **Decisión**: Incorporar el campo `name` en el formulario con valor predeterminado dinámico (ej: `"[Tipo] - [Año]"`) y conectar un hook `afterStateUpdated` en `start_date` y `duration_type` que invoque `calculateEndDate()`.
- **Alternativa descartada**: Hacer el campo `name` nullable en la base de datos. Se descartó para no alterar la estructura ni romper contratos existentes.

### 3. Blindaje de Tablas Administrativas en Filament
- **Decisión**: Añadir `->disabled(fn () => ! auth()->user()?->isAdmin())` en las columnas `ToggleColumn` de `is_approved` e `is_active` en `UserResource`.
- **Razón**: Las `ToggleColumn` de Filament ejecutan mutaciones AJAX inmediatas que solo verifican el método `update` de la política general. Al deshabilitarlas a nivel de columna, se corta la brecha de escalada de privilegios.

### 4. Gestores de Relación para Expediente 360°
- **Decisión**: Crear `PermissionsRelationManager`, `CategoryRelationManager` y `DedicationRelationManager` e incorporarlos en `TeacherResource::getRelations()`.
- **Razón**: Brinda al personal administrativo y jefes de área una visión unificada del estado del docente sin necesidad de saltar entre múltiples menús.

## Risks / Trade-offs

- **[Riesgo de Registros Históricos Desconectados]** → *Mitigación*: Incluir una migración de datos que ejecute una sincronización masiva para todos los docentes existentes en la base de datos.
- **[Riesgo de Recursión en Eventos Eloquent]** → *Mitigación*: Utilizar `update()` en consultas directas o comprobar `isDirty()` antes de disparar actualizaciones secundarias.
