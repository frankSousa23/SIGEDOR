## 1. Seguridad, Control de Acceso y Políticas RBAC

- [x] 1.1 Blindar `UserResource::table` deshabilitando `ToggleColumn::make('is_approved')` y `ToggleColumn::make('is_active')` para roles no administradores y verificar que no puedan mutarse vía AJAX.
- [x] 1.2 Corregir `TeacherPolicy::update` para evaluar la titularidad con `$user->teacher?->cdi` o `$teacher->user_id === $user->id`, y alinear la restricción de sede y área para jefes de área.
- [x] 1.3 Saneamiento de `CategoryPolicy` incorporando operadores nullsafe (`$category->teacher?->sede_id`) y habilitando a los jefes de área para registrar categorías a docentes de su sede.
- [x] 1.4 Restringir en `PermissionTeacherResource` la edición del campo `status` e `is_paid` para docentes en la solicitud inicial, forzando el estado pendiente.

## 2. Integridad Relacional y Sincronización Automática

- [x] 2.1 Implementar hooks de ciclo de vida (`booted`) en el modelo `Category` para sincronizar automáticamente `teachers.category_id` al guardar o eliminar.
- [x] 2.2 Implementar hooks de ciclo de vida (`booted`) en el modelo `Dedication` para sincronizar automáticamente `teachers.dedication_id` al guardar o eliminar.
- [x] 2.3 Implementar hooks de ciclo de vida (`booted`) en el modelo `Site` para sincronizar automáticamente `teachers.site_id` al guardar o eliminar.
- [x] 2.4 Crear y ejecutar migración de datos para sincronizar retroactivamente `category_id`, `dedication_id` y `site_id` en todos los registros existentes de `teachers`.

## 3. Formulario y Flujo de Permisos y Carga Horaria

- [x] 3.1 Exponer y autocompletar el campo obligatorio `name` en `PermissionTeacherResource` y añadir hook de respaldo en `PermissionTeacher::booted()` previniendo fallos por valores vacíos.
- [x] 3.2 Conectar cálculo reactivo de `end_date` en `PermissionTeacherResource` a partir de `start_date` y `duration_type` (`calculateEndDate()`).
- [x] 3.3 Implementar validación de horas reglamentarias por tipo de dedicación en `DedicationResource` conforme a la normativa UNERG.

## 4. Expediente Integral 360° en TeacherResource

- [x] 4.1 Crear `PermissionsRelationManager` en `TeacherResource` para visualizar el historial de permisos y licencias del docente.
- [x] 4.2 Crear `CategoryRelationManager` y `DedicationRelationManager` en `TeacherResource` para consultar escalafón y dedicación horaria en la misma vista.
- [x] 4.3 Registrar los nuevos RelationManagers en `TeacherResource::getRelations()` y verificar la visualización unificada en el panel.

## 5. Pruebas Automatizadas y Validación

- [x] 5.1 Crear prueba `tests/Feature/AcademicWorkflowLifecycleTest.php` verificando la prevención de escalada de privilegios, sincronización automática de relaciones y flujo de permisos.
- [x] 5.2 Ejecutar la suite completa de pruebas Pest (`vendor/bin/pest`) confirmando el 100% de pruebas aprobadas.
- [x] 5.3 Ejecutar `vendor/bin/pint --test` y `openspec validate --all` confirmando el cumplimiento de los estándares de código y especificaciones.

