## Why

Actualmente el panel administrativo de SIGEDOR posee widgets gráficos de dedicación y sedes que no se renderizan en el Dashboard principal por falta de registro en la vista, la navegación carece de un acceso directo visible al "Escritorio", y la experiencia de inicio de sesión (`/admin/login`) no cuenta con atajos demostrativos ni retorno a la página principal. Esta propuesta resuelve estos cuellos de botella de usabilidad y enriquece la experiencia visual (UI/UX) del sistema para uso institucional y defensas demostrativas.

## What Changes

- **Dashboard Analítico**: Exponer los widgets interactivos `TeacherDistributionChart` (dona de dedicaciones) y `SedeStatsChart` (barras territoriales) en `Dashboard.php` para administradores y jefes de área.
- **Navegación Intuitiva**: Incorporar el ítem directo de navegación "Escritorio" (`heroicon-o-home`) en `Navigation.php` para todos los roles.
- **Micro-interacciones en Tablas**: Agregar soporte `copyable()` en columnas de cédula (`cdi`) y correo institucional (`email`), e incorporar acción modal de solo lectura `ViewAction` en `TeacherResource`.
- **Experiencia de Inicio de Sesión y Demos**: Agregar en `/admin/login` botones de llenado rápido de credenciales de prueba (Admin, Jefe de Área, Docente) y enlace de navegación hacia la Landing Page.
- **Smart Forms para Permisos**: Autoseleccionar y bloquear el campo de docente solicitante cuando el usuario autenticado posee el rol docente.
- **Paleta y Tipografía Unificada**: Configurar la tipografía `Outfit` y paleta armónica en `AdminPanelProvider` con soporte `sidebarCollapsibleOnDesktop()`.

## Capabilities

### Modified Capabilities
- `ui-ux-enhancements`: Incorporación visual de gráficos interactivos en el Dashboard, navegación con acceso directo a Escritorio, panel con barra lateral colapsable y acciones `copyable`/`ViewAction` en tablas.
- `filament-smart-forms`: Autoselección y bloqueo de identidad para solicitudes de permisos realizadas por usuarios con rol docente.
- `landing-page-flow`: Relleno ágil de credenciales de demostración en el formulario de login y enlace de retorno a la landing page.

## Impact

- **Código Afectado**:
  - `app/Filament/Pages/Dashboard.php`
  - `app/Filament/Pages/Navigation.php`
  - `app/Filament/Pages/Auth/Login.php`
  - `resources/views/filament/pages/auth/login.blade.php` (o vista personalizada de autenticación)
  - `app/Filament/Resources/TeacherResource.php`
  - `app/Filament/Resources/PermissionTeacherResource.php`
  - `app/Providers/Filament/AdminPanelProvider.php`
- **APIs y Base de Datos**: Sin cambios destructivos ni modificaciones de esquema de base de datos.
- **Dependencias**: Utiliza las capacidades nativas de Filament v3 y Chart.js ya instaladas.
