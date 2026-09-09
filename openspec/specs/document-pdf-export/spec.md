# document-pdf-export Specification

## Purpose

Define los requisitos de emisión y descarga de documentos oficiales y expedientes en formato PDF para docentes, dedicaciones, permisos y reportes, asegurando su renderizado libre de errores y con datos fidedignos.

## Requirements

### Requirement: Generación Segura de Expediente Individual
El sistema DEBE permitir descargar el expediente individual en PDF de cualquier docente registrado, renderizando correctamente sus datos demográficos y de adscripción institucional (sede y área) sin arrojar excepciones de variables no definidas.

#### Scenario: Descarga de expediente docente individual
- **WHEN** un usuario con permisos ejecuta la acción "Expediente PDF" en la tabla de docentes
- **THEN** el sistema genera y descarga un flujo PDF válido con código HTTP 200 conteniendo los nombres de la sede y área académica asignadas

### Requirement: Generación de Constancias de Dedicación y Permisos
El sistema DEBE generar constancias de dedicación horaria y permisos docentes manejando de forma segura variaciones en las denominaciones de dedicación y valores opcionales en relaciones.

#### Scenario: Descarga de informe de dedicación docente
- **WHEN** el usuario solicita el PDF de una dedicación registrada con nombres descriptivos (ej. "Tiempo Completo", "Exclusiva")
- **THEN** la plantilla procesa y formatea el valor sin arrojar excepciones `UnhandledMatchError` y entrega el PDF correspondiente

### Requirement: Integridad y Longitud de Contenido en Reportes
El sistema DEBE permitir el almacenamiento de dictámenes y observaciones académicas de longitud extensa en los reportes y renderizar correctamente la sede asociada en las plantillas oficiales.

#### Scenario: Creación y exportación de reporte extenso
- **WHEN** un usuario registra un reporte con un dictamen mayor a 255 caracteres y genera su documento PDF
- **THEN** la base de datos almacena el texto completo sin truncamiento y el PDF muestra el nombre oficial de la sede universitaria vinculada

### Requirement: Membrete Oficial UNERG y Formato de Documentos
El sistema DEBE renderizar en las plantillas PDF oficiales de reportes, constancias y memorandos el membrete jerárquico de la República, Ministerio, Universidad Nacional Experimental "Rómulo Gallegos" y Vicerrectorado Académico, acompañado del logotipo institucional nítido.

#### Scenario: Generación de documento individual oficial
- **WHEN** un usuario genera y descarga cualquier constancia o reporte en PDF
- **THEN** el documento generado presenta el encabezado oficial de la UNERG, logotipo y estilo tipográfico formal sin deformaciones visuales

### Requirement: Código de Autenticidad y Validez Documental
Cada reporte emitido DEBE portar un código único de verificación institucional y marca de tiempo legal venezolana (`America/Caracas`) tanto en la base de datos como en el pie de página del documento PDF.

#### Scenario: Emisión y validación de reporte
- **WHEN** se crea un nuevo registro en el módulo de reportes
- **THEN** el sistema autogenera un código unívoco de verificación y lo expone en el pie de página del documento descargado

### Requirement: Bloque Reglamentario de Firmas y Sellos
El sistema DEBE incorporar en los documentos oficiales e informes académicos casillas reglamentarias con líneas de firma y sello para el Jefe de Área Académica, Vicerrectorado Académico / Recursos Humanos y Docente Interesado.

#### Scenario: Renderizado de firmas en constancia o memo
- **WHEN** se visualiza o imprime el PDF de un informe o constancia
- **THEN** la sección final incluye los bloques con espacio para firmas y sellos institucionales
