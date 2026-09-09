# Arquitectura de Recursos y Widgets de Filament v3 en SIGEDOR

SIGEDOR utiliza **Filament v3** sobre **Livewire 3** para su panel administrativo (`/admin`). La interfaz implementa control de acceso multi-inquilino (*multi-tenant* por sede), formularios inteligentes con selectores dependientes reactivos y un expediente docente unificado 360°.

---

## 1. TeacherResource (`app/Filament/Resources/TeacherResource.php`)

Recurso principal del expediente docente.

### 1.1 Formulario Wizard en 3 Pasos
Para agilizar el registro y evitar formularios extensos, la creación y edición se organiza en un asistente por pasos:
1. **Paso 1: Información del Docente**:
   - Cédula de Identidad (`cdi`), Nombres (`name`), Apellidos (`surName`), Género (`genre`), Teléfono (`phone`), Correo institucional (`email`) y Fecha de nacimiento (`birthDate`).
   - Selección de Cuenta de Usuario (`user_id`): Al seleccionar un usuario, autocompleta reactivamente el nombre, apellido, correo, sede y área.
2. **Paso 2: Adscripción Institucional**:
   - Selectores jerárquicos dependientes en cascada: `sede_id` -> `area_id` -> `programa_id`.
   - Si el usuario en sesión es `area_manager`, el selector de sede queda fijado a su propia sede asignada.
3. **Paso 3: Asignación Cátedra y Escalafón**:
   - Fecha de último concurso de ascenso (`datePromotion`) y asignatura (`asignaturePromotion`).
   - Claves de vinculación inmediata con escalafón (`category_id`), dedicación (`dedication_id`) y cátedra (`site_id`).

### 1.2 Tabla Administrativa
- Columnas con formato Badge para categoría y dedicación.
- Badges de color para género y sede territorial.
- **Acción Rápida "Emitir Constancia"**: Acción directa por fila que registra un reporte oficial y descarga de inmediato la Constancia de Trabajo en formato PDF oficial UNERG.
- **Filtros**: Por Sede, Área y Dedicación.

### 1.3 Expediente Integral 360° (RelationManagers)
El recurso dispone de 4 gestores de relaciones en pestañas integradas:
1. **`PermissionsRelationManager`**: Historial de solicitudes de permisos, tipo de duración, período de vigencia y estado (`pending`, `approved`, `rejected`).
2. **`CategoryRelationManager`**: Registro cronológico de ascensos docentes, títulos de pregrado/posgrado y categoría vigente calculada.
3. **`DedicationRelationManager`**: Modalidad contractual (TCV, MT, TC, EX), horas lectivas semanales y responsabilidades directivas.
4. **`ReportsRelationManager`**: Historial de constancias y memorandos emitidos para el docente con botón de descarga en PDF.

---

## 2. ReportResource (`app/Filament/Resources/ReportResource.php`)

Gestión y emisión de documentos universitarios oficiales.

### Características Principales
- **Aislamiento Multi-Inquilino**:
  - `admin`: Visualiza y gestiona todos los reportes de todas las sedes.
  - `area_manager`: Solo visualiza los reportes pertenecientes a docentes de su misma sede.
  - `teacher`: Solo consulta sus propios reportes (con permisos de solo lectura, sin opción a modificar o eliminar).
- **Formulario Inteligente**: Precarga y bloquea la cédula del docente para roles docentes.
- **Acciones en Tabla**:
  - Descarga directa de PDF oficial UNERG (`downloadPdf`).
  - Previsualización en modal lateral (`slideOver`) con visor PDF embebido.
- **Badges Semánticos**:
  - Estado: `emitido` (success), `borrador` (warning), `archivado` (gray).
  - Tipología: Constancia de Trabajo, Memorando Administrativo, Informe de Escalafón.

---

## 3. UserResource (`app/Filament/Resources/UserResource.php`)

Administración de cuentas de acceso al sistema.

### Seguridad y Control de Elevación
- **Blindaje de Columnas Interactivas**: Los conmutadores `ToggleColumn::make('is_approved')` y `ToggleColumn::make('is_active')` están estrictamente deshabilitados para cualquier rol que no sea `admin`.
- **Asignación de Roles Spatie**: Solo administradores pueden conceder roles de sistema (`admin`, `area_manager`, `teacher`).
- **Aislamiento Territorial**: Jefes de área solo pueden visualizar y editar usuarios que compartan su misma sede y área.

---

## 4. PermissionTeacherResource (`app/Filament/Resources/PermissionTeacherResource.php`)

Trámite de licencias, permisos médicos, años sabáticos y comisiones de servicio.

### Características Principales
- **Identificador Obligatorio (`name`)**: Menú contextual con opciones reglamentarias predefinidas.
- **Cálculo Reactivo de Fechas**: Al seleccionar la fecha de inicio (`start_date`) y la modalidad (`duration_type`), calcula reactivamente la fecha final (`end_date`).
- **Segregación de Funciones**: Los docentes solo pueden solicitar permisos en estado `pending`. La aprobación y el cambio de estado a `approved` o `rejected` está reservada para administradores y jefes de área.
- **Acción Rápida "Emitir Memo"**: Permite generar automáticamente el memorando oficial en PDF para cualquier permiso que haya sido aprobado.

---

## 5. DedicationResource y CategoryResource

- **`DedicationResource`**: Valida la carga horaria semanal estricta conforme al reglamento UNERG:
  - Tiempo Convencional: 1 a 17h.
  - Medio Tiempo: 18h.
  - Tiempo Completo: 30 a 35h.
  - Exclusiva: 36 a 40h.
- **`CategoryResource`**: Calcula retroactivamente fechas de escalafón previas ante ingresos con títulos de maestría o doctorado (ascenso directo).

---

## 6. Widgets del Dashboard Activo (`app/Filament/Widgets/`)

El escritorio principal de SIGEDOR (`/admin`) integra 5 widgets dinámicos e interactivos:

1. **`StatsOverview`**: Tarjetas de métricas globales (Total de Docentes, Cuentas Activas, Solicitudes Pendientes y Reportes Emitidos).
2. **`TeacherDistributionChart`**: Gráfico de distribución de docentes por categoría académica.
3. **`SedeStatsChart`**: Gráfico de distribución territorial de docentes por sede universitaria.
4. **`TasksOverview`**: Indicadores de carga horaria y tareas de revisión académica.
5. **`LatestReportsWidget`**: Listado de los últimos reportes generados con botón de descarga rápida en PDF y respeto estricto de las políticas de acceso del usuario autenticado.
