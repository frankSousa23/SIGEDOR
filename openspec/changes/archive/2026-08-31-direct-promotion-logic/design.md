## Context

El modelo `Category` actualmente gestiona el escalafón docente. Altera automáticamente el campo `current_category` calculando la fecha más alta registrada. Hasta ahora, el recurso de Filament usaba un booleano `disable_assistant_rule` para detener el flujo hacia la categoría *Asistente*, pero no cubría promociones directas a *Agregado* ni registraba explícitamente las fechas necesarias al crear la categoría. (Ver `proposal.md`).

## Goals / Non-Goals

**Goals:**
- Implementar un mecanismo en la capa de la UI (Filament Forms) para elegir la regla de ascenso directo al momento de registrar un nuevo docente o editar su categoría.
- Usar los "hooks" de Filament (`mutateFormDataBeforeCreate` y `mutateFormDataBeforeSave`) para poblar automáticamente los campos de fecha (`instructor`, `asistente`, `agregado`) según el mérito académico (Especialización/Maestría o Doctorado).
- Eliminar de la UI el viejo Toggle de `disable_assistant_rule`.

**Non-Goals:**
- No alteraremos el método base `getCurrentCategoryAttribute()` del modelo, puesto que ya funciona bien determinando la categoría a partir de qué fechas están presentes.

## Decisions

- **UI en Filament:** Se usará un campo `Select::make('direct_promotion_rule')` estático (no guardado en la base de datos de manera persistente directamente como ese nombre) utilizando `->dehydrated(false)` si no lo guardamos, o interceptándolo en los mutadores y retirándolo antes del save.
  - *Alternativa:* Guardar un campo `direct_promotion_type` en DB. Se decide **no** añadir obligatoriamente este campo a DB para reducir la complejidad, ya que las fechas pobladas en sí mismas representan el resultado de la promoción.
  
- **Mutación de Fechas:** Las fechas se asignarán usando `now()` (fecha actual) si el administrador no especificó fechas concretas.
  - *Razón:* Simplifica la carga del administrador de RRHH y cumple con el requerimiento de que el ascenso sea inmediato.

- **Migración (Opcional/Limpieza):** Crearemos una migración para hacer un `dropColumn('disable_assistant_rule')` del modelo `Category`, ya que no se usará más y limpia el esquema.

## Risks / Trade-offs

- **Riesgo**: Conflictos si ya existen registros en BD donde `disable_assistant_rule` esté en uso.
  - *Mitigación*: La migración eliminará la columna, por lo que es un Breaking Change menor para la BD de desarrollo actual, pero correcto arquitectónicamente. Si hay miedo de perder data, se podría dejar la columna y solo quitarla del Form, pero es más limpio eliminarla. Optaremos por eliminarla.
