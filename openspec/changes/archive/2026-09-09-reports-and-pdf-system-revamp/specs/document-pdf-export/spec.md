## ADDED Requirements

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
