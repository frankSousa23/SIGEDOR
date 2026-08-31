# Guía de Importación de Datos vía CSV y Replicabilidad Universitaria

Esta guía técnica explica la arquitectura del pipeline de ingesta de datos mediante archivos CSV en **SIGEDOR**, permitiendo a estudiantes, tesistas y universidades cargar y personalizar fácilmente su propio claustro docente, facultades y asignaciones académicas.

---

## 📂 Estructura de los Archivos CSV (`database/seeders/data/`)

El sistema organiza la información relacional en cinco archivos CSV delimitados por punto y coma (`;`):

```
database/seeders/data/
├── users.csv          # Cuentas de usuario, credenciales, roles y estados de aprobación
├── teachers.csv       # Expedientes docentes, cédula (CDI), datos personales y cátedras
├── categories.csv     # Grados académicos, pregrado, posgrado y fechas de escalafón
├── dedicaciones.csv   # Dedicación horaria semanal, cargos directivos y horas de tutoría
└── sites.csv          # Asignación de sede, área, programa, UC, secciones y horas semanales
```

---

## 📋 Especificación de Columnas y Formato

### 1. `users.csv` (Usuarios y Roles del Sistema)
Define las credenciales de acceso y permisos. Permite asignar múltiples roles separándolos por coma.

| Columna | Tipo | Descripción | Ejemplo |
| :--- | :--- | :--- | :--- |
| `id` | Entero | Identificador único | `1` |
| `name` | Texto | Nombre y título del usuario | `Dra. Carmen Silva` |
| `email` | Texto | Correo electrónico único | `carmen.silva@universidad.edu` |
| `password` | Texto | Contraseña en texto plano (se hashea automáticamente con Bcrypt) | `password123` |
| `sede_nombre` | Texto | Nombre exacto de la Sede asignada | `Sede Central/San Juan de los Morros` |
| `area_nombre` | Texto | Nombre exacto del Área Académica | `Ciencias de la salud` |
| `rol_name` | Texto | Roles asignados (separados por coma si tiene múltiples) | `area_manager,teacher` o `admin` |
| `is_active` | Booleano (1/0) | Si el usuario está activo para iniciar sesión | `1` |
| `is_approved` | Booleano (1/0) | Si la cuenta fue aprobada por el Administrador | `1` (o `0` para pendientes) |

---

### 2. `teachers.csv` (Expediente del Docente)
Registra la información demográfica, de contacto y fecha de ingreso del docente.

| Columna | Tipo | Descripción | Ejemplo |
| :--- | :--- | :--- | :--- |
| `id` | Entero | ID del registro | `1` |
| `name` | Texto | Nombres de pila | `Carlos Eduardo` |
| `surName` | Texto | Apellidos | `Mendoza Rojas` |
| `cdi` | Texto/Número | Cédula de Identidad (clave única institucional) | `10101001` |
| `genre` | Carácter | Género (`M` o `F`) | `M` |
| `phone` | Texto | Número de teléfono de contacto | `+58 412 1000001` |
| `email` | Texto | Correo electrónico institucional | `docente@universidad.edu` |
| `birthDate` | Fecha (`YYYY-MM-DD`) | Fecha de nacimiento | `1982-04-12` |
| `datePromotion` | Fecha (`YYYY-MM-DD`) | Fecha de ingreso o primer nombramiento | `2010-06-15` |
| `asignaturePromotion` | Texto | Asignatura de concurso o ingreso | `Estructuras de Datos` |
| `user_email` | Texto | Correo de la cuenta de usuario vinculada en `users.csv` | `docente@universidad.edu` |
| `sede_nombre` | Texto | Sede institucional | `Sede Central/San Juan de los Morros` |
| `area_nombre` | Texto | Área de adscripción | `Ingeniería de sistemas` |
| `programa_nombre` | Texto | Programa académico / Carrera | `Ingeniería en Informática` |

---

### 3. `categories.csv` (Escalafón y Títulos Universitarios)
Control cronológico de los ascensos en el escalafón universitario.

| Columna | Tipo | Descripción | Ejemplo |
| :--- | :--- | :--- | :--- |
| `id` | Entero | ID del registro | `1` |
| `teacher_cdi` | Texto | Cédula del docente (vinculación relacional) | `10101001` |
| `preTitle` | Texto | Título universitario de pregrado | `Ingeniero en Informática` |
| `lastTitle` | Texto | Último grado académico o posgrado obtenido | `Magíster en Computación` |
| `current_category` | Texto | Categoría docente actual | `Instructor`, `Asistente`, `Agregado`, `Asociado`, `Titular` |
| `instructor` | Fecha | Fecha de ascenso a Instructor | `2010-06-15` |
| `asistente` | Fecha | Fecha de ascenso a Asistente | `2013-06-15` |
| `agregado` | Fecha | Fecha de ascenso a Agregado | `2017-09-20` |
| `asociado` | Fecha | Fecha de ascenso a Asociado | `null` |
| `titular` | Fecha | Fecha de ascenso a Titular | `null` |
| `disable_assistant_rule` | Booleano (1/0) | Desactiva promoción automática por posgrado | `0` |
| `info` | Texto | Observaciones o distinciones académicas | `Acreditación PEII Nivel A` |

---

### 4. `dedications.csv` (Carga Horaria Semanal y Cargos)
| Columna | Tipo | Descripción | Ejemplo |
| :--- | :--- | :--- | :--- |
| `id` | Entero | ID del registro | `1` |
| `teacher_cdi` | Texto | Cédula del docente | `10101001` |
| `name` | Texto | Tipo de Dedicación (`Tiempo Convencional`, `Medio Tiempo`, `Tiempo Completo`, `Exclusiva`) | `Tiempo Completo` |
| `hours` | Entero | Horas semanales reglamentarias | `30` |
| `director` | Texto (Opcional) | Cargo administrativo (`Coordinador`, `Jefe de Departamento`, `Decano`, `Director`, `Sub-Director`) | `Coordinador` |
| `studentNumber` | Entero | Promedio de estudiantes asignados | `45` |
| `studentHours` | Entero | Horas dedicadas a asesorías o tutorías | `6` |
| `info` | Texto | Descripción de líneas de investigación o responsabilidades | `Línea de Software` |

---

### 5. `sites.csv` (Distribución Curricular y Cátedras)
| Columna | Tipo | Descripción | Ejemplo |
| :--- | :--- | :--- | :--- |
| `id` | Entero | ID del registro | `1` |
| `teacher_cdi` | Texto | Cédula del docente | `10101001` |
| `sede_nombre` | Texto | Sede física | `Sede Central/San Juan de los Morros` |
| `area_nombre` | Texto | Área de adscripción | `Ingeniería de sistemas` |
| `programa_nombre` | Texto | Programa académico | `Ingeniería en Informática` |
| `uc` | Entero | Unidades de Crédito de la materia | `4` |
| `weekHours` | Entero | Horas semanales de cátedra | `6` |
| `sections` | Entero | Número de secciones asignadas | `2` |
| `info` | Texto | Nombre de la Cátedra o Asignatura | `Cátedra de Algoritmos` |
| `is_active` | Booleano | Si la cátedra está activa | `1` |

---

## 🛡️ Seguridad y Pruebas: Ofuscamiento de CSV (Anonimización)

Dado que la información del claustro docente es sensible y privada, el proyecto incluye un comando diseñado específicamente para **anonimizar los datos del archivo `teachers.csv`** antes de realizar pruebas públicas o demostraciones, sin romper la integridad del Seeder.

Si necesitas probar el sistema con volumen masivo pero resguardando los nombres, números telefónicos y cédulas originales, ejecuta el siguiente comando **antes** de hacer la ingesta:

```bash
php artisan app:anonymize-teachers-csv
```

Este script leerá el archivo `teachers.csv`, reemplazará toda la información personal por datos falsos (utilizando `FakerPHP`), pero mantendrá intactas las relaciones estructurales (fechas de promoción, áreas, programas, sedes), de forma que el proceso `migrate:fresh --seed` se completará con éxito, pero con datos seguros.

---

## ⚡ Ejecución de la Ingesta de Datos

Para vaciar la base de datos y cargar los datos desde los archivos CSV actualizados:

```bash
# Con Laragon o PHP en el PATH:
php artisan migrate:fresh --seed

# O con la ruta directa del binario:
"C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe" artisan migrate:fresh --seed
```

---

## 🎓 Replicación para Proyectos Universitarios y Tesis

Si un estudiante o equipo de investigación desea adaptar este sistema para otra universidad:

1. **Configurar el Catálogo Institucional:**
   - Modifica `database/seeders/SedeSeeder.php`, `AreaSeeder.php` y `ProgramaSeeder.php` con los nombres de los campus, facultades y carreras de tu universidad.
2. **Preparar los CSVs de Docentes:**
   - Exporta la plantilla de los 5 archivos CSV y rellena las filas con los datos de tu institución.
3. **Roles y Flujo de Aprobación:**
   - Asigna los roles `admin`, `area_manager` y `teacher`.
   - Si creas usuarios con `is_approved = 0`, el Administrador del sistema podrá aprobarlos interactivamente desde la tabla de Usuarios en `/admin/users`.
