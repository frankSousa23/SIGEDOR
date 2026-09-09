# landing-page-flow Specification

## Purpose
Asegurar que la Landing Page sea el punto de entrada principal correcto, esté actualizada y dirija adecuadamente a las rutas de inicio de sesión.

## Requirements

### Requirement: Enrutamiento de la Ruta Raíz
El sistema DEBE renderizar la Landing Page pública cuando un visitante no autenticado acceda a la ruta `/`.

#### Scenario: Acceso de visitante
- **WHEN** un usuario no autenticado navega a la URL raíz (`/`)
- **THEN** el sistema carga la vista principal (Landing Page) sin forzar redirección al panel administrativo de login

### Requirement: Botones de Acción Funcionales
El sistema DEBE proveer enlaces claros en la Landing Page que dirijan correctamente a la interfaz de acceso (`/admin/login`).

#### Scenario: Acceso administrativo desde la Landing
- **WHEN** el usuario hace clic en el botón de acceso de la Landing Page
- **THEN** es redirigido correctamente a la página de login de Filament

### Requirement: Accesos de Demostración y Retorno en Inicio de Sesión
La vista de inicio de sesión (`/admin/login`) DEBE proveer botones interactivos o selectores de credenciales demostrativas preconfiguradas y un enlace de retorno visible hacia la página principal pública.

#### Scenario: Uso de atajo de credenciales en login
- **WHEN** un usuario hace clic en el botón de acceso de demostración (Administrador, Jefe de Área o Docente)
- **THEN** los campos de correo y contraseña se rellenan automáticamente con las credenciales demo correspondientes

