## Purpose

Define las reglas de control de acceso basado en roles (RBAC) y la prevención de escalada de privilegios y fugas de datos multi-inquilino en los formularios y tablas administrativas de Filament.

## ADDED Requirements

### Requirement: Prevención de Escalada de Privilegios en Usuarios
El sistema DEBE restringir la asignación de roles de sistema y la aprobación de cuentas exclusivamente a usuarios con rol `admin`, bloqueando cualquier modificación de estos campos por parte de jefes de área u otros roles.

#### Scenario: Jefe de área edita usuario de su sede
- **WHEN** un usuario con rol `area_manager` abre el formulario de edición de un usuario perteneciente a su sede
- **THEN** los controles de selección de roles y activación/aprobación de cuenta permanecen deshabilitados o no visibles para su perfil

### Requirement: Cobertura Integral de Políticas de Autorización
Todos los recursos institucionales (`Dedication`, `PermissionTeacher`, `Category`, `Site`, `Teacher`, `User`, `Report`) DEBEN contar con una clase de política registrada en el proveedor de autenticación que evalúe permisos Spatie antes de permitir acciones de visualización, creación, edición o eliminación.

#### Scenario: Intento de eliminación no autorizada
- **WHEN** un usuario sin permiso de borrado intenta ejecutar la acción de eliminar sobre una dedicación o permiso docente
- **THEN** el sistema deniega la acción y oculta el botón correspondiente en la interfaz

### Requirement: Aislamiento Multi-Inquilino en Formularios Administrativos
Los campos de selección de docentes en los formularios de gestión académica DEBEN limitar sus opciones a los docentes adscritos a la sede del usuario en sesión cuando este posea el rol de `area_manager`.

#### Scenario: Jefe de área abre formulario de asignación
- **WHEN** un `area_manager` de la Sede Calabozo abre el formulario para registrar un permiso, categoría o dedicación
- **THEN** el listado desplegable de docentes únicamente contiene docentes adscritos a la Sede Calabozo
