# Tasks: ui-ux-dashboard-and-login-polish

## 1. Dashboard y Navegación

- [x] 1.1 Registrar `TeacherDistributionChart` y `SedeStatsChart` en `app/Filament/Pages/Dashboard.php` y verificar su renderizado en el escritorio.
- [x] 1.2 Incorporar el ítem directo de navegación "Escritorio" (`heroicon-o-home`) en `app/Filament/Pages/Navigation.php` antes de los grupos y verificar su funcionalidad para todos los roles.
- [x] 1.3 Configurar `sidebarCollapsibleOnDesktop()`, tipografía `Outfit` y paleta semántica (`gray => Slate`) en `app/Providers/Filament/AdminPanelProvider.php`.

## 2. Tablas Dinámicas y Micro-Interacciones

- [x] 2.1 Agregar `copyable()` con mensaje de confirmación a las columnas `cdi` y `email` en `TeacherResource.php` y `UserResource.php`.
- [x] 2.2 Agregar `Tables\Actions\ViewAction::make()->slideOver()` en `TeacherResource.php` para consulta de expediente en modo lectura.
- [x] 2.3 Configurar `toggleable(isToggledHiddenByDefault: true)` en columnas secundarias de `TeacherResource.php`.
- [x] 2.4 Asignar colores semánticos jerárquicos a la columna de categoría docente en `TeacherResource.php`.

## 3. Formularios Inteligentes para Docentes

- [x] 3.1 Actualizar el campo `teacher_cdi` en `PermissionTeacherResource.php` para autoseleccionar y bloquear la cédula del docente cuando el usuario autenticado posee el rol docente.

## 4. Experiencia de Acceso y Demostración en Login

- [x] 4.1 Enriquecer la vista de inicio de sesión (`/admin/login`) con botones interactivos de relleno rápido de credenciales de prueba (Admin, Jefe de Área, Docente) y enlace de navegación hacia la página principal.

## 5. Verificación Integral y Calidad

- [x] 5.1 Ejecutar `vendor/bin/pest` y confirmar que el 100% de las pruebas automatizadas pasen en verde.
- [x] 5.2 Ejecutar `vendor/bin/pint --test` para verificar el cumplimiento de estilo de código Laravel.
- [x] 5.3 Ejecutar `openspec validate --all` para asegurar la coherencia de los artefactos del cambio.
