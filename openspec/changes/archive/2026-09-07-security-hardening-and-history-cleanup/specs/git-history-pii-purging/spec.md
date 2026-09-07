## Purpose

Garantiza la eliminación permanente y verificable de cualquier archivo de datos o registro histórico en Git que contenga información personal identificable (PII) no anonimizada de la comunidad universitaria.

## ADDED Requirements

### Requirement: Purga de Datos Sensibles del Historial de Commits
El historial del repositorio Git NO DEBE contener ningún blob o commit histórico con registros personales reales (cédulas de identidad, nombres civiles, teléfonos o correos personales) originados en importaciones de bases de datos preliminares.

#### Scenario: Inspección de blobs históricos en Git
- **WHEN** un auditor de seguridad ejecuta una búsqueda exhaustiva en todo el árbol de commits sobre los CSVs históricos de docentes
- **THEN** el comando no encuentra ninguna versión que contenga los 872 registros de datos reales introducidos en el commit `06ad522`, conteniendo únicamente los datos limpios y canónicos anonimizados

### Requirement: Conservación de la Integridad del Código Post-Reescritura
Tras la reescritura o purga del historial de Git, el repositorio DEBE conservar intacta la totalidad del código fuente de la aplicación, migraciones, controladores, pruebas automatizadas y especificaciones OpenSpec en estado completamente funcional.

#### Scenario: Ejecución de pruebas y validación post-purga
- **WHEN** se ejecutan `vendor/bin/pest` y `openspec validate --all` en la rama principal tras reescribir el historial
- **THEN** el 100% de las pruebas automatizadas pasan en verde y todas las especificaciones se validan sin errores
