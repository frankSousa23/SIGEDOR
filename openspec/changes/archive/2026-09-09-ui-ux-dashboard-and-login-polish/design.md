## Context

El sistema SIGEDOR utiliza Laravel 11 y Filament v3 para la administración de expedientes universitarios. Los widgets de gráficos analíticos (`TeacherDistributionChart` y `SedeStatsChart`) ya existen y están optimizados con consultas eficientes, pero se omitieron en `Dashboard::getWidgets()`. Por otro lado, la barra de navegación personalizada en `Navigation.php` no expone un enlace raíz al Escritorio, y la página de autenticación carece de atajos ágiles para defensas demostrativas.

## Goals / Non-Goals

**Goals:**
- Desbloquear la visualización ejecutiva del Dashboard integrando los gráficos analíticos por dedicación y sede.
- Proporcionar acceso directo a "Escritorio" en la barra de navegación lateral para todos los roles.
- Activar el colapso del menú lateral en escritorio (`sidebarCollapsibleOnDesktop()`) para ganar espacio horizontal en tablas extensas.
- Dotar a las tablas de `TeacherResource` y `UserResource` de funciones de copia al portapapeles (`copyable()`) y acción modal de visualización de expediente (`ViewAction`).
- Autoseleccionar la identidad del docente en el formulario de solicitudes de permisos cuando el usuario autenticado es un profesor.
- Enriquecer la vista de login con botones de credenciales de demostración en un clic y enlace hacia la Landing Page.

**Non-Goals:**
- No se modifican esquemas de base de datos ni migraciones.
- No se alteran las políticas de autorización (Policies) existentes de Spatie.
- No se introducen dependencias de paquetes externos adicionales.

## Decisions

### Decisión 1: Integración ordenada en `Dashboard::getWidgets()`
- **Alternativa A**: Dejar que Filament descubra automáticamente los widgets. (Descartado: pierde control del orden visual por rol).
- **Alternativa elegida (B)**: Declarar explícitamente en `Dashboard::getWidgets()`:
  1. `StatsOverview::class` (Fila 1: contadores)
  2. `TeacherDistributionChart::class` (Fila 2: dona de dedicaciones)
  3. `SedeStatsChart::class` (Fila 2: barras comparativas de sedes)
  4. `TasksOverview::class` (Fila 3: tabla de usuarios recientes / pendientes)

### Decisión 2: Ítem principal "Escritorio" en `Navigation.php`
- En lugar de forzar al usuario a hacer clic sobre el título o marca de la barra superior, se agrega como primer ítem antes de los grupos:
  ```php
  $navigation->item(
      NavigationItem::make('Escritorio')
          ->icon('heroicon-o-home')
          ->activeIcon('heroicon-s-home')
          ->isActiveWhenPageIs(Dashboard::class)
          ->url(Dashboard::getUrl()),
  );
  ```

### Decisión 3: Interfaz de Login con Autorelleno y Retorno
- Sobrescribir la vista Blade del componente de autenticación de Filament (`resources/views/filament/pages/auth/login.blade.php`) o extender la clase `Login` para proveer botones interactivos con Alpine.js/Livewire que inyecten:
  - Administrador: `admin@sigedor.com / password`
  - Jefe de Área: `areamanager@sigedor.com / password`
  - Docente: `docente@sigedor.com / password`
- Incluir un enlace accesible hacia la ruta raíz `/` (Landing Page).

### Decisión 4: Inspección de Expedientes con `ViewAction` Modal
- En `TeacherResource`, incorporar `Tables\Actions\ViewAction::make()->slideOver()` para permitir a jefes de área y docentes inspeccionar el perfil completo sin riesgo de manipulación accidental de campos en el formulario de edición.

## Risks / Trade-offs

- **[Riesgo] Pantallas pequeñas con tablas de múltiples columnas** → **Mitigación:** Configurar `sidebarCollapsibleOnDesktop()` y marcar columnas de soporte (`phone`, `email`) con `toggleable(isToggledHiddenByDefault: true)` para evitar desplazamientos horizontales innecesarios.
- **[Riesgo] Carga de gráficos sin datos iniciales** → **Mitigación:** Los widgets Chart de Filament cuentan con valores predeterminados `0` y consultas seguras que no arrojan excepciones cuando una tabla está vacía.
