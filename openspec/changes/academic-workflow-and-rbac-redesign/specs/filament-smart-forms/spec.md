## ADDED Requirements

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
