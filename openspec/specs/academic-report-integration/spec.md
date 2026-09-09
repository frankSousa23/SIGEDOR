# academic-report-integration Specification

## Purpose

Interconecta el módulo de reportes y memorandos con los expedientes docentes, la gestión de permisos y el panel principal, facilitando la emisión y consulta ágil de documentos oficiales.

## Requirements

### Requirement: Historial de Reportes en Expediente Docente
El sistema DEBE incluir en la interfaz de gestión docente un gestor de relaciones (`ReportsRelationManager`) que exponga los reportes y memorandos emitidos para el profesor seleccionado con opciones de descarga en PDF.

#### Scenario: Visualización de historial de documentos del docente
- **WHEN** un administrador o jefe de área inspecciona o edita el expediente de un profesor
- **THEN** se despliega la pestaña de Reportes listando los documentos oficiales vinculados y permitiendo su descarga directa

### Requirement: Emisión Rápida de Constancia de Trabajo
El sistema DEBE proveer en la tabla de docentes una acción directa para emitir y descargar una Constancia de Trabajo oficial pre-rellenada en un solo paso.

#### Scenario: Generación inmediata de constancia
- **WHEN** un usuario autorizado ejecuta la acción "Emitir Constancia" en la fila de un docente
- **THEN** el sistema registra el reporte oficial correspondiente y devuelve la descarga del documento PDF formateado

### Requirement: Emisión de Memorando desde Permisos Aprobados
El sistema DEBE permitir generar un memorando oficial a partir de un permiso docente en estado aprobado, vinculando los datos de la solicitud con el registro de reportes.

#### Scenario: Emisión de memo desde permiso aprobado
- **WHEN** un jefe de área o administrador activa la acción de memorando sobre un permiso aprobado
- **THEN** el sistema crea el reporte oficial asociado al permiso y facilita su descarga en PDF

### Requirement: Expediente Integral 360 del Docente
La interfaz de edición y consulta de docentes DEBE incorporar gestores de relaciones dedicados para Permisos (`PermissionsRelationManager`), Categoría Académica y Dedicación Horaria, permitiendo auditar y gestionar el historial universitario completo del docente desde una única vista unificada.

#### Scenario: Consulta del expediente unificado de un profesor
- **WHEN** un administrador o jefe de área accede a la vista de edición o consulta de un docente
- **THEN** la interfaz despliega pestañas para consultar sus reportes oficiales, historial de permisos tramitados, escalafón docente y carga horaria asignada

