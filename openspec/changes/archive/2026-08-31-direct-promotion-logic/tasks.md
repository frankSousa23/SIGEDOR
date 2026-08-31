## 1. Schema & Models

- [x] 1.1 Crear migración para eliminar la columna `disable_assistant_rule` de la tabla `categories`. Verificar ejecutando `php artisan migrate` satisfactoriamente.
- [x] 1.2 Actualizar el modelo `Category.php` eliminando `disable_assistant_rule` del `$fillable` y del docblock. Verificar que el código estático no arroje errores ni dependa del campo eliminado.

## 2. Formularios de Recursos (Filament)

- [x] 2.1 En `CategoryResource.php`, reemplazar `Toggle::make('disable_assistant_rule')` por un `Select::make('direct_promotion_rule')` con opciones para 'Ninguno', 'Especialización/Maestría' y 'Doctorado'. Se debe usar `dehydrated(false)` para que Filament no intente guardarlo en BD. Verificar la carga de la vista del formulario en el panel administrativo.
- [x] 2.2 Modificar `CreateCategory.php` inyectando el hook `mutateFormDataBeforeCreate`. Aquí, se captura el valor de `direct_promotion_rule` e inyecta dinámicamente `now()` a los campos de fecha (`instructor`, `asistente`, `agregado`) según el grado. Verificar registrando una nueva categoría con ascenso a Agregado desde la interfaz.
- [x] 2.3 Modificar `EditCategory.php` inyectando el hook `mutateFormDataBeforeSave` con la misma lógica. Verificar cambiando la regla de promoción en una categoría existente.

## 3. Integración y Validación

- [x] 3.1 Revisar si en alguna otra parte del código se estaba referenciando `shouldApplyAssistantRule()` o `disable_assistant_rule` (ej. en Reportes u otros controladores). Verificar con una búsqueda en el proyecto que no queden llamadas rotas.
- [x] 3.2 Compilar los recursos y hacer pruebas funcionales de extremo a extremo: Crear un docente con Doctorado, verificar que la Base de Datos registre las tres fechas al día de hoy y que la lista muestre al Docente con Badge 'Agregado'.
