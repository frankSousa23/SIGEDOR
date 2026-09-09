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
