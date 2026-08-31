## Context

El sistema SIGEDOR cuenta con archivos CSV en la carpeta `database/seeders/data/` (como `teachers.csv`) que se utilizan para precargar la base de datos a través de Seeders. Estos archivos contienen información real y sensible del personal universitario. Para futuras pruebas (como probar lógicas de asenso o interfaces de login), necesitamos que esta data esté completamente anonimizada pero sin perder la integridad referencial (las mismas áreas, los mismos programas, sedes, y fechas lógicas). (Ver proposal.md)

## Goals / Non-Goals

**Goals:**
- Construir un script/comando `AnonymizeTeachersCsv` en Laravel.
- Utilizar `FakerPHP` para sobrescribir o generar un nuevo archivo CSV basado en la misma estructura.
- Reconstruir el entorno local con una base de datos limpia utilizando `migrate:fresh --seed`.

**Non-Goals:**
- No alteraremos la estructura en la base de datos (columnas).
- No borraremos ni anonimizaremos datos operacionales del sistema como nombres de Programas y Sedes.

## Decisions

- **Herramienta de Anonimización**: Se usará un comando nativo de Laravel `php artisan make:command AnonymizeTeachersCsv` que utilice el helper `fake()` de Laravel/Faker.
- **Formato del Archivo**: Se leerá el CSV original línea por línea (con punto y coma `;` como separador), se modificará el array de datos (índices correspondientes a nombre, cédula, correo, teléfono) y se escribirá de vuelta en el mismo archivo (o uno temporal que luego reemplace al original) para asegurar de que el Seeder lo recoja.
- **Campos a Fake-ear**: 
  - `name`: `fake()->firstName()`
  - `surName`: `fake()->lastName()`
  - `cdi`: `fake()->unique()->numerify('########')`
  - `phone`: `fake()->phoneNumber()`
  - `email` y `user_email`: `fake()->unique()->safeEmail()`
  - Se mantendrán estáticos los campos de fechas, relaciones, áreas, etc.

## Risks / Trade-offs

- **Riesgo:** Perder los datos originales por accidente.
  - *Mitigación:* Se asume que estos CSVs no deben estar en su versión con información sensible en repositorios públicos. Al ser reescritos, esta versión falsa es la que debería quedar en control de versiones. (Es preferible perderlos que filtrarlos).
- **Riesgo:** Inconsistencias al hacer Fresh de la DB si fallan los seeders.
  - *Mitigación:* Se verificará que el `DatabaseSeeder.php` llame correctamente al `TeacherSeeder` y a la creación del usuario inicial.
