# category-management Specification

## Purpose
Establece las reglas de negocio para el ascenso directo de escalafón (Instructor, Asistente, Agregado) de los docentes en el sistema de acuerdo a los títulos académicos (Especialización, Maestría, Doctorado) presentados durante su alta o edición en el módulo de categorías.

## Requirements

### Requirement: Regla de Ascenso por Grado Académico
El sistema DEBE permitir al administrador asignar ascensos directos a docentes de nuevo ingreso (cuya base es Instructor) a través de una selección de la regla aplicable. Las fechas del escalafón DEBEN auto-completarse de forma concurrente, asegurando que el cálculo automático de `current_category` refleje el salto realizado.

#### Scenario: Nuevo docente sin posgrado
- **WHEN** el administrador registra la categoría del docente sin seleccionar una regla de ascenso directo
- **THEN** el sistema registra la fecha actual en el campo `instructor` y deja los campos `asistente`, `agregado`, etc., vacíos, resultando en categoría "Instructor".

#### Scenario: Nuevo docente con Especialización o Maestría
- **WHEN** el administrador selecciona la regla de ascenso para Especialización/Maestría
- **THEN** el sistema registra la fecha actual en los campos `instructor` y `asistente`, permitiendo que el docente sea clasificado inmediatamente como "Asistente".

#### Scenario: Nuevo docente con Doctorado
- **WHEN** el administrador selecciona la regla de ascenso para Doctorado
- **THEN** el sistema registra la fecha actual en los campos `instructor`, `asistente` y `agregado`, asignando al docente automáticamente la categoría de "Agregado".
