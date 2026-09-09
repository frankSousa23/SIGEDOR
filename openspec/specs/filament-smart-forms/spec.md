# filament-smart-forms Specification

## Purpose

Proporciona reactividad en vivo y auto-llenado inteligente en los formularios de gestión docente para agilizar la captura de datos y prevenir inconsistencias territoriales.

## Requirements

### Requirement: Auto-llenado de Docente por Usuario
El sistema DEBE autocompletar automáticamente el nombre, apellido, correo institucional, sede y área al seleccionar una cuenta de usuario en el formulario de creación de docentes.

#### Scenario: Selección de cuenta de usuario en formulario docente
- **WHEN** el usuario administrativo selecciona un usuario en el campo Cuenta de Usuario
- **THEN** el sistema autocompleta inmediatamente los campos de nombres, apellidos, correo, sede y área sin recargar la página

### Requirement: Selectores dependientes de Sede y Área
El sistema DEBE filtrar reactivamente las opciones de área y programa académico según la sede seleccionada.

#### Scenario: Cambio de sede seleccionada
- **WHEN** el usuario cambia la sede en el formulario
- **THEN** las opciones disponibles en el selector de área se actualizan inmediatamente mostrando solo las áreas válidas para esa sede

### Requirement: Autoselección de Docente en Solicitudes de Permisos
El formulario de solicitud de permisos DEBE detectar si el usuario autenticado tiene rol docente, preseleccionando automáticamente su cédula en el campo de docente solicitante e inhabilitando la selección de otros profesores para garantizar la integridad de la identidad.

#### Scenario: Docente ingresa al formulario de permisos
- **WHEN** un usuario con rol exclusivo de docente ingresa a crear una solicitud de permiso
- **THEN** el campo de docente solicitante aparece preseleccionado con su propia cédula y bloqueado para edición

### Requirement: Sincronización Automática Bidireccional de Escalafón y Dedicación
Al registrar o actualizar una categoría, dedicación o asignación de sede mediante su `teacher_cdi`, el sistema DEBE actualizar automáticamente las claves foráneas correspondientes (`category_id`, `dedication_id`, `site_id`) en el registro del docente, asegurando que la tabla principal refleje inmediatamente los datos vigentes.

#### Scenario: Creación de escalafón para docente
- **WHEN** un administrador registra una categoría para un profesor por su cédula
- **THEN** el sistema asigna el identificador de la categoría en el expediente del docente y la tabla de profesores muestra la categoría calculada en lugar de "Sin asignar"

### Requirement: Persistencia Segura y Reactividad en Permisos Docentes
El sistema DEBE proveer un campo identificador descriptivo obligatorio (`name`) en el registro de permisos docentes y calcular reactivamente la fecha de finalización (`end_date`) a partir de la fecha de inicio y la modalidad de duración (semestral o anual).

#### Scenario: Cálculo de fecha final en solicitud semestral
- **WHEN** el usuario ingresa una fecha de inicio y selecciona modalidad semestral
- **THEN** el sistema calcula automáticamente la fecha de término a 6 meses vista sin requerir cálculo manual

### Requirement: Validación Normativa de Carga Horaria por Modalidad
El sistema DEBE validar que la cantidad de horas semanales ingresadas en una dedicación se corresponda exactamente con las horas reglamentarias de la UNERG para la modalidad seleccionada (Tiempo Convencional: 1 a 17h, Medio Tiempo: 18h, Tiempo Completo: 30h, Exclusiva: 35 a 36h).

#### Scenario: Validación de horas en Dedicación Exclusiva
- **WHEN** el usuario selecciona modalidad Exclusiva e ingresa 36 horas
- **THEN** el sistema valida la carga horaria como reglamentaria permitiendo el guardado del registro


