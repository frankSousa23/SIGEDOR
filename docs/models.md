# Modelos y Relaciones Eloquent en SIGEDOR

El sistema SIGEDOR implementa el mapeo objeto-relacional mediante **Laravel Eloquent ORM**. A continuación se detalla la arquitectura de modelos, atributos asignables (`$fillable`), conversión de tipos (`$casts`), eventos de ciclo de vida (`booted`) y relaciones activas.

---

## 1. Teacher (`app/Models/Teacher.php`)

Modelo central del expediente docente universitario. Centraliza la filiación personal, titularidad de cuenta, adscripción institucional y vinculaciones directas a su escalafón, dedicación y asignación de cátedra.

### Atributos Principales (`$fillable`)
- `cdi`: Cédula de Identidad única del docente (ej. `V-12345678`).
- `name`: Nombres del profesor.
- `surName`: Apellidos del profesor.
- `genre`: Género (`F`, `M`).
- `phone`: Teléfono de contacto institucional/personal.
- `email`: Correo electrónico único bajo dominio institucional (`@sigedor.com`).
- `birthDate`: Fecha de nacimiento (`date`).
- `datePromotion`: Fecha del último ascenso obtenido (`date`).
- `asignaturePromotion`: Asignatura o cátedra del concurso de ascenso.
- `user_id`: Clave foránea hacia `users.id` (cuenta de acceso al panel).
- `sede_id`: Clave foránea hacia `sedes.id` (recinto territorial universitario).
- `area_id`: Clave foránea hacia `areas.id` (área de conocimiento académico).
- `programa_id`: Clave foránea hacia `programas.id` (carrera / programa de grado).
- `site_id`: Clave foránea hacia `sites.id` (asignación de carga horaria y secciones).
- `category_id`: Clave foránea hacia `categories.id` (escalafón docente vigente).
- `dedication_id`: Clave foránea hacia `dedications.id` (modalidad horaria contratada).

### Relaciones Eloquent
```php
// Pertenencias institucionales
public function user(): BelongsTo           // Usuario en tabla users
public function sede(): BelongsTo           // Recinto territorial (sedes)
public function area(): BelongsTo           // Área académica (areas)
public function programa(): BelongsTo       // Programa de estudio (programas)

// Componentes académicos (claves foráneas directas sincronizadas)
public function category(): BelongsTo       // Categoría/escalafón vigente
public function dedication(): BelongsTo     // Carga horaria vigente
public function site(): BelongsTo           // Cátedra y secciones asignadas

// Históricos y solicitudes
public function permissions(): HasMany      // Permisos tramitados (PermissionTeacher)
public function reports(): HasMany          // Reportes y memorandos emitidos (Report)
```

### Características Especiales
- Implementa `SoftDeletes` para preservar la trazabilidad histórica de expedientes.
- Sincronización bidireccional automática: Las claves `category_id`, `dedication_id` y `site_id` son actualizadas de manera reactiva por los eventos de ciclo de vida (`booted`) de sus modelos respectivos.

---

## 2. Category (`app/Models/Category.php`)

Registra el escalafón universitario del profesorado, títulos académicos obtenidos y las fechas de ingreso y ascenso a cada categoría.

### Atributos Principales (`$fillable`)
- `teacher_cdi`: Cédula del docente asociado (clave de enlace histórica).
- `preTitle`: Título de pregrado obtenido.
- `lastTitle`: Último título de postgrado (Especialización, Maestría, Doctorado).
- `current_category`: Categoría activa calculada (`Instructor`, `Asistente`, `Agregado`, `Asociado`, `Titular`).
- `instructor`, `asistente`, `agregado`, `asociado`, `titular`: Fechas de efectividad de cada escalafón (`date`).
- `info`: Observaciones académicas o de resolución de consejo.

### Reglas de Ascenso Directo y Hooks de Ciclo de Vida
- **Ascenso Directo**: Si el docente posee título de Maestría o Especialidad, asciende directamente a *Asistente*. Si acredita título de Doctorado, asciende a *Agregado*.
- **Hook `saved` / `deleted`**: Al guardar o eliminar una categoría, actualiza automáticamente la columna `category_id` en el registro correspondiente de `teachers`.

---

## 3. Dedication (`app/Models/Dedication.php`)

Gestiona la modalidad de contratación y carga horaria del docente conforme a la normativa de la UNERG.

### Atributos Principales (`$fillable`)
- `teacher_cdi`: Cédula del docente asociado.
- `name`: Nombre descriptivo de la dedicación (`Tiempo Convencional`, `Medio Tiempo`, `Tiempo Completo`, `Exclusiva`).
- `type`: Código normativo (`TCV`, `MT`, `TC`, `EX`).
- `hours`: Horas semanales reglamentarias asignadas.
- `director`: Indicador de cargo directivo o de coordinación.
- `studentNumber`: Número de alumnos atendidos.
- `studentHours`: Horas de atención y asesoría académica.
- `info`: Información complementaria de la asignación.

### Rangos Normativos de Horas (UNERG)
- **Tiempo Convencional (TCV)**: 1 a 17 horas semanales.
- **Medio Tiempo (MT)**: 18 horas semanales.
- **Tiempo Completo (TC)**: 30 a 35 horas semanales.
- **Dedicación Exclusiva (EX)**: 36 a 40 horas semanales.

### Hooks de Ciclo de Vida
- **Hook `saving`**: Mapea automáticamente el código corto `type` a partir del `name`.
- **Hook `saved` / `deleted`**: Sincroniza automáticamente la clave foránea `dedication_id` en el expediente de `teachers`.

---

## 4. Site (`app/Models/Site.php`)

> **Distinción de Dominio**: En SIGEDOR, `Site` modela la **Cátedra y Asignación Docente** (distribución de horas lectivas, unidades de crédito y secciones), mientras que `Sede` representa el **Recinto Territorial Universitario**.

### Atributos Principales (`$fillable`)
- `teacher_cdi`: Cédula del profesor asignado.
- `sede_id`: Sede donde se dicta la cátedra.
- `area_id`: Área de conocimiento de la materia.
- `programa_id`: Carrera a la que pertenece la asignatura.
- `uc`: Unidades de crédito de la materia.
- `weekHours`: Horas de clase presencial por semana.
- `sections`: Número de secciones impartidas.
- `is_active`, `is_available`: Banderas de disponibilidad operativa.

### Hooks de Ciclo de Vida
- **Hook `saved` / `deleted`**: Sincroniza de forma atómica `site_id`, `sede_id`, `area_id` y `programa_id` en la tabla `teachers`.

---

## 5. PermissionTeacher (`app/Models/PermissionTeacher.php`)

Administra las solicitudes de permisos, licencias remuneradas o no remuneradas, años sabáticos y comisiones de servicio.

### Atributos Principales (`$fillable`)
- `name`: Nombre identificador descriptivo del trámite (médico, sabático, comisión, etc.).
- `teacher_cdi`: Cédula del docente solicitante.
- `type`: Tipo formal de permiso.
- `duration_type`: Modalidad temporal (`semestral`, `anual`, `dias`).
- `start_date`: Fecha de inicio del permiso (`date`).
- `end_date`: Fecha de culminación calculada (`date`).
- `is_paid`: Booleano que indica si es remunerado o no remunerado.
- `status`: Estado del trámite (`pending`, `approved`, `rejected`).
- `description`: Justificación académica o médica del permiso.

### Hooks y Métodos Auxiliares
- **`calculateEndDate()`**: Calcula automáticamente la fecha de término sumando 6 meses (semestral) o 1 año (anual) a partir de la fecha de inicio.
- **Hook `booted`**: Garantiza que si `end_date` no ha sido suministrado, se calcule automáticamente antes de persistir, impidiendo errores de restricción NOT NULL en base de datos.

---

## 6. Report (`app/Models/Report.php`)

Registra los documentos institucionales emitidos por el sistema: Constancias de Trabajo, Memorandos Administrativos e Informes de Escalafón.

### Atributos Principales (`$fillable`)
- `verification_code`: Código único de autenticidad documental institucional (`UNERG-REP-YYYY-XXXX`).
- `teacher_cdi`: Cédula del docente al que pertenece el reporte.
- `memoNumber`: Número correlativo institucional del documento.
- `typeReport`: Tipología (`Constancia de Trabajo`, `Memorando`, `Escalafón`).
- `status`: Estado del reporte (`draft`, `issued`, `archived`).
- `created_by`: ID del usuario emisor del documento.
- `sede_id`, `area_id`, `category_id`, `dedication_id`: Claves académicas contextuales en el momento de la emisión.
- `report`: Nombre del archivo o ruta del PDF generado.

### Hooks de Ciclo de Vida
- **Hook `creating`**: Genera automáticamente el código criptográfico de autenticidad `UNERG-REP-YYYY-XXXX` y asocia el usuario en sesión (`created_by = auth()->id()`).

---

## 7. User (`app/Models/User.php`)

Gestiona la identidad de acceso al panel administrativo de Filament.

### Atributos Principales (`$fillable`)
- `name`: Nombre completo del usuario.
- `email`: Correo electrónico bajo dominio `@sigedor.com`.
- `password`: Contraseña hasheada (Bcrypt / Argon2id).
- `is_active`: Bandera de habilitación de acceso al sistema.
- `is_approved`: Bandera de aprobación administrativa de la cuenta.
- `sede_id`, `area_id`: Filiación territorial asignada (para rol `area_manager`).

### Métodos Clave de Rol
```php
public function isAdmin(): bool         // Comprueba rol 'admin'
public function isAreaManager(): bool   // Comprueba rol 'area_manager'
public function isTeacher(): bool       // Comprueba rol 'teacher'
```

---

## 8. Catálogos Territoriales e Institucionales

- **Sede (`app/Models/Sede.php`)**: Recintos universitarios UNERG (Sede Central San Juan de los Morros, Calabozo, Zaraza, Valle de la Pascua, etc.).
- **Area (`app/Models/Area.php`)**: Áreas de conocimiento (Ingeniería de Sistemas, Ciencias de la Salud, Ciencias Económicas, etc.).
- **Programa (`app/Models/Programa.php`)**: Carreras profesionales de pregrado y posgrado adscritas a cada área.
