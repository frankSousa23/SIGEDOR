## Purpose

Establece defensas perimetrales y de infraestructura para el repositorio de código, previniendo la fuga accidental de credenciales, el envenenamiento de paquetes, la indexación indebida y la captura de contraseñas en telemetría.

## ADDED Requirements

### Requirement: Exclusión Preventiva en .gitignore
El archivo `.gitignore` DEBE prevenir proactivamente la inclusión de cualquier archivo de variables de entorno locales, volcados de base de datos y temporales de compilación en el control de versiones.

#### Scenario: Creación de archivo de entorno local alternativo
- **WHEN** un desarrollador crea un archivo `.env.local` o genera un volcado `backup.sql` en la raíz del proyecto
- **THEN** Git ignora automáticamente dichos archivos impidiendo que sean agregados al área de staging (`git status`)

### Requirement: Enforzamiento de Transporte Seguro en Dependencias
La configuración del gestor de dependencias de PHP DEBE exigir transporte cifrado HTTPS para todos los paquetes y metadatos remotos.

#### Scenario: Instalación o actualización de dependencias
- **WHEN** se ejecuta `composer install` o `composer update`
- **THEN** el gestor valida que todas las descargas se realicen estrictamente sobre HTTPS con la opción `secure-http` habilitada

### Requirement: Sanitización de Credenciales en Telemetría
El sistema de monitoreo interno (Laravel Telescope) DEBE omitir el registro de contraseñas, confirmaciones y cabeceras de autorización en sus entradas persistidas.

#### Scenario: Registro de petición con credenciales
- **WHEN** un usuario realiza un inicio de sesión o envía una petición con contraseña o cabecera `Authorization`
- **THEN** los detalles registrados en Telescope enmascaran dichos valores impidiendo su exposición en la base de datos de logs

### Requirement: Desautorización de Indexación en Rutas Sensibles
El archivo de control de robots (`public/robots.txt`) DEBE indicar a los motores de búsqueda que no rastreen ni indexen las áreas de administración ni telemetría.

#### Scenario: Rastreo por bots de búsqueda
- **WHEN** un crawler de indexación consulta `/robots.txt`
- **THEN** el archivo responde con directivas explícitas de `Disallow` para `/admin/`, `/telescope/` y `/api/`

### Requirement: Detección Automatizada de Secretos en CI/CD
El flujo de integración continua en GitHub Actions DEBE ejecutar un análisis estático de detección de secretos antes de permitir la fusión de código.

#### Scenario: Intento de commit con credenciales expuestas
- **WHEN** se dispara el pipeline de CI ante un push o pull request
- **THEN** una herramienta de escaneo de secretos (Gitleaks) audita los cambios y aborta la ejecución si detecta patrones de claves o tokens privados
