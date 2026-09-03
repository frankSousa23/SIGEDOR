## Purpose

Define el comportamiento observable de la carga y coherencia de datos iniciales del sistema SIGEDOR mediante seeders y archivos CSV, garantizando la integridad referencial y la capacidad de inicio de sesión de todos los usuarios importados.

## ADDED Requirements

### Requirement: Coherencia Relacional en Ingesta CSV
El sistema DEBE importar los datos de docentes y sus relaciones correspondientes (categorías, dedicaciones, sedes) garantizando que cada registro hijo coincida exactamente con la clave de cédula (`cdi`) del docente padre.

#### Scenario: Carga completa de seeders sin exclusiones
- **WHEN** un administrador o desarrollador ejecuta `php artisan db:seed`
- **THEN** el sistema registra con éxito 25 docentes, 25 categorías, 25 dedicaciones y 25 asignaciones de sede con sus llaves foráneas enlazadas al 100%

### Requirement: Autenticabilidad de Usuarios Importados
Todos los usuarios y cuentas de docentes creados o importados desde los archivos de semillas DEBEN poseer una dirección de correo electrónico perteneciente al dominio `@sigedor.com` y una contraseña cifrada válida.

#### Scenario: Inicio de sesión de docentes importados
- **WHEN** un docente registrado desde `teachers.csv` introduce sus credenciales institucionales en la pantalla de acceso
- **THEN** el sistema valida satisfactoriamente la regla de dominio institucional `@sigedor.com` y permite el inicio de sesión
