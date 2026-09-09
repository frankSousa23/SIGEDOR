## 1. Base de Datos, Modelo y Seguridad (RBAC)

- [x] 1.1 Crear migración para agregar `verification_code`, `created_by` y `status` a la tabla `reports` y ejecutar `php artisan migrate`.
- [x] 1.2 Actualizar el modelo `Report` con `$fillable`, `$casts`, relación `creator()` y evento `creating` para generar el `verification_code`.
- [x] 1.3 Corregir `ReportPolicy` vinculando el perfil del docente a través de `teacher_cdi` y restringiendo creación, edición y borrado por rol.
- [x] 1.4 Crear prueba automatizada `tests/Feature/ReportSecurityTest.php` verificando el aislamiento estricto por roles (`admin`, `area_manager`, `teacher`).

## 2. Motor de Plantillas PDF Institucionales (UNERG)

- [x] 2.1 Actualizar `resources/views/pdf/base.blade.php` con membrete jerárquico oficial de la UNERG, logotipo `LogoUnerg.png`, pie de página con código de verificación, hora legal de Venezuela y paginación formal.
- [x] 2.2 Estandarizar `resources/views/pdf/report.blade.php` con secciones especializadas (Constancia de Trabajo certificada, Memorando Administrativo, Informe de Escalafón y Carga Horaria) e incorporar bloques de firmas y sellos reglamentarios.
- [x] 2.3 Actualizar `resources/views/pdf/reports.blade.php` (exportación masiva) para extender de `pdf.base` en orientación apaisada con estilos tipográficos consistentes.
- [x] 2.4 Actualizar y ampliar `tests/Feature/PdfGenerationTest.php` verificando que todos los tipos de reportes individuales y masivos generen flujos PDF válidos conteniendo el membrete y código de verificación.

## 3. Optimización de ReportResource en Filament

- [x] 3.1 Implementar aislamiento en `ReportResource::getEloquentQuery()` restringiendo la consulta de docentes a su propio `teacher_cdi` y jefes de área a su `sede_id`.
- [x] 3.2 Actualizar el formulario de `ReportResource` con preselección y bloqueo seguro de `teacher_cdi` para docentes, selector de `status` y visualización de solo lectura para `verification_code`.
- [x] 3.3 Añadir badges semánticos para el estado y tipo de reporte en la tabla de `ReportResource`, junto con la acción de descarga PDF individual estilizada.

## 4. Interconexión del Ecosistema de Reportes

- [x] 4.1 Crear `ReportsRelationManager` en `TeacherResource` mostrando la lista de reportes emitidos al docente con botón de descarga rápida en PDF.
- [x] 4.2 Incorporar la acción rápida de fila "Emitir Constancia" en `TeacherResource` para generar y descargar inmediatamente la constancia oficial del docente.
- [x] 4.3 Incorporar acción en `PermissionTeacherResource` para emitir un memorando oficial a partir de un permiso aprobado.
- [x] 4.4 Crear widget `LatestReportsWidget` y registrarlo en `Dashboard.php` para monitoreo de reportes recientes.

## 5. Auditoría, Calidad y Validación Integral

- [x] 5.1 Ejecutar la suite completa de pruebas con Pest (`vendor/bin/pest`) confirmando el 100% de pruebas en verde.
- [x] 5.2 Ejecutar `vendor/bin/pint --test` confirmando el cumplimiento estricto del estándar de código Laravel.
- [x] 5.3 Ejecutar `openspec validate --all` confirmando que todas las especificaciones y cambios sean válidos.
