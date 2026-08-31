# Historia Técnica del Desarrollo de SIGEDOR
## Sistema de Gestión Docente y de Reportes

**Autor:** Frank Sousa  
**Repositorio:** frankSousa23/SIGEDOR  
**Fecha del documento:** Agosto 2026  
**Versión del sistema:** v1.0.0

---

## Introducción

Este documento es el registro histórico y técnico completo del ciclo de vida del proyecto SIGEDOR,
desde su creación hasta el estado actual. Está pensado como complemento de tesis y como guía
de mantenimiento futura.

El proyecto atravesó múltiples ciclos de desarrollo, refactorización y recuperación. En cada ciclo,
se intentó añadir una nueva capa de funcionalidad que terminó rompiendo la estabilidad del sistema.
Este documento explica **qué se intentó, por qué falló, y cómo se resolvió definitivamente**.

---

## Línea Temporal del Proyecto

```
2024 (Nov)      2025 (Ene)       2025 (Feb)       2026 (Ago)
│               │                │                │
▼               ▼                ▼                ▼
1% ──► Avance ──► Correcciones ──► upgrade-L12 ──► main v1.0
CRUD            Roles+PDFs        Refactor         Estable +
Básico          intentados        fallida          Integración
```

---

## Fase 1 — Scaffolding Inicial (Nov 2024)

**Commits relevantes:** `1%`, `2%`, `3%`, `Avance`, `Integrando Tablas`

### Qué se construyó

El proyecto comenzó con la creación de los modelos base:

- `Teacher`, `Category`, `Dedication`, `PermissionTeacher`, `Report`, `User`
- Seeders básicos para poblar la base de datos
- Las primeras migraciones de la base de datos
- CRUDs básicos en Filament sin filtros ni restricciones de acceso

### Estado al finalizar

El sistema funcionaba como un CRUD simple: cualquier usuario podía ver todos los datos.
No había control de acceso por rol ni filtrado territorial.

---

## Fase 2 — Primer Intento de Exportación PDF (Feb 2025)

**Commit:** `94215f4 — Exportar a PDF`

### Qué se intentó

Se añadió el paquete `barryvdh/laravel-dompdf` y se creó el primer template de exportación en
`resources/views/reports/export.blade.php`. El template generaba un listado de docentes con sus datos.

### Por qué falló

El template hacía referencia a `$report->site->name`, pero el modelo de Sede ya había sido
renombrado de `Site` a `Sede` y la columna de `site_id` a `sede_id`. El error producido fue:

```
ErrorException: Trying to get property 'name' of non-object
```

### Resolución posterior

El template fue corregido para usar `$report->sede->nombre`. Las vistas PDF finales
(`pdf/teacher.blade.php`, `pdf/teachers.blade.php`, etc.) usan los nombres correctos
y están plenamente funcionales en el estado actual.

---

## Fase 3 — Sistema de Roles y Aislamiento de Datos (Ene–Feb 2025)

**Commits:** `Roles`, `Restart roles`, `RolesVistas`, `Correcciones1` hasta `Correcciones9`

### Qué se intentó

Implementar un sistema de **aislamiento multi-inquilino**: que cada usuario solo pudiera
ver los datos de su propia sede o área. Se utilizó el método `getTableQuery()` en todos
los recursos de Filament:

```php
// Código que se intentó en múltiples Resources
protected function getTableQuery(): Builder
{
    if (auth()->user()->hasRole('admin')) {
        return Model::query(); // Admin ve todo
    }
    if (auth()->user()->hasRole('area_manager')) {
        return Model::query()
            ->where('sede_id', auth()->user()->sede_id)
            ->where('area_id', auth()->user()->area_id);
    }
    return Model::where('user_id', auth()->user()->id);
}
```

### Por qué falló — Causa Raíz Principal

**El método `getTableQuery()` fue deprecado en Filament v2 y eliminado en Filament v3.**
El método correcto en Filament v3 es el estático `getEloquentQuery()`.

Filament v3 **ignoraba silenciosamente el método sin lanzar ningún error**: simplemente
no lo ejecutaba. El resultado era que todos los usuarios seguían viendo todos los datos,
sin ningún mensaje de error que indicara por qué.

```
Filament v2:  protected function getTableQuery(): Builder { ... }    ← deprecado
Filament v3:  public static function getEloquentQuery(): Builder { } ← correcto
```

**El código era lógicamente correcto, pero llamaba al método equivocado.**

### Efecto secundario: La Trampa de SiteOption

Para intentar arreglar la situación, se crearon los modelos `SiteOption` y `AreaOption`
como una capa intermedia. Esto generó nuevos problemas en cascada:

1. **Null Pointer en Administradores:** Los admins no pertenecen a ninguna sede. Al filtrar
   con `auth()->user()->site_option_id`, este valor era `null`, rompiendo las queries.
2. **Redundancia de modelos:** `SiteOption` duplicaba la funcionalidad de `Sede` sin añadir nada nuevo.
3. **Cascada de parches:** Para arreglar el NPE del admin, se añadieron condicionales en el
   `AppServiceProvider`, el login y múltiples recursos, creando código frágil e imposible de mantener.

### Scripts de Emergencia

Durante este período se crearon scripts en la raíz del proyecto (`clean.php`, `rebuild.php`,
`final_fix.php`) para restablecer manualmente el estado de la base de datos cuando el
sistema colapsaba. Estos scripts son evidencia del nivel de emergencia alcanzado.

---

## Fase 4 — Intento de Upgrade a Laravel 12 (Feb 2025)

**Rama:** `upgrade-laravel-12`, commit `1b9cc81`

### Qué se intentó

Ante la inestabilidad acumulada, se intentó una refactorización mayor migrando a Laravel 12
y reescribiendo los recursos más problemáticos. Esta rama contiene la versión más avanzada
y completa del sistema antes de la estabilización.

### Funcionalidades desarrolladas en esta rama (recuperadas en 2026)

| Funcionalidad | Descripción |
|---|---|
| Filtros avanzados | Rango de fechas, cargo directivo, horas, docente con búsqueda |
| Filtros Sede/Área en TeacherResource | Selectores relacionales por sede y área |
| StatsOverview diferenciado por rol | 3 métodos: admin, area_manager, teacher |
| TasksOverview para area_manager | Lista de docentes activos de su sede |
| Navegación dinámica por rol | Menú diferente según el rol del usuario |

### Por qué falló

1. **Mezcla de APIs de Filament v2 y v3:** El `Dashboard.php` implementaba simultáneamente
   `getHeaderWidgets()`, `getFooterWidgets()` y `getWidgets()`. En Filament v3, solo
   `getWidgets()` es relevante. Los otros se ignoraban o causaban conflictos.

2. **Vista Blade personalizada inexistente:** El `render()` del Dashboard intentaba renderizar
   `filament.pages.dashboard`, una vista que no existía en la estructura esperada.

3. **`getTableQuery()` aún presente:** Incluso en esta rama, algunos recursos seguían usando
   el método deprecado.

4. **Providers duplicados:** Se registraron providers redundantes en `bootstrap/providers.php`
   que causaban conflictos al arrancar la aplicación.

---

## Fase 5 — Restauración y Estabilización (2025–2026)

**Ramas:** `restore-branch`, `restart`, `95%`, `main`

### Qué ocurrió

Se realizó una restauración desde un punto funcional conocido con las siguientes
decisiones de arquitectura para garantizar la estabilidad:

1. **Eliminar `SiteOption` y `AreaOption`** — Depender directamente de `Sede` y `Area`.
2. **Usar `getEloquentQuery()` correctamente** — Método estático con la firma correcta de Filament v3.
3. **Implementar Policies de Laravel** — `TeacherPolicy` y `CategoryPolicy` para control de acceso.
4. **Validación de dominio en Login** — Clase `Login` personalizada que valida `@sigedor.com`.
5. **Dashboard con widgets correctos** — Eliminar la vista Blade personalizada y usar los métodos de Filament v3.

---

## Fase 6 — Profesionalización y Cierre (Agosto 2026)

**Rama:** `main`, **Tag:** `v1.0.0`

### Documentación

- `README.md` actualizado con arquitectura multi-tenant y sistema de roles
- `docs/informe_errores_integracion.md` — Reporte técnico de fallas para la tesis
- `docs/historia_desarrollo_sigedor.md` — Este documento

### Seguridad y Control de Acceso

- `TeacherPolicy` — Aislamiento de docentes por sede y área
- `CategoryPolicy` — Restricción de categorías por sede
- `Login.php` personalizado con validación de dominio `@sigedor.com`

### API Documentation (Swagger/OpenAPI)

- Instalación de `darkaonline/l5-swagger`
- `@OA\Info` en `Controller.php`
- `@OA\Schema` en `User.php`
- Documentación accesible en `/api/documentation`

### Testing Automatizado

- `AuthTest.php` — Verifica validación de dominio en login (2 casos)
- `PolicyTest.php` — Verifica aislamiento territorial por rol (2 casos)
- Suite completa: **13 pruebas, 28 aserciones, 100% verde**

### Integración de Mejoras Recuperadas

Las siguientes funcionalidades existían en ramas antiguas pero nunca habían llegado a `main`.
Se integraron correctamente en esta sesión final:

| Mejora | Archivo | Origen |
|---|---|---|
| `getEloquentQuery()` en DedicationResource | `DedicationResource.php` | `upgrade-laravel-12` |
| `getEloquentQuery()` en PermissionTeacherResource | `PermissionTeacherResource.php` | `upgrade-laravel-12` |
| `canView()` para todos los roles en StatsOverview | `StatsOverview.php` | `upgrade-laravel-12` |
| `canView()` para area_manager en TasksOverview | `TasksOverview.php` | `upgrade-laravel-12` |
| Filtros de docente y fecha en CategoryResource | `CategoryResource.php` | `upgrade-laravel-12` |
| Filtros de horas, cargo y docente en DedicationResource | `DedicationResource.php` | `upgrade-laravel-12` |
| Filtros de duración, fecha y docente en PermissionTeacherResource | `PermissionTeacherResource.php` | `upgrade-laravel-12` |
| Filtro de rango de fechas en TeacherResource | `TeacherResource.php` | `upgrade-laravel-12` |

---

## Mapa Completo de Errores y Soluciones

| Error Encontrado | Causa Raíz | Solución Definitiva |
|---|---|---|
| Datos sin filtrar por rol | `getTableQuery()` ignorado por Filament v3 | Cambiar a `getEloquentQuery()` estático |
| NPE en usuarios administradores | `SiteOption` era null en admins | Eliminar `SiteOption`, usar Policies |
| Dashboard mostraba 404/500 | Vista Blade personalizada inexistente | Usar widgets nativos de Filament v3 |
| Login permitía cualquier dominio | Sin validación de email | Clase `Login` personalizada con validación |
| Widgets no visibles para area_manager | `canView()` solo permitía admin | Cambiar a `hasAnyRole()` |
| PDFs con error de columna | Referencia a `site->name` en lugar de `sede->nombre` | Actualizar templates |
| Providers duplicados | Migración incompleta a Laravel 12 | Limpiar `bootstrap/providers.php` |

---

## Estado Final del Sistema (Agosto 2026)

### Stack Tecnológico

```
Framework    : Laravel 11.x
Admin Panel  : Filament v3.x
Auth y Roles : Filament Auth + Spatie Permission
Base de Datos: MySQL (producción/Laragon) / SQLite (testing)
PDF Export   : barryvdh/laravel-dompdf
API Docs     : darkaonline/l5-swagger (OpenAPI 3.0)
Testing      : Pest PHP
Versión Tag  : v1.0.0
```

### Roles y Permisos

```
admin        → Ve y gestiona TODO el sistema
area_manager → Ve solo su Sede. Widget TasksOverview activo.
teacher      → Solo sus propios registros.
```

### Aislamiento de Datos (getEloquentQuery)

```
CategoryResource          ✅ por sede del docente
DedicationResource        ✅ por sede del docente  ← INTEGRADO EN ESTA SESIÓN
PermissionTeacherResource ✅ por sede del docente  ← INTEGRADO EN ESTA SESIÓN
ReportResource            ✅ por sede del docente
```

### Filtros Disponibles por Recurso

```
CategoryResource          → Categoría, Docente*, Rango de fechas*
DedicationResource        → Tipo, Cargo*, Docente*, Rango de horas*
PermissionTeacherResource → Estado, Tipo, Duración*, Docente*, Rango de fecha*
TeacherResource           → Género, Sede, Área, Rango de fechas*

* = INTEGRADO EN ESTA SESIÓN (recuperado de ramas antiguas)
```

---

## Evolución del Modelo Territorial y Sectorial: Sede, Área y Docente

Un aspecto central del desarrollo fue la transición desde un modelo de datos plano hacia una **arquitectura multi-inquilino basada en territorio (Sede) y facultad (Área)**:

### 1. El Planteamiento Original y la Necesidad de Sectorización
* En las primeras iteraciones, los datos institucionales estaban almacenados de forma monolítica en una o dos tablas maestras (`teachers`, `users`), guardando sedes y áreas como texto plano.
* La necesidad institucional universitaria exigía que, al registrar un usuario (`User`), se le asignaran obligatoriamente su `sede_id` y su `area_id`.
* Esto permitiría vincular la cadena de custodia: `Usuario` ➔ `Docente` ➔ `Sede` ➔ `Área Académica`, restringiendo a los Jefes de Área (`area_manager`) para que **únicamente puedan auditar, crear reportes y gestionar docentes adscritos a su misma sede y facultad**.

### 2. Factores de Fracaso en las Ramas Antiguas
* **Selects dependientes en cascada:** Al intentar que el selector de Área/Programa dependiera de la Sede seleccionada, la falta de reactividad adecuada en los formularios de Filament provocaba pérdida de estado y campos nulos.
* **La trampa de `SiteOption` / `AreaOption`:** El intento de crear tablas intermedias de opciones por sitio generó excepciones de puntero nulo (`NullPointerException`) en los administradores del sistema, quienes no poseen una sede fija.
* **Duplicación de claves foráneas:** Se colocaron `sede_id` y `area_id` redundantemente en todas las tablas transaccionales, creando inconsistencias cuando un docente o usuario era editado.

### 3. Solución Arquitectónica Definitiva (Estado Actual v1.0.0)
* **Modelos Dimensionales Claros:** Tablas normalizadas `sedes`, `areas` y `programas` relacionadas mediante Eloquent estándar.
* **Seguridad Desacoplada mediante Policies:** `TeacherPolicy` y `CategoryPolicy` protegen los recursos a nivel de lógica de negocio, evaluando `user->sede_id === teacher->sede_id`.
* **Scopes Dinámicos en Filament:** Implementación de `getEloquentQuery()` estático en todos los recursos (`TeacherResource`, `CategoryResource`, `DedicationResource`, `PermissionTeacherResource`, `ReportResource`).
* **Tratamiento Especial a Administradores:** El rol `admin` tiene pase maestro (`before()` en Policies y `query()` sin restricciones territoriales).

### 4. Propuestas de Mejora y Futuras Extensiones Arquitectónicas
* **Auto-llenado Reactivo de Docentes:** Al seleccionar un `User` en `TeacherResource`, heredar automáticamente `sede_id`, `area_id` y `email` para agilizar la carga.
* **Filtrado Reactivo en Vivo (`->live()`):** Implementar carga dinámica de áreas según la sede seleccionada en los formularios administrativos.
* **Matriz Territorial (`sede_area`):** Modelar mediante tabla pivote las carreras o áreas específicas que se imparten en cada núcleo universitario.

---

## Lecciones Técnicas para Futuros Desarrolladores

1. **Verificar siempre la versión de la API de Filament.** Entre v2 y v3 hubo cambios de ruptura
   silenciosos. `getTableQuery()` fue reemplazado por `getEloquentQuery()` (estático, público).
   Cuando un filtro "no funciona", verificar el nombre del método es el primer paso.

2. **No crear modelos intermedios innecesarios.** `SiteOption` era `Sede` con otro nombre.
   La duplicación de abstracciones multiplica la complejidad sin añadir valor.

3. **Los Administradores son un caso especial.** Cualquier filtro global debe tener una cláusula
   que libere al admin de restricciones territoriales. De lo contrario, el Null Pointer es inevitable.

4. **Las Policies de Laravel son preferibles a los filtros inline.** Centralizar la lógica de
   autorización en una Policy permite testearla en aislamiento y mantenerla fácilmente.

5. **Los tests automatizados son el mejor seguro.** La batería de 13 pruebas habría detectado
   todos los errores descritos en este documento en segundos, antes de llegar a producción.

---

*Documento generado como parte de la sesión de cierre del proyecto SIGEDOR — Agosto 2026.*

