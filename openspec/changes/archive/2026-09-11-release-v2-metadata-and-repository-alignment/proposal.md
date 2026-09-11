## Why

Tras consolidar todas las mejoras de arquitectura, RBAC multi-inquilino, expedientes 360°, generación de PDFs institucionales, especificaciones OpenAPI y erradicación de consultas N+1, el proyecto SIGEDOR ha alcanzado la madurez necesaria para su lanzamiento formal **v2.0.0**. Sin embargo, `composer.json` aún conserva el nombre y descripción del esqueleto genérico de Laravel (`laravel/laravel`), y la documentación en `README.md` requiere reflejar el hito de la versión 2.0 y el recuento total de 49 pruebas automatizadas para acompañar el lanzamiento y la creación del tag oficial.

## What Changes

- **Alineación de Metadatos de Paquete (`composer.json`)**:
  - Actualizar el nombre de paquete a `franksousa23/sigedor`.
  - Configurar la descripción institucional oficial de SIGEDOR.
  - Agregar palabras clave relevantes (`laravel`, `filament`, `unerg`, `academic-management`, `escalafon-docente`, `multi-tenancy`, `openapi`, `dompdf`).
- **Actualización de Documentación e Insignias (`README.md`)**:
  - Incorporar la insignia del release `v2.0.0` y reflejar el hito de la versión mayor.
  - Actualizar la sección de pruebas destacando los 49 tests unitarios y de integración con 372 aserciones aprobadas.
- **Optimización y Mantenimiento del Repositorio Git**:
  - Ejecutar empaquetado y recolección de basura de Git (`git gc --prune=now`) para optimizar el tamaño de la base de objetos local.
- **Lanzamiento y Etiquetado de Versión**:
  - Crear el tag anotado oficial `v2.0.0` y sincronizarlo con el repositorio remoto GitHub.

## Capabilities

### New Capabilities
<!-- No se introducen nuevas capacidades de comportamiento en este cambio de metadatos y release. -->

### Modified Capabilities
<!-- Sin modificaciones de requerimientos de comportamiento del sistema. El cambio utiliza skip_specs: true. -->

## Impact

- **Publicación y Empaquetado**: El paquete en Composer y la descripción del proyecto quedan perfectamente identificados bajo la autoría del repositorio.
- **Transparencia y Documentación**: Visitantes, colaboradores y evaluadores disponen de notas de versión y documentación completamente sincronizadas con la realidad del código.
- **Riesgo**: Nulo para el código de producción o la lógica de negocio; 100% retrocompatible.
