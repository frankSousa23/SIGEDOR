## Purpose

Proporciona widgets gráficos interactivos en el panel principal para la visualización ejecutiva de la distribución docente y territorial universitaria.

## ADDED Requirements

### Requirement: Gráfico de Distribución de Dedicaciones
El sistema DEBE presentar en el Dashboard un gráfico de dona con la proporción de docentes por cada tipo de dedicación horaria.

#### Scenario: Carga del panel principal
- **WHEN** un usuario con rol administrativo accede al escritorio
- **THEN** visualiza un gráfico interactivo con el conteo y porcentaje de profesores por Exclusiva, Tiempo Completo, Medio Tiempo y Convencional

### Requirement: Gráfico Territorial por Sedes
El sistema DEBE presentar un gráfico de barras comparativo con el total de personal adscrito a cada sede universitaria.

#### Scenario: Visualización de métricas de sedes
- **WHEN** el administrador consulta el panel de analítica
- **THEN** visualiza barras comparativas con el volumen de personal registrado por cada sede física

