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
    subtitle: 'Una arquitectura integral para la gestión de expedientes, escalafón docente y emisión documental con sello criptográfico.',
    category: 'Portada Oficial',
    keyPoints: [
      'Desarrollado para la Universidad Nacional Experimental de los Llanos Centrales Rómulo Gallegos (UNERG).',
      'Stack técnico oficial: Laravel 11, Filament v3, OpenAPI 3.0 y Licencia MIT.',
      'Autoría de la investigación: Frank Sousa.'
    ]
  },
  {
    num: 2,
    title: 'De la Fragmentación Documental a la Inteligencia Institucional',
    subtitle: 'Centralización del núcleo universitario en un único ecosistema digital automatizado',
    category: 'Ecosistema & Integridad',
    keyPoints: [
      'Superación de la fragmentación tradicional: Nómina, RRHH, Control de Estudios y Archivos físicos.',
      'Integridad 1:1 y 1:N: Modelado de dominio universitario exacto.',
      'Trazabilidad Forense: Auditoría exhaustiva con spatie/laravel-activitylog.',
      'Respuesta Inmediata: Flujos digitales en milisegundos.'
    ]
  },
  {
    num: 3,
    title: 'Anatomía del Expediente Integral 360°',
    subtitle: 'Sincronización simultánea de 4 gestores de relaciones mediante hooks Eloquent',
    category: 'Expediente 360°',
    keyPoints: [
      'Adscripción Institucional: Sedes, Áreas y Programas ($teacher->sites()).',
      'Escalafón Universitario: Historial cronológico ($teacher->category()).',
      'Carga Académica: Modalidad horaria y asignaciones ($teacher->dedication()).',
      'Permisos y Licencias: Ausencias y trámites ($teacher->permissions()).'
    ]
  },
  {
    num: 4,
    title: 'Validación Automatizada de Reglas de Negocio Universitarias',
    subtitle: 'Cruce del escalafón cronológico con modalidades de contratación e impedimento de inconsistencias',
    category: 'Reglas de Negocio',
    keyPoints: [
      'Matriz bidimensional: Categorías (Instructor a Titular) vs. Dedicaciones (TCV, MT, TC, EX).',
      'Validación Bloqueada para Instructor con Dedicación Exclusiva.',
      'Tope estricto de 40 horas semanales para dedicaciones a Tiempo Completo.',
      'Requisito obligatorio de Doctorado para ascenso a la categoría de Titular.'
    ]
  },
  {
    num: 5,
    title: 'El Ciclo de Vida Documental: Del Dato a la Certificación Oficial',
    subtitle: 'Generación automatizada de constancias en PDF con sello criptográfico institucional',
    category: 'Documentación Oficial',
    keyPoints: [
      '1. Evento: Disparado por la interfaz Filament / Web.',
      '2. Sincronización: Eloquent Hook actualiza de forma atómica las tablas del perfil.',
      '3. Certificación: Hoja membretada oficial UNERG con motor barryvdh/laravel-dompdf.',
      'Sello de seguridad criptográfico con código único: UNERG-REP-YYYY-XXXX.'
    ]
  },
  {
    num: 6,
    title: 'Arquitectura Multi-Inquilino: Aislamiento Territorial Dinámico',
    subtitle: 'Dynamic Eloquent Scopes para filtrado perimetral estricto a nivel de base de datos',
    category: 'Seguridad Territorial',
    keyPoints: [
      'Visibilidad Contextual: El panel adapta sus consultas SQL en milisegundos según el rol.',
      'Barrera de Sede: Un Jefe de Área visualiza exclusivamente los expedientes de su recinto geográfico.',
      'Prevención de Fugas: Filtrado estructural a nivel de ORM, imposible de evadir desde el frontend.'
    ]
  },
  {
    num: 7,
    title: 'Matriz de Control de Acceso Basado en Roles (RBAC)',
    subtitle: 'Gestión fluida y segura de múltiples roles concurrentes con spatie/laravel-permission',
    category: 'Control de Acceso',
    keyPoints: [
      'Super Administrador: Alcance global, edición total, gestión de usuarios con toggle is_active y reportes masivos.',
      'Jefe de Área: Alcance restringido por sede/área, aprobación/edición parcial y reportes de su jurisdicción.',
      'Docente Académico: Alcance exclusivo a su expediente personal, solo lectura y descarga de constancias propias.',
      'Soporte multi-rol para usuarios con doble función (docente y directivo).'
    ]
  },
  {
    num: 8,
    title: 'Interfaz Universal: Experiencia Multidispositivo (v2.0.0)',
    subtitle: 'Capa ergonómica y responsiva con Filament v3 y React/Tailwind',
    category: 'Experiencia de Usuario',
    keyPoints: [
      'Modo Tabla (Auditoría): Diseñado para monitores 4K y administradores que requieren alta densidad de datos.',
      'Modo Cuadrícula (Táctil): Optimizado para smartphones (hasta 320px) con barra inferior fija y cajones contextuales.',
      'Accesibilidad: Interfaz rápida diseñada para reducir la fatiga visual administrativa.'
    ]
  },
  {
    num: 9,
    title: 'Ingesta de Datos: Desde Archivos Planos a Integridad Relacional',
    subtitle: 'Pipeline de inyección estructurado mediante Data Factory y Seeders',
    category: 'Ingesta de Datos',
    keyPoints: [
      'users.csv: Hasheo de contraseñas y asignación perimetral de roles.',
      'teachers.csv & categories.csv: Expedientes demográficos e histórico de ascensos.',
      'dedications.csv & sites.csv: Asignaciones de carga horaria y adscripción territorial.',
      'Garantía de integridad referencial y claves foráneas en bases de datos relacionales.'
    ]
  },
  {
    num: 10,
    title: 'Interoperabilidad Estándar: API RESTful OpenAPI 3.0',
    subtitle: 'Fuente de la Verdad académica para integración con nómina y control de estudios',
    category: 'Interoperabilidad & API',
    keyPoints: [
      'Endpoints oficiales v1: Catálogo paginado en GET /api/v1/teachers y verificación en GET /api/v1/reports.',
      'Protección estricta de PII: Sanitización de datos sensibles previa a la serialización JSON.',
      'Swagger UI interactivo integrado, generado dinámicamente mediante anotaciones @OA.'
    ]
  },
  {
    num: 11,
    title: 'Arquitectura en Tres Capas: Separación Estricta de Responsabilidades',
    subtitle: 'Diseño desacoplado para alta mantenibilidad y escalabilidad a largo plazo',
    category: 'Arquitectura de Software',
    keyPoints: [
      'Capa de Presentación: Panel Filament v3, vistas Blade y motor DomPDF.',
      'Lógica de Negocio y Seguridad: Núcleo Laravel 11, Spatie Roles & Permisos y Modelos Eloquent.',
      'Persistencia de Datos: MySQL / MariaDB con relaciones 1:1 y 1:N.',
      'Ventaja: Facilita incorporación de futuros módulos sin alterar el código base existente.'
    ]
  },
  {
    num: 12,
    title: 'Fiabilidad Demostrada y Código Abierto',
    subtitle: 'Validación por pruebas automatizadas exhaustivas y liberación bajo Licencia MIT',
    category: 'Calidad & Comunidad',
    keyPoints: [
      '100% de éxito: 49 pruebas de integración y 372 aserciones validadas con Pest / PHPUnit.',
      'Verificación de autenticación, políticas multi-inquilino y generación de documentos PDF.',
      'Código Abierto bajo Licencia MIT: Desacoplado y listo para adopción en otras universidades.'
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
                  href="/images/Gestion_docente_universitaria_SIGEDOR.svg"
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
            <div className="relative rounded-xl border border-slate-200 bg-slate-900 overflow-hidden shadow-inner flex justify-center">
              <img
                src="/images/Gestion_docente_universitaria_SIGEDOR.svg"
                alt="SIGEDOR - El Motor Digital de la Gestión Docente Universitaria"
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
