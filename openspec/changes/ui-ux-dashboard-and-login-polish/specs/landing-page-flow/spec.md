## ADDED Requirements

### Requirement: Accesos de Demostración y Retorno en Inicio de Sesión
La vista de inicio de sesión (`/admin/login`) DEBE proveer botones interactivos o selectores de credenciales demostrativas preconfiguradas y un enlace de retorno visible hacia la página principal pública.

#### Scenario: Uso de atajo de credenciales en login
- **WHEN** un usuario hace clic en el botón de acceso de demostración (Administrador, Jefe de Área o Docente)
- **THEN** los campos de correo y contraseña se rellenan automáticamente con las credenciales demo correspondientes
