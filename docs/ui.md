# Interfaz de Usuario y Experiencia (UI/UX) en SIGEDOR

La interfaz de usuario de SIGEDOR está construida con **Filament v3**, aprovechando **Livewire 3**, **Tailwind CSS** y **Alpine.js** para brindar una experiencia de usuario ágil, reactiva, accesible y adaptada al ámbito universitario.

---

## 1. Estructura General del Panel Administrativo (`/admin`)

- **Barra de Navegación Lateral (Sidebar)**:
  - Agrupación lógica de recursos: *Gestión Docente* (Docentes, Categorías, Dedicaciones, Sedes), *Trámites Académicos* (Permisos, Reportes) y *Sistema* (Usuarios, Roles).
  - Enlace rápido de retorno a la página de bienvenida / landing page.
- **Cabecera y Perfil**:
  - Selector de tema (Modo Claro / Modo Oscuro automático).
  - Menú de perfil de usuario con opción segura de cierre de sesión.
- **Diseño Responsivo**:
  - Optimizado para pantallas de escritorio, portátiles, tabletas y teléfonos móviles mediante grids adaptativos de Tailwind CSS.

---

## 2. Dashboard y Widgets del Escritorio

El panel principal (`App\Filament\Pages\Dashboard`) presenta 5 widgets complementarios:

1. **`StatsOverview`**:
   - Tarjetas informativas con micro-gráficos (*sparklines*) que resumen el total de docentes, profesores activos, solicitudes de permisos pendientes y reportes generados.
2. **`TeacherDistributionChart`**:
   - Gráfico tipo dona (*doughnut chart*) con la distribución del personal docente según su escalafón académico (Instructor, Asistente, Agregado, Asociado, Titular).
3. **`SedeStatsChart`**:
   - Gráfico de barras que compara la cantidad de profesores adscritos por cada núcleo territorial de la universidad.
4. **`TasksOverview`**:
   - Resumen visual de la carga de revisión de solicitudes y trámites pendientes por resolver.
5. **`LatestReportsWidget`**:
   - Tabla interactiva con los reportes más recientes, badges de estado, indicación de código de verificación y botón de descarga directa de PDF.

---

## 3. Componentes Interactivos y Formularios Inteligentes

- **Wizard de Creación en 3 Pasos**:
  - En `TeacherResource`, la carga del expediente se divide en pasos claros con validación por etapas: Datos Personales -> Adscripción Institucional -> Cátedra y Escalafón.
- **Selectores Dependientes en Cascada**:
  - La selección de una Sede filtra automáticamente las Áreas disponibles, y la elección del Área actualiza los Programas correspondientes en tiempo real sin recargar la página.
- **Auto-llenado Inteligente**:
  - Al seleccionar una cuenta de usuario en el formulario docente, se rellenan automáticamente nombres, apellidos, correo institucional y sede.
- **Badges Semánticos en Tablas**:
  - Uso de colores armónicos para identificar estados de permisos (`pending` en amarillo, `approved` en verde, `rejected` en rojo), géneros y categorías académicas.

---

## 4. Expediente Integral 360° en Pestañas (Tabs)

Al consultar o editar un docente en `TeacherResource`, el panel despliega 4 pestañas interactivas sin necesidad de navegar a módulos separados:
1. **Pestaña Permisos**: Historial de solicitudes, fechas y resoluciones.
2. **Pestaña Categoría**: Títulos y fechas de ascenso en el escalafón.
3. **Pestaña Dedicación**: Modalidad de contratación y horas semanales.
4. **Pestaña Reportes**: Constancias emitidas con previsualización y descarga inmediata.

---

## 5. Acciones en Modal Deslizante (SlideOver)

- Los reportes y constancias pueden inspeccionarse directamente dentro de la interfaz mediante modales laterales deslizantes (`slideOver`), permitiendo leer el contenido del informe antes de descargarlo o imprimirlo.

---

## 6. Arquitectura Responsiva Universal y Experiencia Móvil (v2.0.0)

SIGEDOR incorpora un sistema de diseño adaptativo ergonómico pensado para usabilidad completa en smartphones, tablets y pantallas de alta resolución:

### 6.1 Navegación Móvil Ergonómica
- **Barra Inferior Fija (*Mobile Bottom Navigation Bar*):** Ubicada al alcance del pulgar (`fixed bottom-0 left-0 right-0 z-40`), permite alternar instantáneamente entre Inicio, Docentes, Reportes y el menú expandido sin estirar la mano.
- **Cajón Contextual Táctil (*Drawer Navigation*):** Menú lateral deslizante con desenfoque de fondo (*backdrop blur*) que brinda acceso a todas las secciones (Estadísticas, Permisos, Láminas, Documentación API y cierre de sesión).

### 6.2 Barra Lateral Colapsable de Escritorio
- Los usuarios en monitores estándar y panorámicos pueden contraer o expandir la barra lateral con un solo clic, maximizando el espacio de visualización para tablas de datos densas e informes analíticos.

### 6.3 Alternancia Dual: Vista de Tabla y Vista de Tarjetas
- En el catálogo de docentes, la interfaz ofrece un conmutador de vista:
  - **Modo Tabla:** Densidad de datos óptima para revisión rápida de cédula, correo, sede, área, categoría y dedicación con paginación fluida.
  - **Modo Tarjetas:** Bloques individuales enriquecidos con avatar, insignias semánticas de escalafón, dedicación y botones de acción rápida, ideal para interacción táctil en dispositivos móviles.

### 6.4 Visor Documental Oficial A4 con Verificación QR
- Módulo `OfficialDocumentView` que simula en pantalla una hoja membretada de formato institucional A4 UNERG.
- Incorpora sello criptográfico de verificación, código QR validable y reglas de impresión `@media print` que ocultan la interfaz de usuario al imprimir o exportar a PDF desde el navegador.

### 6.5 Compendio de Láminas de Tesis y Explorador Swagger
- Visualizador interactivo de 12 láminas vectoriales de arquitectura con previsualización en pantalla completa y navegación paso a paso.
- Modal de documentación OpenAPI 3.0 con cliente de pruebas interactivo (*Try it out*) integrado en la interfaz.
