## ADDED Requirements

### Requirement: Bloqueo de Elevación en Tablas Administrativas
El sistema DEBE restringir cualquier control de activación o aprobación de usuarios (`is_approved`, `is_active`) en columnas y acciones de tablas administrativas exclusivamente a usuarios con rol `admin`, impidiendo la manipulación de estados por parte de jefes de área u otros roles.

#### Scenario: Jefe de área intenta alternar estado en tabla
- **WHEN** un usuario con rol `area_manager` visualiza la tabla de usuarios
- **THEN** los controles interactivos de aprobación y activación permanecen deshabilitados impidiendo cualquier cambio de estado

### Requirement: Validación Real de Identidad Docente en Políticas
Las políticas de seguridad del modelo docente DEBEN validar la titularidad del usuario comparando la cédula del expediente (`cdi`) contra el perfil docente vinculado al usuario en sesión (`$user->teacher?->cdi`), garantizando que la autorización no falle por atributos inexistentes.

#### Scenario: Docente consulta o actualiza su propio perfil
- **WHEN** un docente autenticado intenta acceder a sus datos personales o expediente
- **THEN** el sistema valida exitosamente la coincidencia entre el perfil docente vinculado al usuario y la cédula del registro

### Requirement: Segregación de Funciones en Solicitudes de Permisos
El sistema DEBE restringir la modificación del estado de permisos (`status`) a los roles con atribuciones de gestión (`admin` y `area_manager`), forzando el estado inicial `pending` cuando la solicitud es creada por un docente.

#### Scenario: Docente registra una solicitud de permiso
- **WHEN** un docente completa el formulario de solicitud de permiso
- **THEN** el campo de estado se registra automáticamente como pendiente sin permitir su modificación directa a aprobado o rechazado
