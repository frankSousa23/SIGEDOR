## Why

El proceso actual de asignación de categorías para nuevos docentes ordinarios se basa en un simple interruptor (`disable_assistant_rule`) que solo contempla el ascenso de Instructor a Asistente si se tiene una especialización o maestría. Sin embargo, los estatutos universitarios exigen que si el docente de nuevo ingreso posee un título de Doctorado, este ascienda directamente a la categoría de Agregado. Se requiere una solución robusta en el panel de administración que asigne automáticamente los escalafones y fechas históricas (Instructor -> Asistente -> Agregado) de acuerdo al grado académico presentado al ingresar.

## What Changes

- Reemplazo del interruptor booleano `disable_assistant_rule` por un campo de selección (`direct_promotion_rule`) en el formulario de creación/edición de `CategoryResource`.
- Lógica de mutación de datos (`mutateFormDataBeforeCreate` y `mutateFormDataBeforeSave`) que asigne automáticamente la fecha actual a las columnas `instructor`, `asistente` y (si aplica) `agregado` cuando se elija un ascenso directo por posgrado o doctorado.
- **BREAKING**: Se debe eliminar la columna `disable_assistant_rule` de la base de datos (o dejar de usarla formalmente) y reemplazarla por la nueva lógica de negocio en el registro de fechas.
- Actualización de las notificaciones interactivas para que confirmen el ascenso directo logrado.

## Capabilities

### New Capabilities
- `category-management`: Lógica y reglas de negocio para el ascenso directo de escalafón (Instructor, Asistente, Agregado, Asociado, Titular) basado en los títulos académicos (Especialización, Maestría, Doctorado) durante el alta del docente en el sistema.

### Modified Capabilities
- Ninguna.

## Impact

- **Modelos**: `Category.php` (lógica y atributos reajustados).
- **Recursos Filament**: `CategoryResource.php`, `CreateCategory.php` y `EditCategory.php`.
- **Base de Datos**: Se requiere una migración para eliminar el campo obsoleto `disable_assistant_rule` y añadir (opcionalmente) un campo que guarde la justificación del ascenso si se considera necesario, aunque el registro de fechas en paralelo actuará como prueba del ascenso inmediato.
