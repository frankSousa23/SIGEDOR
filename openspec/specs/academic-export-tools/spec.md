# academic-export-tools Specification

## Purpose

Permite la extracción masiva y consolidada de expedientes y reportes académicos en formatos de hoja de cálculo estructurados (CSV/Excel) para trámites de secretaría general.

## Requirements

### Requirement: Exportación Masiva a CSV de Docentes
El sistema DEBE permitir exportar el listado filtrado de docentes con todas sus columnas académicas en formato CSV estándar.

#### Scenario: Exportación desde la tabla de docentes
- **WHEN** el usuario selecciona registros o acciona exportar en la tabla de docentes
- **THEN** el sistema genera y descarga un archivo CSV con cédula, nombres, apellidos, sede, área, categoría y dedicación
