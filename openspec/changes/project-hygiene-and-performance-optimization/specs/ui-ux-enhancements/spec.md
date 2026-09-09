## MODIFIED Requirements

### Requirement: Mejora de Navegación y UI
El sistema DEBE presentar un panel de control con tipografías legibles, colores unificados según la identidad gráfica del proyecto, navegación SPA interactiva y consultas optimizadas mediante carga ansiosa (eager loading) que eliminen la latencia en tablas administrativas.

#### Scenario: Contraste y legibilidad
- **WHEN** cualquier usuario autenticado navega por el panel de Filament
- **THEN** los colores primarios y secundarios presentan un alto contraste y consistencia tipográfica

#### Scenario: Navegación fluida en modo SPA
- **WHEN** un usuario autenticado navega entre los distintos módulos y recursos del panel de administración
- **THEN** las transiciones ocurren en modo SPA sin recarga destructiva del documento HTML ni parpadeo visual

#### Scenario: Rendimiento en renderizado de tablas administrativas
- **WHEN** un usuario lista registros en recursos que involucran relaciones foráneas (docentes, usuarios, reportes, permisos, asignaciones)
- **THEN** el sistema carga anticipadamente las relaciones asociadas evitando consultas individuales recurrentes (problema N+1)
