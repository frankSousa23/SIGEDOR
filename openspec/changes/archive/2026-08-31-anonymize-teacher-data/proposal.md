## Why

El sistema fue cargado originalmente con datos reales del personal docente de la universidad a través de un archivo CSV (`teachers.csv`). Para poder realizar pruebas de carga masiva, mantenimiento y despliegue del sistema sin comprometer datos sensibles, necesitamos ofuscar (hacer aleatoria) toda la información personal (nombres, correos, cédulas, teléfonos) dentro de ese archivo y restaurar la base de datos para probar su funcionamiento.

## What Changes

- Crear un comando de Artisan (`AnonymizeTeachersCsv`) para iterar el CSV original y sustituir la información sensible usando `FakerPHP`.
- Respetar intactas las relaciones (Sede, Área, Programa y Fechas de Ascenso) para que la lógica de seeders mantenga consistencia con los datos que son públicos/organizacionales.
- Ejecutar un refresh completo de la base de datos (con `migrate:fresh --seed`).
- Probar que la carga masiva funciona adecuadamente y deja al sistema con un entorno limpio, el administrador principal creado y cientos de docentes ficticios cargados correctamente.

## Capabilities

### New Capabilities
<!-- Ninguna, este cambio es puramente de tooling/scripts -->

### Modified Capabilities
<!-- Ninguna -->

## Impact

- **Scripts:** Se añadirá un nuevo comando de consola a Laravel (sólo para uso en desarrollo).
- **Datos de Prueba (Seeders):** El archivo `database/seeders/data/teachers.csv` (y similares si es necesario) se sobreescribirá con datos falsos.
- **Base de Datos:** Se purgará completamente en desarrollo local y se restaurará desde los seeders limpios.
