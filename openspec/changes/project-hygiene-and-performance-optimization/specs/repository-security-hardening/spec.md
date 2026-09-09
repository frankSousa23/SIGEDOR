## MODIFIED Requirements

### Requirement: Exclusión Preventiva en .gitignore
El archivo `.gitignore` DEBE prevenir proactivamente la inclusión de cualquier archivo de variables de entorno locales, volcados o bases de datos locales, artefactos de pruebas o coberturas y temporales de compilación en el control de versiones, asegurando que los archivos de timestamp previamente rastreados sean desvinculados del índice.

#### Scenario: Creación de archivo de entorno local alternativo
- **WHEN** un desarrollador crea un archivo `.env.local` o genera un volcado `backup.sql` en la raíz del proyecto
- **THEN** Git ignora automáticamente dichos archivos impidiendo que sean agregados al área de staging (`git status`)

#### Scenario: Generación de artefactos de testing y temporales de compilación
- **WHEN** se ejecutan pruebas automatizadas que generan `.pest/` o coberturas, o se compilan assets con Vite
- **THEN** Git ignora automáticamente los directorios de caché, logs locales, bases SQLite de prueba y archivos de timestamp (`vite.config.js.timestamp-*`)
