import React, { useState } from 'react';
import { 
  Presentation, 
  Layers, 
  ChevronLeft, 
  ChevronRight, 
  Maximize2, 
  Download, 
  ExternalLink,
  BookOpen,
  CheckCircle2,
  Cpu,
  ShieldAlert,
  Database,
  Grid
} from 'lucide-react';

interface SlideMeta {
  num: number;
  title: string;
  subtitle: string;
  category: string;
  keyPoints: string[];
}

const SLIDES_DATA: SlideMeta[] = [
  {
    num: 1,
    title: 'SIGEDOR: El Motor Digital del Control Académico Universitario',
    subtitle: 'Presentación Oficial del Proyecto de Grado / Investigación',
    category: 'Portada Institucional',
    keyPoints: [
      'Desarrollado para la Universidad Nacional Experimental de los Llanos Centrales Rómulo Gallegos (UNERG).',
      'Núcleo técnico: Laravel 11, Filament v3, OpenAPI 3.0 y Licencia de código abierto MIT.',
      'Autoría: Frank Sousa.'
    ]
  },
  {
    num: 2,
    title: 'Un ecosistema centralizado para la administración académica',
    subtitle: 'Interconexión modular en torno al núcleo de Laravel 11',
    category: 'Visión General',
    keyPoints: [
      'Gestión integral de expedientes docentes con adscripción territorial.',
      'Motor de Escalafón Universitario y control de horas semanales.',
      'Aislamiento Multi-Inquilino (Spatie) para sedes y áreas de conocimiento.',
      'Generador oficial de documentos y memorandos en DomPDF con sello institucional.'
    ]
  },
  {
    num: 3,
    title: 'El Expediente Integral 360° sincroniza la vida académica del docente',
    subtitle: 'Integración atómica de cuatro dimensiones de gestión',
    category: 'Expediente Docente',
    keyPoints: [
      'Adscripción Institucional: Sede territorial, Área de conocimiento y Programas académicos.',
      'Escalafón Universitario: Historial cronológico con validación automática de categoría vigente.',
      'Carga Horaria: Modalidades de contratación, horas semanales y cargos directivos.',
      'Permisos y Licencias: Historial completo de ausencias aprobadas y reportes oficiales generados.'
    ]
  },
  {
    num: 4,
    title: 'Automatización de las reglas de negocio para la carrera universitaria',
    subtitle: 'Matriz bidimensional de Categoría de Escalafón vs. Dedicación Horaria',
    category: 'Reglas de Negocio',
    keyPoints: [
      'Categorías de escalafón: Instructor, Asistente, Agregado, Asociado y Titular.',
      'Dedicaciones horarias: Tiempo Convencional (TCV), Medio Tiempo (MT), Tiempo Completo (TC) y Dedicación Exclusiva (EX).',
      'Control estricto de UC (Unidades de Crédito) y cupos de atención a estudiantes.',
      'Requisito obligatorio de títulos de posgrado (Especializaciones, Maestrías y Doctorados) para ascensos superiores.'
    ]
  },
  {
    num: 5,
    title: 'Del trámite a la certificación: Flujo de emisión automatizada de documentos',
    subtitle: 'Canalización digital sin papel con sello criptográfico institucional',
    category: 'Documentación Oficial',
    keyPoints: [
      'Paso 1: Solicitud e ingreso de datos para años sabáticos, comisiones de servicio o permisos.',
      'Paso 2: Aprobación y fiscalización de vigencias semestrales por roles jerárquicos en panel Filament.',
      'Paso 3: Generación instantánea de PDF en hojas membretadas oficiales UNERG.',
      'Embebido de código alfanumérico único de autenticidad para verificación institucional.'
    ]
  },
  {
    num: 6,
    title: 'Arquitectura Multi-Inquilino para un control de acceso estrictamente territorial',
    subtitle: 'Seguridad perimetral y particionado lógico de datos con Spatie',
    category: 'Seguridad y Tenancy',
    keyPoints: [
      'Acceso restringido a correos institucionales autorizados (@sigedor.com y @unerg.edu.ve).',
      'Super Administrador: Gestión centralizada de todas las sedes, docentes, áreas y auditoría global.',
      'Jefe de Área: Scopes de Eloquent aplicados automáticamente; solo visualiza registros de su propia sede.',
      'Soporte multi-rol: Capacidad para que un usuario sea docente activo y directivo de área simultáneamente.'
    ]
  },
  {
    num: 7,
    title: 'Pipeline de ingesta masiva: De archivos planos a persistencia relacional',
    subtitle: 'Data Factory y Seeders para inicialización atómica de catálogos',
    category: 'Ingesta de Datos',
    keyPoints: [
      'Fuentes CSV: users.csv, teachers.csv, categories.csv, dedications.csv y sites.csv.',
      'Normalización y transacciones DB seguras con foreign keys garantizadas.',
      'Requisito de aprobación explícita (is_approved) para activación de credenciales importadas.',
      'Soporte para comando CLI de anonimización de datos de prueba docentes.'
    ]
  },
  {
    num: 8,
    title: 'Arquitectura de software en tres capas para rendimiento y mantenibilidad',
    subtitle: 'Separación estricta de responsabilidades (Presentación, Lógica y Persistencia)',
    category: 'Arquitectura de Software',
    keyPoints: [
      'Capa 1 (Presentación): Filament v3, Vistas Blade responsive y motor de documentos DomPDF.',
      'Capa 2 (Lógica y Seguridad): Laravel 11 MVC, Spatie Permissions y auditoría ActivityLog.',
      'Capa 3 (Persistencia): Modelos relacionales Eloquent, MySQL 8.0+ / MariaDB con integridad referencial.'
    ]
  },
  {
    num: 9,
    title: 'Modelo relacional: La integridad estructural del dominio universitario',
    subtitle: 'Diagrama de Entidad-Relación institucional',
    category: 'Base de Datos',
    keyPoints: [
      'Relación 1:N entre Sedes, Áreas, Programas y la entidad central Docentes (TEACHERS).',
      'Relación 1:1 entre Docente y su Escalafón (CATEGORIES) y Carga Horaria (DEDICATIONS).',
      'Relación 1:1 entre Usuario del sistema (USERS) y el perfil docente (TEACHERS).',
      'Relación 1:N para Cátedras/Asignaturas asignadas (SITES), Licencias (PERMISSIONS) y Documentos (REPORTS).'
    ]
  },
  {
    num: 10,
    title: 'Interoperabilidad nativa: API RESTful bajo el estándar OpenAPI 3.0',
    subtitle: 'Integración fluida con sistemas de nómina, control de estudios y tableros',
    category: 'Interoperabilidad & API',
    keyPoints: [
      'Especificación OpenAPI 3.0 / Swagger disponible en api-docs.json.',
      'Endpoints CRUD estandarizados para Docentes, Evaluaciones, Sedes y Cargas.',
      'Healthchecks de estado para monitoreo en producción (Docker, Kubernetes).',
      'Autenticación vía Token Bearer y serialización JSON estricta.'
    ]
  },
  {
    num: 11,
    title: 'Fiabilidad de grado empresarial: Pruebas automatizadas y auditoría',
    subtitle: 'QA Mission Control Dashboard y Trazabilidad Forense de Acciones',
    category: 'Calidad & Auditoría',
    keyPoints: [
      '49 Pruebas Pasadas y 372 Aserciones (100% éxito en Pest / PHPUnit).',
      'Validación exhaustiva de políticas multi-inquilino, reglas de ascenso UNERG y endpoints REST.',
      'Trazabilidad total con ActivityLog: registro inmutable de IP, usuario y modificaciones.',
      'Protección contra inyección CSV, contraseñas Argon2id y cabeceras OWASP.'
    ]
  },
  {
    num: 12,
    title: 'Nacido como investigación, diseñado para escalar globalmente',
    subtitle: 'Código abierto bajo Licencia MIT para cualquier institución de educación superior',
    category: 'Impacto & Escalabilidad',
    keyPoints: [
      'Bifurcación y personalización amparada por la Licencia de software libre MIT.',
      'Estructura desacoplada que permite expandir módulos hacia evaluación estudiantil o posgrados.',
      'Adaptación ágil de membretes oficiales y catálogos de sedes mediante Seeders.',
      'Investigación rigurosa convertida en software listo para despliegue en servidores de producción.'
    ]
  }
];

export const SystemPresentationView: React.FC = () => {
  const [activeTab, setActiveTab] = useState<'infographic' | 'slides'>('infographic');
  const [currentSlideIndex, setCurrentSlideIndex] = useState(0);
  const [isFullScreen, setIsFullScreen] = useState(false);

  const currentSlide = SLIDES_DATA[currentSlideIndex];

  const handleNextSlide = () => {
    setCurrentSlideIndex((prev) => (prev < SLIDES_DATA.length - 1 ? prev + 1 : 0));
  };

  const handlePrevSlide = () => {
    setCurrentSlideIndex((prev) => (prev > 0 ? prev - 1 : SLIDES_DATA.length - 1));
  };

  return (
    <div className="space-y-6">
      {/* Top Banner */}
      <div className="bg-gradient-to-r from-slate-900 via-unerg-blue to-slate-900 rounded-2xl p-6 lg:p-8 text-white shadow-xl relative overflow-hidden">
        <div className="absolute right-0 top-0 bottom-0 w-1/3 opacity-10 pointer-events-none bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px]"></div>
        
        <div className="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4 sm:gap-6">
          <div className="space-y-2">
            <div className="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-amber-300 text-xs font-bold tracking-wider uppercase backdrop-blur-sm">
              <Presentation className="w-3.5 h-3.5" />
              Documentación Visual &bull; Contexto Oficial del Proyecto
            </div>
            <h1 className="text-xl sm:text-2xl lg:text-3xl font-black tracking-tight text-white">
              SIGEDOR: Gestión Digital Docente Universitaria
            </h1>
            <p className="text-slate-300 text-xs sm:text-sm max-w-2xl leading-relaxed">
              Explora la arquitectura técnica, el modelo de datos relacional y las 12 láminas de la presentación académica del sistema desarrollado para la Universidad Rómulo Gallegos (UNERG).
            </p>
          </div>

          {/* Toggle buttons */}
          <div className="w-full md:w-auto grid grid-cols-1 sm:grid-cols-2 md:flex items-center gap-2 bg-slate-800/80 p-1.5 rounded-xl border border-white/10">
            <button
              onClick={() => setActiveTab('infographic')}
              className={`flex items-center justify-center gap-2 px-3.5 py-2 rounded-lg text-xs font-bold transition-all ${
                activeTab === 'infographic'
                  ? 'bg-unerg-blue text-white shadow-md'
                  : 'text-slate-300 hover:text-white hover:bg-slate-700/50'
              }`}
            >
              <Layers className="w-4 h-4 text-amber-300 flex-shrink-0" />
              <span className="truncate">Visión Integral (Infografía)</span>
            </button>
            <button
              onClick={() => setActiveTab('slides')}
              className={`flex items-center justify-center gap-2 px-3.5 py-2 rounded-lg text-xs font-bold transition-all ${
                activeTab === 'slides'
                  ? 'bg-unerg-blue text-white shadow-md'
                  : 'text-slate-300 hover:text-white hover:bg-slate-700/50'
              }`}
            >
              <Presentation className="w-4 h-4 text-amber-300 flex-shrink-0" />
              <span className="truncate">Láminas de Presentación (12)</span>
            </button>
          </div>
        </div>
      </div>

      {/* TAB 1: Infografía de Visión Integral */}
      {activeTab === 'infographic' && (
        <div className="space-y-6">
          {/* Main Visual Card */}
          <div className="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-6 overflow-hidden">
            <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100 mb-6">
              <div>
                <h2 className="text-lg font-bold text-slate-900 flex items-center gap-2">
                  <BookOpen className="w-5 h-5 text-unerg-blue" />
                  Infografía Oficial: Visión Integral de Arquitectura
                </h2>
                <p className="text-xs text-slate-500">
                  Resumen de alto nivel que abarca capacidades funcionales, ecosistema Laravel 11 y modelo de seguridad multi-inquilino.
                </p>
              </div>
              <div className="flex items-center gap-2">
                <a
                  href="/images/vision_integral_sigedor.svg"
                  target="_blank"
                  rel="noreferrer"
                  className="inline-flex items-center gap-2 px-3 py-1.5 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors"
                >
                  <ExternalLink className="w-3.5 h-3.5" />
                  Abrir en pestaña completa
                </a>
              </div>
            </div>

            {/* High-res embedded SVG */}
            <div className="relative rounded-xl border border-slate-200 bg-slate-50 overflow-hidden shadow-inner flex justify-center">
              <img
                src="/images/vision_integral_sigedor.svg"
                alt="SIGEDOR - Visión Integral del Sistema"
                className="w-full h-auto max-h-[800px] object-contain"
                loading="lazy"
              />
            </div>
          </div>

          {/* 3 Pillars Breakdown */}
          <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div className="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
              <div className="w-10 h-10 rounded-xl bg-blue-100 text-unerg-blue flex items-center justify-center font-bold mb-4">
                <Layers className="w-5 h-5" />
              </div>
              <h3 className="text-base font-bold text-slate-900 mb-2">Capacidades del Sistema</h3>
              <ul className="text-xs text-slate-600 space-y-2.5">
                <li className="flex items-start gap-2">
                  <CheckCircle2 className="w-4 h-4 text-emerald-600 flex-shrink-0 mt-0.5" />
                  <span><strong>Expediente 360°:</strong> Datos personales, cédula y adscripción a sedes territoriales y áreas de conocimiento.</span>
                </li>
                <li className="flex items-start gap-2">
                  <CheckCircle2 className="w-4 h-4 text-emerald-600 flex-shrink-0 mt-0.5" />
                  <span><strong>Escalafón Universitario:</strong> Historial de ascensos de Instructor a Titular con verificación de posgrados.</span>
                </li>
                <li className="flex items-start gap-2">
                  <CheckCircle2 className="w-4 h-4 text-emerald-600 flex-shrink-0 mt-0.5" />
                  <span><strong>Carga Horaria:</strong> Modalidades TCV, MT, TC y EX con cálculo de unidades de crédito.</span>
                </li>
                <li className="flex items-start gap-2">
                  <CheckCircle2 className="w-4 h-4 text-emerald-600 flex-shrink-0 mt-0.5" />
                  <span><strong>Reportes DomPDF:</strong> Constancias con sello alfanumérico institucional verificable.</span>
                </li>
              </ul>
            </div>

            <div className="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
              <div className="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center font-bold mb-4">
                <Cpu className="w-5 h-5" />
              </div>
              <h3 className="text-base font-bold text-slate-900 mb-2">Ecosistema Tecnológico</h3>
              <ul className="text-xs text-slate-600 space-y-2.5">
                <li className="flex items-start gap-2">
                  <CheckCircle2 className="w-4 h-4 text-emerald-600 flex-shrink-0 mt-0.5" />
                  <span><strong>Laravel 11 + Filament v3:</strong> Interfaz administrativa de alta productividad reactiva.</span>
                </li>
                <li className="flex items-start gap-2">
                  <CheckCircle2 className="w-4 h-4 text-emerald-600 flex-shrink-0 mt-0.5" />
                  <span><strong>OpenAPI 3.0:</strong> Swagger interactivo documentado para consumo externo e interoperabilidad.</span>
                </li>
                <li className="flex items-start gap-2">
                  <CheckCircle2 className="w-4 h-4 text-emerald-600 flex-shrink-0 mt-0.5" />
                  <span><strong>Ingesta Masiva CSV:</strong> Seeders y migraciones que procesan plantillas institucionales de forma atómica.</span>
                </li>
                <li className="flex items-start gap-2">
                  <CheckCircle2 className="w-4 h-4 text-emerald-600 flex-shrink-0 mt-0.5" />
                  <span><strong>Modelo ER Robusto:</strong> Estricta integridad referencial con claves foráneas e índices optimizados.</span>
                </li>
              </ul>
            </div>

            <div className="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
              <div className="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold mb-4">
                <Database className="w-5 h-5" />
              </div>
              <h3 className="text-base font-bold text-slate-900 mb-2">Seguridad y Multi-Tenancy</h3>
              <ul className="text-xs text-slate-600 space-y-2.5">
                <li className="flex items-start gap-2">
                  <CheckCircle2 className="w-4 h-4 text-emerald-600 flex-shrink-0 mt-0.5" />
                  <span><strong>Spatie Permissions:</strong> Roles diferenciados de Super Admin, Jefe de Área y Docente.</span>
                </li>
                <li className="flex items-start gap-2">
                  <CheckCircle2 className="w-4 h-4 text-emerald-600 flex-shrink-0 mt-0.5" />
                  <span><strong>Aislamiento Territorial:</strong> Filtrado dinámico por sede que previene fuga o visibilidad indebida de expedientes.</span>
                </li>
                <li className="flex items-start gap-2">
                  <CheckCircle2 className="w-4 h-4 text-emerald-600 flex-shrink-0 mt-0.5" />
                  <span><strong>ActivityLog Forense:</strong> Auditoría completa de modificaciones de expediente, ascensos y licencias.</span>
                </li>
                <li className="flex items-start gap-2">
                  <CheckCircle2 className="w-4 h-4 text-emerald-600 flex-shrink-0 mt-0.5" />
                  <span><strong>Pruebas Automatizadas:</strong> 49 pruebas pasadas con 372 aserciones para garantizar cero regresiones.</span>
                </li>
              </ul>
            </div>
          </div>
        </div>
      )}

      {/* TAB 2: Presentación Interactiva de 12 Láminas */}
      {activeTab === 'slides' && (
        <div className="space-y-6">
          {/* Slide Stage Card */}
          <div className="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-6 overflow-hidden">
            {/* Controls Bar */}
            <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100 mb-6">
              <div className="flex items-center gap-3">
                <span className="px-3 py-1 rounded-md bg-unerg-blue text-white text-xs font-bold">
                  Lámina {currentSlide.num} de 12
                </span>
                <span className="text-xs font-bold text-slate-500 uppercase tracking-wider">
                  {currentSlide.category}
                </span>
              </div>

              {/* Navigation Controls */}
              <div className="flex items-center gap-2">
                <button
                  onClick={handlePrevSlide}
                  className="p-2 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-colors"
                  title="Lámina anterior"
                >
                  <ChevronLeft className="w-4 h-4" />
                </button>
                <span className="text-xs font-semibold text-slate-600 px-2">
                  {currentSlide.num} / 12
                </span>
                <button
                  onClick={handleNextSlide}
                  className="p-2 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-colors"
                  title="Siguiente lámina"
                >
                  <ChevronRight className="w-4 h-4" />
                </button>

                <a
                  href={`/images/slides/slide-${currentSlide.num}.svg`}
                  target="_blank"
                  rel="noreferrer"
                  className="ml-2 inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors"
                  title="Abrir lámina en pestaña completa"
                >
                  <Maximize2 className="w-3.5 h-3.5" />
                  Ver Fullscreen
                </a>
              </div>
            </div>

            {/* Slide Stage Image */}
            <div className="relative rounded-xl border border-slate-200 bg-slate-50 overflow-hidden shadow-inner flex justify-center mb-6">
              <img
                src={`/images/slides/slide-${currentSlide.num}.svg`}
                alt={`Lámina ${currentSlide.num}: ${currentSlide.title}`}
                className="w-full h-auto max-h-[720px] object-contain transition-all duration-300"
              />
            </div>

            {/* Slide Summary Information */}
            <div className="bg-slate-50 rounded-xl p-5 border border-slate-200/80">
              <h2 className="text-lg font-bold text-slate-900 mb-1">
                {currentSlide.title}
              </h2>
              <p className="text-xs text-slate-500 font-medium mb-4">
                {currentSlide.subtitle}
              </p>
              
              <div className="space-y-2">
                <div className="text-[11px] uppercase tracking-wider text-slate-400 font-bold">
                  Puntos Clave Explicativos:
                </div>
                <div className="grid grid-cols-1 md:grid-cols-2 gap-2 text-xs text-slate-700">
                  {currentSlide.keyPoints.map((point, i) => (
                    <div key={i} className="flex items-start gap-2 bg-white p-2.5 rounded-lg border border-slate-200/60 shadow-xs">
                      <CheckCircle2 className="w-4 h-4 text-unerg-blue flex-shrink-0 mt-0.5" />
                      <span>{point}</span>
                    </div>
                  ))}
                </div>
              </div>
            </div>
          </div>

          {/* Slide Thumbnails Grid */}
          <div className="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <div className="flex items-center gap-2 mb-4">
              <Grid className="w-4 h-4 text-unerg-blue" />
              <h3 className="text-sm font-bold text-slate-900">
                Todas las Láminas del Proyecto (Navegación Rápida)
              </h3>
            </div>
            
            <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
              {SLIDES_DATA.map((slide, idx) => (
                <button
                  key={slide.num}
                  onClick={() => setCurrentSlideIndex(idx)}
                  className={`group text-left p-2 rounded-xl border transition-all ${
                    currentSlideIndex === idx
                      ? 'border-unerg-blue bg-blue-50/50 ring-2 ring-unerg-blue/30 shadow-sm'
                      : 'border-slate-200 hover:border-slate-300 hover:bg-slate-50'
                  }`}
                >
                  <div className="aspect-[16/9] rounded-lg overflow-hidden border border-slate-200/80 bg-slate-100 mb-2 relative">
                    <img
                      src={`/images/slides/slide-${slide.num}.svg`}
                      alt={slide.title}
                      className="w-full h-full object-cover group-hover:scale-105 transition-transform"
                    />
                    <div className="absolute top-1 left-1 bg-slate-900/80 text-white text-[10px] font-bold px-1.5 py-0.5 rounded">
                      #{slide.num}
                    </div>
                  </div>
                  <div className="text-[11px] font-bold text-slate-800 line-clamp-1">
                    {slide.title}
                  </div>
                  <div className="text-[10px] text-slate-500 line-clamp-1">
                    {slide.category}
                  </div>
                </button>
              ))}
            </div>
          </div>
        </div>
      )}
    </div>
  );
};
