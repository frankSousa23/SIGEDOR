# Informe Técnico: Análisis de Fallos de Integración en Ramas Experimentales

**Proyecto:** SIGEDOR (Sistema de Gestión Docente y Reportes)
**Fecha:** Agosto 2026

## Resumen Ejecutivo
Durante el desarrollo del proyecto se crearon múltiples ramas experimentales (como `Correcciones6`, `Correcciones7`, `Correcciones8`, y `nueva-rama-de-pruebas`) con el objetivo de implementar roles avanzados, políticas de seguridad y la estructuración de Sedes/Áreas como relaciones de base de datos. Sin embargo, al intentar integrar estas ramas a la rama principal (`main`), el sistema se volvía inestable. 

Este informe detalla las causas técnicas fundamentales de estos fallos y cómo fueron resueltos en la integración final.

---

## 1. Choque Estructural: Migración de Arrays a Tablas Relacionales
### La Intención Original
En las versiones tempranas del sistema, el modelo `Site` utilizaba arreglos estáticos (constantes `SITES` y `AREAS`) para definir las opciones disponibles. En la rama `nueva-rama-de-pruebas`, se intentó migrar esto hacia tablas relacionales creando los modelos `SiteOption` y `AreaOption`.

### Por qué falló la integración
* **Redundancia e Incompatibilidad:** El intento de introducir `SiteOption` colisionó frontalmente con el hecho de que, en paralelo, la rama principal ya había evolucionado para utilizar los modelos `Sede` y `Area`. 
* **Falta de Sincronización de Migraciones:** Al hacer el *merge*, el sistema intentaba buscar la llave foránea `site_option_id`, pero gran parte del código heredado (vistas, controladores y form builders de Filament) seguía esperando `sede_id`. Esto resultó en errores fatales de SQL (`Unknown column 'site_option_id'`) y Null Pointers.

### Solución Aplicada
Se descartaron los modelos redundantes (`SiteOption`, `AreaOption`) y se consolidó el uso exclusivo de `Sede` y `Area`. Se actualizaron todas las referencias relacionales (`$teacher->sede_id`) para asegurar la coherencia en todo el ORM.

---

## 2. Filtrado de Queries Inseguro en Recursos de Filament
### La Intención Original
Se intentó aplicar seguridad multi-inquilino para que un "Jefe de Área" solo pudiera ver a los docentes de su propia Sede. Esto se intentó inyectando condiciones directamente en el método `getEloquentQuery()` de recursos como `CategoryResource`.

### Por qué falló la integración
* **Excepciones de Propiedad Nula (Null Object Pattern):** El código modificado en las ramas experimentales asumía que **todos** los usuarios autenticados poseían un `site_option_id` o `sede_id`. 
* Cuando el **Administrador** (que por diseño no tiene una sede específica asignada) intentaba acceder al panel, la consulta intentaba leer `auth()->user()->site_option_id`, lo cual causaba una excepción fatal.

### Solución Aplicada
Se reescribió el método `getEloquentQuery()` añadiendo comprobaciones de nulidad estrictas y validación de roles antes de aplicar el filtro.
```php
// Solución segura implementada:
if ($user && $user->hasRole('area_manager') && $user->sede_id) {
    // Solo entonces aplicar el filtro
}
```

---

## 3. Conflictos en Políticas de Seguridad (Policies)
### La Intención Original
Se crearon Políticas de Laravel (`TeacherPolicy`, `CategoryPolicy`) para controlar permisos granulares a nivel de acción (view, update, delete).

### Por qué falló la integración
* **Desalineación de Atributos:** Dentro de `TeacherPolicy.php`, el código intentaba comparar `$teacher->site_id === $user->site_id`. Sin embargo, el modelo `User` nunca implementó el atributo `site_id`, sino `sede_id`. Esto causaba que todos los usuarios fueran bloqueados silenciosamente por la política (retornando siempre `false`).

### Solución Aplicada
Se corrigieron las propiedades evaluadas dentro de las Políticas, alineándolas con el esquema real de la base de datos (`$teacher->sede_id === $user->sede_id`).

---

## 4. Renderización Dinámica en el Dashboard de Filament
### La Intención Original
En el commit `Rev0.3/Panels+Errors`, se intentó modificar la clase `Dashboard.php` para que el método `getHeaderWidgets()` retornara arrays diferentes de widgets (`AreaStats` vs `StatsOverview`) dependiendo del rol del usuario de forma imperativa.

### Por qué falló la integración
* **Violación de la Arquitectura de Filament v3:** Filament requiere que todos los Widgets estén registrados globalmente en el PanelProvider. Inyectar clases dinámicamente fuera del ciclo de vida del Provider produce errores de registro.

### Solución Aplicada
Se devolvió el `Dashboard.php` a su estado base y se trasladó la lógica de visibilidad directamente al método `public static function canView(): bool` dentro de cada clase Widget, cumpliendo con los estándares de Filament.

---

## Conclusión
Los fallos experimentados no fueron producto de una mala lógica de negocio, sino de **desfases en la estructura de la base de datos** (conflictos de *naming* como `site_id` vs `sede_id`) y de forzar comportamientos en Filament ignorando su ciclo de vida estándar. 

Tras esta depuración, las intenciones originales (roles, multi-inquilino, validación estricta de correo `@sigedor.com`) se han integrado con éxito y el código base es altamente estable.
