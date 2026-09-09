## ADDED Requirements

### Requirement: Exposición de Métricas y Gráficos en Dashboard
El sistema DEBE exponer visualmente en la página principal del panel administrativo (`/admin`) los gráficos interactivos de distribución de dedicaciones y estadísticas por sede, adaptando su visualización a los roles de Administrador y Jefe de Área.

#### Scenario: Visualización de gráficos en el panel de control
- **WHEN** un usuario con rol de Administrador o Jefe de Área accede al escritorio principal
- **THEN** el sistema renderiza los componentes gráficos analíticos permitiendo inspeccionar la distribución docente y territorial

### Requirement: Enlace Directo a Escritorio en Menú Lateral
La barra de navegación lateral DEBE presentar un elemento principal de navegación "Escritorio" accesible para todos los roles del sistema, permitiendo volver a la pantalla inicial con un solo clic.

#### Scenario: Retorno al escritorio desde cualquier módulo
- **WHEN** un usuario autenticado hace clic en el ítem "Escritorio" en la barra lateral
- **THEN** el sistema lo redirige a la vista principal del panel (`/admin`)

### Requirement: Acciones de Consulta y Copia en Tablas Administrativas
Las tablas de gestión de docentes y usuarios DEBEN proveer utilidades de copia rápida al portapapeles en identificadores críticos (cédula y correo electrónico) y una acción de inspección de expediente en modo solo lectura (`ViewAction`).

#### Scenario: Copia de cédula o correo desde la tabla
- **WHEN** un usuario hace clic sobre una cédula o correo habilitado para copia en la tabla
- **THEN** el valor se copia inmediatamente al portapapeles del sistema operativo y se notifica la acción
