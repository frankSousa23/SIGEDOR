import fs from 'fs';
import path from 'path';

const outDir = path.resolve('public/images');
const slidesDir = path.resolve('public/images/slides');

if (!fs.existsSync(outDir)) fs.mkdirSync(outDir, { recursive: true });
if (!fs.existsSync(slidesDir)) fs.mkdirSync(slidesDir, { recursive: true });

// 1. Main Infographic SVG (1920x1080)
const infographicSvg = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1920 1080" width="1920" height="1080">
  <defs>
    <linearGradient id="bgGrad" x1="0" y1="0" x2="0" y2="1">
      <stop offset="0%" stop-color="#EFF6FF" />
      <stop offset="50%" stop-color="#F8FAFC" />
      <stop offset="100%" stop-color="#F1F5F9" />
    </linearGradient>
    <linearGradient id="headerGrad" x1="0" y1="0" x2="1" y2="0">
      <stop offset="0%" stop-color="#003366" />
      <stop offset="100%" stop-color="#1E40AF" />
    </linearGradient>
    <linearGradient id="pillGrad" x1="0" y1="0" x2="1" y2="0">
      <stop offset="0%" stop-color="#1E3A8A" />
      <stop offset="100%" stop-color="#2563EB" />
    </linearGradient>
    <filter id="shadow" x="-5%" y="-5%" width="110%" height="110%">
      <feDropShadow dx="0" dy="4" stdDeviation="6" flood-opacity="0.08" />
    </filter>
  </defs>

  <!-- Background -->
  <rect width="1920" height="1080" fill="url(#bgGrad)" />

  <!-- Grid overlay -->
  <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
    <path d="M 40 0 L 0 0 0 40" fill="none" stroke="#E2E8F0" stroke-width="0.8" opacity="0.6"/>
  </pattern>
  <rect width="1920" height="1080" fill="url(#grid)" />

  <!-- Top Title Banner -->
  <text x="960" y="60" text-anchor="middle" font-family="system-ui, -apple-system, sans-serif" font-size="44" font-weight="900" fill="#003366" letter-spacing="-0.5">
    <tspan fill="#003366">SIGEDOR: </tspan><tspan fill="#0F172A">Transformación Digital en la Gestión Docente Universitaria</tspan>
  </text>
  <text x="960" y="98" text-anchor="middle" font-family="system-ui, -apple-system, sans-serif" font-size="24" font-weight="700" fill="#334155" letter-spacing="0.5">
    Visión Integral del Sistema
  </text>
  <line x1="200" y1="120" x2="1720" y2="120" stroke="#CBD5E1" stroke-width="2" stroke-linecap="round"/>

  <!-- COLUMN 1: Capacidades del Sistema -->
  <g transform="translate(60, 140)">
    <rect width="420" height="860" rx="16" fill="#F8FAFC" stroke="#E2E8F0" stroke-width="2" filter="url(#shadow)" />
    <rect x="50" y="-18" width="320" height="42" rx="10" fill="url(#pillGrad)" filter="url(#shadow)" />
    <text x="210" y="9" text-anchor="middle" font-family="system-ui, sans-serif" font-size="18" font-weight="800" fill="#FFFFFF">
      Capacidades del Sistema
    </text>

    <!-- Item 1: Gestión Integral de Expedientes -->
    <g transform="translate(24, 45)">
      <rect width="372" height="175" rx="12" fill="#FFFFFF" stroke="#E2E8F0" stroke-width="1.5" />
      <circle cx="45" cy="45" r="26" fill="#DBEAFE" />
      <text x="85" y="38" font-family="system-ui, sans-serif" font-size="18" font-weight="800" fill="#0F172A">Gestión Integral de</text>
      <text x="85" y="60" font-family="system-ui, sans-serif" font-size="18" font-weight="800" fill="#0F172A">Expedientes</text>
      <text x="20" y="100" font-family="system-ui, sans-serif" font-size="14.5" fill="#475569">
        <tspan x="20" dy="0">Centraliza datos personales, cédulas,</tspan>
        <tspan x="20" dy="22">contactos y vinculación institucional</tspan>
        <tspan x="20" dy="22">con sedes y áreas de conocimiento</tspan>
        <tspan x="20" dy="22">específicas.</tspan>
      </text>
    </g>

    <!-- Item 2: Control de Escalafón Universitario -->
    <g transform="translate(24, 245)">
      <rect width="372" height="175" rx="12" fill="#FFFFFF" stroke="#E2E8F0" stroke-width="1.5" />
      <circle cx="45" cy="45" r="26" fill="#FEF3C7" />
      <text x="85" y="38" font-family="system-ui, sans-serif" font-size="18" font-weight="800" fill="#0F172A">Control de Escalafón</text>
      <text x="85" y="60" font-family="system-ui, sans-serif" font-size="18" font-weight="800" fill="#0F172A">Universitario</text>
      <text x="20" y="100" font-family="system-ui, sans-serif" font-size="14.5" fill="#475569">
        <tspan x="20" dy="0">Seguimiento cronológico de ascensos</tspan>
        <tspan x="20" dy="22">(Instructor a Titular) con validación</tspan>
        <tspan x="20" dy="22">automática de reglas y registro de</tspan>
        <tspan x="20" dy="22">títulos de posgrado.</tspan>
      </text>
    </g>

    <!-- Item 3: Carga Académica y Dedicación -->
    <g transform="translate(24, 445)">
      <rect width="372" height="175" rx="12" fill="#FFFFFF" stroke="#E2E8F0" stroke-width="1.5" />
      <circle cx="45" cy="45" r="26" fill="#E0E7FF" />
      <text x="85" y="38" font-family="system-ui, sans-serif" font-size="18" font-weight="800" fill="#0F172A">Carga Académica y</text>
      <text x="85" y="60" font-family="system-ui, sans-serif" font-size="18" font-weight="800" fill="#0F172A">Dedicación</text>
      <text x="20" y="100" font-family="system-ui, sans-serif" font-size="14.5" fill="#475569">
        <tspan x="20" dy="0">Gestiona modalidades de contratación</tspan>
        <tspan x="20" dy="22">(TCV, MT, TC, EX) y controla horas</tspan>
        <tspan x="20" dy="22">semanales, secciones y unidades de</tspan>
        <tspan x="20" dy="22">crédito.</tspan>
      </text>
    </g>

    <!-- Item 4: Reportes Automatizados en PDF -->
    <g transform="translate(24, 645)">
      <rect width="372" height="185" rx="12" fill="#FFFFFF" stroke="#E2E8F0" stroke-width="1.5" />
      <circle cx="45" cy="45" r="26" fill="#FEE2E2" />
      <text x="85" y="38" font-family="system-ui, sans-serif" font-size="18" font-weight="800" fill="#0F172A">Reportes</text>
      <text x="85" y="60" font-family="system-ui, sans-serif" font-size="18" font-weight="800" fill="#0F172A">Automatizados en PDF</text>
      <text x="20" y="100" font-family="system-ui, sans-serif" font-size="14.5" fill="#475569">
        <tspan x="20" dy="0">Emisión inmediata de constancias de</tspan>
        <tspan x="20" dy="22">trabajo, memorandos y expedientes</tspan>
        <tspan x="20" dy="22">con códigos de verificación</tspan>
        <tspan x="20" dy="22">institucional únicos.</tspan>
      </text>
    </g>
  </g>

  <!-- COLUMN 2: Ecosistema Tecnológico -->
  <g transform="translate(520, 140)">
    <rect width="780" height="860" rx="16" fill="#F8FAFC" stroke="#E2E8F0" stroke-width="2" filter="url(#shadow)" />
    <rect x="230" y="-18" width="320" height="42" rx="10" fill="url(#pillGrad)" filter="url(#shadow)" />
    <text x="390" y="9" text-anchor="middle" font-family="system-ui, sans-serif" font-size="18" font-weight="800" fill="#FFFFFF">
      Ecosistema Tecnológico
    </text>

    <!-- Top Card: Tech Stack -->
    <g transform="translate(30, 45)">
      <rect width="720" height="480" rx="14" fill="#FFFFFF" stroke="#E2E8F0" stroke-width="1.5" />

      <!-- Left Subcard: Laravel 11 + Filament v3 -->
      <g transform="translate(30, 30)">
        <rect width="310" height="190" rx="10" fill="#F8FAFC" stroke="#E2E8F0" stroke-width="1"/>
        <text x="90" y="42" font-family="system-ui, sans-serif" font-size="17" font-weight="800" fill="#0F172A">Laravel 11 +</text>
        <text x="90" y="64" font-family="system-ui, sans-serif" font-size="17" font-weight="800" fill="#0F172A">Filament v3</text>
        <text x="20" y="100" font-family="system-ui, sans-serif" font-size="14" fill="#475569">
          <tspan x="20" dy="0">El núcleo del sistema utiliza el</tspan>
          <tspan x="20" dy="21">framework PHP más robusto</tspan>
          <tspan x="20" dy="21">junto a paneles de administración</tspan>
          <tspan x="20" dy="21">interactivos y modernos.</tspan>
        </text>
      </g>

      <!-- Right Subcard: API RESTful OpenAPI 3.0 -->
      <g transform="translate(380, 30)">
        <rect width="310" height="190" rx="10" fill="#F8FAFC" stroke="#E2E8F0" stroke-width="1"/>
        <text x="90" y="42" font-family="system-ui, sans-serif" font-size="17" font-weight="800" fill="#0F172A">API RESTful</text>
        <text x="90" y="64" font-family="system-ui, sans-serif" font-size="17" font-weight="800" fill="#0F172A">OpenAPI 3.0</text>
        <text x="20" y="100" font-family="system-ui, sans-serif" font-size="14" fill="#475569">
          <tspan x="20" dy="0">Documentación completa</tspan>
          <tspan x="20" dy="21">mediante Swagger para facilitar</tspan>
          <tspan x="20" dy="21">la integración con otros sistemas</tspan>
          <tspan x="20" dy="21">y servicios externos.</tspan>
        </text>
      </g>

      <!-- Ingesta de Datos Masiva -->
      <g transform="translate(30, 245)">
        <rect width="660" height="200" rx="10" fill="#F8FAFC" stroke="#E2E8F0" stroke-width="1"/>
        <text x="110" y="48" font-family="system-ui, sans-serif" font-size="20" font-weight="800" fill="#0F172A">Ingesta de Datos Masiva</text>
        <text x="110" y="72" font-family="system-ui, sans-serif" font-size="14" font-weight="600" fill="#D97706">Pipeline automatizado desde archivos CSV a persistencia relacional</text>
        <text x="35" y="115" font-family="system-ui, sans-serif" font-size="15" fill="#475569">
          <tspan x="35" dy="0">Pipeline automatizado para la carga de usuarios, docentes y catálogos institucionales</tspan>
          <tspan x="35" dy="24">mediante archivos CSV estructurados con validación atómica y aprobación administrativa.</tspan>
        </text>
      </g>
    </g>

    <!-- Bottom Card: Estructura de Datos (Modelo ER) -->
    <g transform="translate(30, 550)">
      <rect width="720" height="280" rx="14" fill="#FFFFFF" stroke="#E2E8F0" stroke-width="1.5" />
      <rect x="180" y="-14" width="360" height="34" rx="8" fill="#1E3A8A" />
      <text x="360" y="9" text-anchor="middle" font-family="system-ui, sans-serif" font-size="16" font-weight="800" fill="#FFFFFF">
        Estructura de Datos (Modelo ER)
      </text>

      <g transform="translate(45, 50)">
        <rect x="30" y="20" width="130" height="46" rx="8" fill="#DBEAFE" stroke="#3B82F6" stroke-width="2"/>
        <text x="95" y="49" text-anchor="middle" font-family="system-ui, sans-serif" font-size="16" font-weight="800" fill="#1E3A8A">Users</text>

        <rect x="250" y="10" width="140" height="66" rx="10" fill="#0F172A" stroke="#38BDF8" stroke-width="2.5" />
        <text x="320" y="42" text-anchor="middle" font-family="system-ui, sans-serif" font-size="17" font-weight="900" fill="#FFFFFF">Teachers</text>

        <rect x="480" y="20" width="130" height="46" rx="8" fill="#DBEAFE" stroke="#3B82F6" stroke-width="2"/>
        <text x="545" y="49" text-anchor="middle" font-family="system-ui, sans-serif" font-size="16" font-weight="800" fill="#1E3A8A">Sedes</text>

        <rect x="30" y="120" width="130" height="46" rx="8" fill="#E0E7FF" stroke="#6366F1" stroke-width="2"/>
        <text x="95" y="149" text-anchor="middle" font-family="system-ui, sans-serif" font-size="16" font-weight="800" fill="#312E81">Áreas</text>

        <rect x="250" y="120" width="140" height="46" rx="8" fill="#E0E7FF" stroke="#6366F1" stroke-width="2"/>
        <text x="320" y="149" text-anchor="middle" font-family="system-ui, sans-serif" font-size="16" font-weight="800" fill="#312E81">Programas</text>

        <rect x="480" y="120" width="130" height="46" rx="8" fill="#FEF3C7" stroke="#F59E0B" stroke-width="2"/>
        <text x="545" y="149" text-anchor="middle" font-family="system-ui, sans-serif" font-size="16" font-weight="800" fill="#78350F">Categorías</text>
      </g>

      <text x="360" y="250" text-anchor="middle" font-family="system-ui, sans-serif" font-size="14.5" fill="#475569" font-weight="500">
        El sistema vincula Usuarios y Docentes con Sedes, Áreas, Programas y Categorías para una integridad referencial total.
      </text>
    </g>
  </g>

  <!-- COLUMN 3: Seguridad y Arquitectura -->
  <g transform="translate(1340, 140)">
    <rect width="520" height="860" rx="16" fill="#F8FAFC" stroke="#E2E8F0" stroke-width="2" filter="url(#shadow)" />
    <rect x="100" y="-18" width="320" height="42" rx="10" fill="url(#pillGrad)" filter="url(#shadow)" />
    <text x="260" y="9" text-anchor="middle" font-family="system-ui, sans-serif" font-size="18" font-weight="800" fill="#FFFFFF">
      Seguridad y Arquitectura
    </text>

    <!-- Top Card: RBAC & 3-Tier Architecture -->
    <g transform="translate(24, 45)">
      <rect width="472" height="480" rx="14" fill="#FFFFFF" stroke="#E2E8F0" stroke-width="1.5" />

      <g transform="translate(20, 20)">
        <text x="0" y="32" font-family="system-ui, sans-serif" font-size="17" font-weight="800" fill="#0F172A">Control de Acceso Granular (RBAC)</text>
      </g>

      <g transform="translate(20, 160)">
        <rect width="432" height="150" rx="10" fill="#F8FAFC" stroke="#E2E8F0" stroke-width="1"/>
        <text x="20" y="38" font-family="system-ui, sans-serif" font-size="15" font-weight="800" fill="#0F172A">Arquitectura Multi-Sede</text>
        <text x="20" y="70" font-family="system-ui, sans-serif" font-size="13.5" fill="#475569">
          <tspan x="20" dy="0">Las consultas a la base de datos se adaptan</tspan>
          <tspan x="20" dy="20">dinámicamente según el rol; los Jefes de Área solo</tspan>
          <tspan x="20" dy="20">visualizan registros vinculados a su sede específica.</tspan>
        </text>
      </g>

      <g transform="translate(20, 325)">
        <rect width="432" height="135" rx="10" fill="#F0FDF4" stroke="#BBF7D0" stroke-width="1"/>
        <text x="20" y="30" font-family="system-ui, sans-serif" font-size="15" font-weight="800" fill="#166534">Auditoría Completa (Logs)</text>
        <text x="20" y="55" font-family="system-ui, sans-serif" font-size="13" fill="#15803D">
          <tspan x="20" dy="0">Registro inmutable y detallado de cada acción mediante</tspan>
          <tspan x="20" dy="20">spatie/laravel-activitylog para garantizar la</tspan>
          <tspan x="20" dy="20">transparencia, trazabilidad y control de ascensos.</tspan>
        </text>
      </g>
    </g>

    <!-- Bottom Card: Roles Preconfigurados y Alcance -->
    <g transform="translate(24, 550)">
      <rect width="472" height="280" rx="14" fill="#FFFFFF" stroke="#E2E8F0" stroke-width="1.5" />
      <text x="236" y="32" text-anchor="middle" font-family="system-ui, sans-serif" font-size="16" font-weight="800" fill="#0F172A">
        Roles Preconfigurados y Alcance
      </text>

      <g transform="translate(16, 50)">
        <rect width="440" height="32" rx="6" fill="#F1F5F9" />
        <text x="20" y="21" font-family="system-ui, sans-serif" font-size="12" font-weight="800" fill="#475569">Rol</text>
        <text x="160" y="21" font-family="system-ui, sans-serif" font-size="12" font-weight="800" fill="#475569">Alcance de Gestión</text>

        <g transform="translate(0, 38)">
          <rect width="440" height="52" rx="4" fill="#F8FAFC" />
          <text x="20" y="32" font-family="system-ui, sans-serif" font-size="13" font-weight="800" fill="#1E40AF">Super Admin</text>
          <text x="160" y="24" font-family="system-ui, sans-serif" font-size="12" fill="#334155">
            <tspan x="160" dy="0">Acceso total, gestión de usuarios, sedes,</tspan>
            <tspan x="160" dy="16">reportes y configuraciones globales.</tspan>
          </text>
        </g>

        <g transform="translate(0, 96)">
          <rect width="440" height="52" rx="4" fill="#FFFFFF" stroke="#F1F5F9" stroke-width="1"/>
          <text x="20" y="32" font-family="system-ui, sans-serif" font-size="13" font-weight="800" fill="#B45309">Jefe de Área</text>
          <text x="160" y="24" font-family="system-ui, sans-serif" font-size="12" fill="#334155">
            <tspan x="160" dy="0">Supervisión limitada a docentes de su</tspan>
            <tspan x="160" dy="16">sede y área académica correspondiente.</tspan>
          </text>
        </g>

        <g transform="translate(0, 154)">
          <rect width="440" height="52" rx="4" fill="#F8FAFC" />
          <text x="20" y="32" font-family="system-ui, sans-serif" font-size="13" font-weight="800" fill="#4338CA">Docente</text>
          <text x="160" y="24" font-family="system-ui, sans-serif" font-size="12" fill="#334155">
            <tspan x="160" dy="0">Consulta personal de escalafón, permisos</tspan>
            <tspan x="160" dy="16">y descarga de reportes propios.</tspan>
          </text>
        </g>
      </g>
    </g>
  </g>

  <!-- FOOTER -->
  <line x1="60" y1="1025" x2="1860" y2="1025" stroke="#CBD5E1" stroke-width="1.5" />
  <text x="960" y="1055" text-anchor="middle" font-family="system-ui, sans-serif" font-size="16" font-weight="700" fill="#475569">
    Licencia MIT &bull; Autoría: Frank Sousa &bull; Universidad Nacional Experimental de los Llanos Centrales Rómulo Gallegos (UNERG)
  </text>
</svg>`;


// Common helper for slide template
function wrapSlide(title, subtitle, content, slideNum) {
  return `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1920 1080" width="1920" height="1080">
  <defs>
    <linearGradient id="slideBg" x1="0" y1="0" x2="0" y2="1">
      <stop offset="0%" stop-color="#FDFBF7" />
      <stop offset="100%" stop-color="#F4EFE6" />
    </linearGradient>
    <filter id="cardShadow" x="-5%" y="-5%" width="110%" height="110%">
      <feDropShadow dx="0" dy="6" stdDeviation="8" flood-opacity="0.06" />
    </filter>
  </defs>
  <rect width="1920" height="1080" fill="url(#slideBg)" />

  <!-- Isometric grid decorative lines -->
  <g opacity="0.15" stroke="#94A3B8" stroke-width="1">
    <line x1="0" y1="200" x2="1920" y2="200"/>
    <line x1="0" y1="960" x2="1920" y2="960"/>
    <line x1="160" y1="0" x2="160" y2="1080"/>
    <line x1="1760" y1="0" x2="1760" y2="1080"/>
  </g>

  <!-- Slide Header -->
  <g transform="translate(100, 80)">
    <text x="0" y="45" font-family="'Plus Jakarta Sans', system-ui, sans-serif" font-size="44" font-weight="900" fill="#0F172A" letter-spacing="-0.5">
      ${title}
    </text>
    ${subtitle ? `<text x="0" y="85" font-family="'Plus Jakarta Sans', system-ui, sans-serif" font-size="22" font-weight="600" fill="#64748B">
      ${subtitle}
    </text>` : ''}
  </g>

  <!-- Content -->
  ${content}

  <!-- Slide Footer -->
  <g transform="translate(100, 1030)">
    <text x="0" y="0" font-family="system-ui, sans-serif" font-size="16" font-weight="600" fill="#94A3B8">
      SIGEDOR &bull; Proyecto de Investigación &bull; Frank Sousa
    </text>
    <text x="1720" y="0" text-anchor="end" font-family="system-ui, sans-serif" font-size="16" font-weight="700" fill="#64748B">
      ${slideNum} / 12
    </text>
  </g>
</svg>`;
}

// SLIDE 1
const slide1 = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1920 1080" width="1920" height="1080">
  <defs>
    <linearGradient id="s1Bg" x1="0" y1="0" x2="0" y2="1">
      <stop offset="0%" stop-color="#FAF8F5" />
      <stop offset="100%" stop-color="#F2ECE4" />
    </linearGradient>
    <filter id="s1CardShadow" x="-10%" y="-10%" width="120%" height="120%">
      <feDropShadow dx="0" dy="16" stdDeviation="24" flood-color="#0F172A" flood-opacity="0.08" />
    </filter>
  </defs>
  <rect width="1920" height="1080" fill="url(#s1Bg)" />

  <!-- Isometric Server Graphic Background -->
  <g opacity="0.3" transform="translate(100, 100)">
    <path d="M 200 400 L 400 300 L 600 400 L 400 500 Z" fill="none" stroke="#CBD5E1" stroke-width="2"/>
    <path d="M 1200 300 L 1400 200 L 1600 300 L 1400 400 Z" fill="none" stroke="#CBD5E1" stroke-width="2"/>
  </g>

  <!-- Center Card -->
  <g transform="translate(420, 220)">
    <rect width="1080" height="620" rx="32" fill="#FFFFFF" stroke="#E2E8F0" stroke-width="2" filter="url(#s1CardShadow)" />
    
    <text x="540" y="220" text-anchor="middle" font-family="'Plus Jakarta Sans', system-ui, sans-serif" font-size="110" font-weight="900" fill="#003366" letter-spacing="-2">
      SIGEDOR
    </text>
    
    <text x="540" y="320" text-anchor="middle" font-family="'Plus Jakarta Sans', system-ui, sans-serif" font-size="34" font-weight="800" fill="#1E293B">
      El Motor Digital del Control Académico Universitario
    </text>

    <!-- Badges pill -->
    <g transform="translate(190, 420)">
      <rect width="700" height="64" rx="32" fill="#FFF1F2" stroke="#FDA4AF" stroke-width="2"/>
      <text x="350" y="41" text-anchor="middle" font-family="'Plus Jakarta Sans', system-ui, sans-serif" font-size="22" font-weight="700" fill="#9F1239">
        Laravel 11 &bull; Filament v3 &bull; OpenAPI 3.0 &bull; Licencia MIT
      </text>
    </g>

    <text x="540" y="550" text-anchor="middle" font-family="system-ui, sans-serif" font-size="18" font-weight="600" fill="#64748B">
      Universidad Nacional Experimental de los Llanos Centrales Rómulo Gallegos (UNERG)
    </text>
  </g>

  <g transform="translate(100, 1030)">
    <text x="0" y="0" font-family="system-ui, sans-serif" font-size="16" font-weight="600" fill="#94A3B8">
      Autoría: Frank Sousa &bull; 1 / 12
    </text>
  </g>
</svg>`;

// SLIDE 2: Un ecosistema centralizado para la administración académica
const slide2 = wrapSlide(
  'Un ecosistema centralizado para la administración académica',
  'Interconexión funcional alrededor del núcleo Laravel 11',
  `<g transform="translate(100, 240)">
    <!-- Central Hub -->
    <circle cx="860" cy="380" r="140" fill="#EF4444" filter="url(#cardShadow)"/>
    <circle cx="860" cy="380" r="115" fill="#DC2626"/>
    <text x="860" y="365" text-anchor="middle" font-family="system-ui, sans-serif" font-size="32" font-weight="900" fill="#FFFFFF">Núcleo</text>
    <text x="860" y="405" text-anchor="middle" font-family="system-ui, sans-serif" font-size="32" font-weight="900" fill="#FFFFFF">Laravel 11</text>

    <!-- Connecting lines -->
    <line x1="860" y1="240" x2="860" y2="150" stroke="#EF4444" stroke-width="3" stroke-dasharray="6,6"/>
    <line x1="1000" y1="380" x2="1140" y2="380" stroke="#EF4444" stroke-width="3" stroke-dasharray="6,6"/>
    <line x1="860" y1="520" x2="860" y2="610" stroke="#EF4444" stroke-width="3" stroke-dasharray="6,6"/>
    <line x1="720" y1="380" x2="580" y2="380" stroke="#EF4444" stroke-width="3" stroke-dasharray="6,6"/>

    <!-- Box Top: Gestión de Expedientes -->
    <g transform="translate(630, 20)">
      <rect width="460" height="130" rx="16" fill="#FFFFFF" stroke="#E2E8F0" stroke-width="2" filter="url(#cardShadow)"/>
      <text x="40" y="50" font-family="system-ui, sans-serif" font-size="24" font-weight="900" fill="#0F172A">Gestión de Expedientes</text>
      <text x="40" y="85" font-family="system-ui, sans-serif" font-size="18" fill="#475569">Datos personales e institucionales unificados.</text>
    </g>

    <!-- Box Right: Motor de Escalafón & Carga -->
    <g transform="translate(1140, 315)">
      <rect width="500" height="130" rx="16" fill="#FFFFFF" stroke="#E2E8F0" stroke-width="2" filter="url(#cardShadow)"/>
      <text x="40" y="50" font-family="system-ui, sans-serif" font-size="24" font-weight="900" fill="#0F172A">Motor de Escalafón &amp; Carga</text>
      <text x="40" y="85" font-family="system-ui, sans-serif" font-size="18" fill="#475569">Reglas de negocio para ascensos y control horario.</text>
    </g>

    <!-- Box Bottom: Control Multi-Inquilino (Spatie) -->
    <g transform="translate(630, 610)">
      <rect width="460" height="130" rx="16" fill="#FFFFFF" stroke="#E2E8F0" stroke-width="2" filter="url(#cardShadow)"/>
      <text x="40" y="50" font-family="system-ui, sans-serif" font-size="24" font-weight="900" fill="#0F172A">Control Multi-Inquilino (Spatie)</text>
      <text x="40" y="85" font-family="system-ui, sans-serif" font-size="18" fill="#475569">Aislamiento de datos territorial por Sedes y Áreas.</text>
    </g>

    <!-- Box Left: Emisión Oficial (DomPDF) -->
    <g transform="translate(80, 315)">
      <rect width="500" height="130" rx="16" fill="#FFFFFF" stroke="#E2E8F0" stroke-width="2" filter="url(#cardShadow)"/>
      <text x="40" y="50" font-family="system-ui, sans-serif" font-size="24" font-weight="900" fill="#0F172A">Emisión Oficial (DomPDF)</text>
      <text x="40" y="85" font-family="system-ui, sans-serif" font-size="18" fill="#475569">Memorandos y constancias con verificación criptográfica.</text>
    </g>

    <!-- Bottom Institutional note -->
    <g transform="translate(1140, 620)">
      <rect width="480" height="110" rx="14" fill="#FEF3C7" stroke="#F59E0B" stroke-width="1.5"/>
      <text x="30" y="45" font-family="system-ui, sans-serif" font-size="17" font-weight="800" fill="#92400E">Diseñado para la UNERG</text>
      <text x="30" y="75" font-family="system-ui, sans-serif" font-size="15" fill="#78350F">Universidad Rómulo Gallegos y escalable a cualquier institución.</text>
    </g>
  </g>`,
  2
);

// SLIDE 3: El Expediente Integral 360° sincroniza la vida académica del docente
const slide3 = wrapSlide(
  'El Expediente Integral 360° sincroniza la vida académica del docente',
  'Sincronización atómica garantizada mediante hooks de ciclo de vida de Eloquent',
  `<g transform="translate(100, 220)">
    <!-- Central Node -->
    <circle cx="860" cy="400" r="160" fill="#F8FAFC" stroke="#003366" stroke-width="4" filter="url(#cardShadow)"/>
    <text x="860" y="385" text-anchor="middle" font-family="system-ui, sans-serif" font-size="34" font-weight="900" fill="#003366">Perfil</text>
    <text x="860" y="425" text-anchor="middle" font-family="system-ui, sans-serif" font-size="34" font-weight="900" fill="#003366">Docente</text>
    <text x="860" y="465" text-anchor="middle" font-family="system-ui, sans-serif" font-size="20" font-weight="700" fill="#D97706">Unificado</text>

    <!-- Node 1: Top Right: Adscripción Institucional -->
    <g transform="translate(1180, 100)">
      <rect width="480" height="140" rx="16" fill="#FFFFFF" stroke="#E2E8F0" stroke-width="2" filter="url(#cardShadow)"/>
      <circle cx="-30" cy="70" r="16" fill="#F59E0B"/>
      <text x="30" y="50" font-family="system-ui, sans-serif" font-size="22" font-weight="900" fill="#0F172A">Adscripción Institucional</text>
      <text x="30" y="85" font-family="system-ui, sans-serif" font-size="17" fill="#475569">Sede territorial, Área de conocimiento y Programas.</text>
    </g>

    <!-- Node 2: Mid Right: Escalafón Universitario -->
    <g transform="translate(1180, 420)">
      <rect width="480" height="140" rx="16" fill="#FFFFFF" stroke="#E2E8F0" stroke-width="2" filter="url(#cardShadow)"/>
      <circle cx="-30" cy="70" r="16" fill="#F59E0B"/>
      <text x="30" y="50" font-family="system-ui, sans-serif" font-size="22" font-weight="900" fill="#0F172A">Escalafón Universitario</text>
      <text x="30" y="85" font-family="system-ui, sans-serif" font-size="17" fill="#475569">Historial cronológico y validación automática de categoría vigente.</text>
    </g>

    <!-- Node 3: Bottom Left: Carga Horaria -->
    <g transform="translate(80, 560)">
      <rect width="480" height="140" rx="16" fill="#FFFFFF" stroke="#E2E8F0" stroke-width="2" filter="url(#cardShadow)"/>
      <circle cx="510" cy="70" r="16" fill="#F59E0B"/>
      <text x="30" y="50" font-family="system-ui, sans-serif" font-size="22" font-weight="900" fill="#0F172A">Carga Horaria</text>
      <text x="30" y="85" font-family="system-ui, sans-serif" font-size="17" fill="#475569">Modalidad de contratación, horas semanales y cargos directivos.</text>
    </g>

    <!-- Node 4: Top Left: Permisos y Reportes -->
    <g transform="translate(80, 200)">
      <rect width="480" height="140" rx="16" fill="#FFFFFF" stroke="#E2E8F0" stroke-width="2" filter="url(#cardShadow)"/>
      <circle cx="510" cy="70" r="16" fill="#F59E0B"/>
      <text x="30" y="50" font-family="system-ui, sans-serif" font-size="22" font-weight="900" fill="#0F172A">Permisos y Reportes</text>
      <text x="30" y="85" font-family="system-ui, sans-serif" font-size="17" fill="#475569">Historial de licencias e informes emitidos.</text>
    </g>

    <!-- Orbit rings -->
    <circle cx="860" cy="400" r="280" fill="none" stroke="#CBD5E1" stroke-width="2" stroke-dasharray="8,8"/>

    <!-- Bottom Tech Pill -->
    <g transform="translate(1220, 680)">
      <rect width="440" height="80" rx="12" fill="#EFF6FF" stroke="#3B82F6" stroke-width="1.5"/>
      <text x="30" y="35" font-family="system-ui, sans-serif" font-size="15" font-weight="800" fill="#1D4ED8">Sincronización atómica (technica)</text>
      <text x="30" y="58" font-family="system-ui, sans-serif" font-size="13" fill="#1E40AF">Garantizada mediante hooks de ciclo de vida de Eloquent.</text>
    </g>
  </g>`,
  3
);

// SLIDE 4: Automatización de las reglas de negocio para la carrera universitaria
const slide4 = wrapSlide(
  'Automatización de las reglas de negocio para la carrera universitaria',
  'Matriz bidimensional de Escalafón vs. Dedicación Horaria UNERG',
  `<g transform="translate(120, 220)">
    <!-- Matrix Table -->
    <g transform="translate(260, 60)">
      <!-- Header row -->
      <rect x="0" y="0" width="1050" height="50" fill="#F1F5F9" stroke="#CBD5E1"/>
      <text x="525" y="32" text-anchor="middle" font-family="system-ui, sans-serif" font-size="18" font-weight="800" fill="#334155">Dedicación Horaria</text>

      <!-- Subheaders -->
      <g transform="translate(0, 50)">
        <rect x="0" y="0" width="220" height="45" fill="#E2E8F0" stroke="#CBD5E1"/>
        <text x="110" y="28" text-anchor="middle" font-family="system-ui, sans-serif" font-size="14" font-weight="800" fill="#0F172A">Categorías Escalafón</text>

        <rect x="220" y="0" width="200" height="45" fill="#F8FAFC" stroke="#CBD5E1"/>
        <text x="320" y="28" text-anchor="middle" font-family="system-ui, sans-serif" font-size="14" font-weight="700" fill="#334155">Tiempo Convencional (TCV)</text>

        <rect x="420" y="0" width="200" height="45" fill="#F8FAFC" stroke="#CBD5E1"/>
        <text x="520" y="28" text-anchor="middle" font-family="system-ui, sans-serif" font-size="14" font-weight="700" fill="#334155">Medio Tiempo (MT)</text>

        <rect x="620" y="0" width="215" height="45" fill="#F8FAFC" stroke="#CBD5E1"/>
        <text x="727" y="28" text-anchor="middle" font-family="system-ui, sans-serif" font-size="14" font-weight="700" fill="#334155">Tiempo Completo (TC)</text>

        <rect x="835" y="0" width="215" height="45" fill="#F8FAFC" stroke="#CBD5E1"/>
        <text x="942" y="28" text-anchor="middle" font-family="system-ui, sans-serif" font-size="14" font-weight="700" fill="#334155">Dedicación Exclusiva (EX)</text>
      </g>

      <!-- Rows -->
      ${['Instructor', 'Asistente', 'Agregado', 'Asociado', 'Titular'].map((cat, idx) => `
        <g transform="translate(0, ${95 + idx * 60})">
          <rect x="0" y="0" width="220" height="60" fill="#FFFFFF" stroke="#CBD5E1"/>
          <text x="30" y="36" font-family="system-ui, sans-serif" font-size="16" font-weight="800" fill="#0F172A">${cat}</text>

          <rect x="220" y="0" width="200" height="60" fill="#FFFFFF" stroke="#E2E8F0"/>
          <rect x="420" y="0" width="200" height="60" fill="#FFFFFF" stroke="#E2E8F0"/>
          <rect x="620" y="0" width="215" height="60" fill="#FFFFFF" stroke="#E2E8F0"/>
          <rect x="835" y="0" width="215" height="60" fill="#FFFFFF" stroke="#E2E8F0"/>
        </g>
      `).join('')}

      <!-- Highlight boxes on matrix -->
      <rect x="220" y="95" width="400" height="120" fill="none" stroke="#EF4444" stroke-width="3" rx="4"/>
    </g>

    <!-- Callout 1 (Top Left) -->
    <g transform="translate(20, 20)">
      <rect width="320" height="130" rx="12" fill="#FEF2F2" stroke="#EF4444" stroke-width="2"/>
      <text x="20" y="35" font-family="system-ui, sans-serif" font-size="16" font-weight="800" fill="#991B1B">Cálculo de Horas:</text>
      <text x="20" y="65" font-family="system-ui, sans-serif" font-size="14" fill="#7F1D1D">
        <tspan x="20" dy="0">Control automático de UC</tspan>
        <tspan x="20" dy="20">(Unidades de Crédito) y atención</tspan>
        <tspan x="20" dy="20">estudiantil según modalidad.</tspan>
      </text>
    </g>

    <!-- Callout 2 (Bottom Right) -->
    <g transform="translate(1250, 360)">
      <rect width="340" height="140" rx="12" fill="#FEF3C7" stroke="#F59E0B" stroke-width="2"/>
      <text x="20" y="35" font-family="system-ui, sans-serif" font-size="16" font-weight="800" fill="#92400E">Registro de Posgrados:</text>
      <text x="20" y="65" font-family="system-ui, sans-serif" font-size="14" fill="#78350F">
        <tspan x="20" dy="0">Requisito estructurado de</tspan>
        <tspan x="20" dy="20">Especializaciones, Maestrías y</tspan>
        <tspan x="20" dy="20">Doctorados para ascensos superiores.</tspan>
      </text>
    </g>

    <!-- Bottom Rule Banner -->
    <g transform="translate(100, 520)">
      <rect width="1480" height="60" rx="10" fill="#F8FAFC" stroke="#CBD5E1" stroke-width="2"/>
      <line x1="0" y1="0" x2="0" y2="60" stroke="#EF4444" stroke-width="12"/>
      <text x="40" y="37" font-family="system-ui, sans-serif" font-size="18" font-weight="800" fill="#0F172A">
        El sistema calcula automáticamente la categoría vigente y valida las reglas cronológicas de ascenso institucional.
      </text>
    </g>
  </g>`,
  4
);

// SLIDE 5: Del trámite a la certificación: Flujo de emisión automatizada de documentos
const slide5 = wrapSlide(
  'Del trámite a la certificación: Flujo de emisión automatizada de documentos',
  'Canalización transparente desde la solicitud hasta la firma con sello criptográfico',
  `<g transform="translate(100, 240)">
    <!-- Step 1 -->
    <g transform="translate(80, 80)">
      <rect width="380" height="260" rx="20" fill="#FFFFFF" stroke="#E2E8F0" stroke-width="2" filter="url(#cardShadow)"/>
      <circle cx="50" cy="50" r="25" fill="#EFF6FF"/>
      <text x="50" y="58" text-anchor="middle" font-family="system-ui, sans-serif" font-size="20" font-weight="900" fill="#1D4ED8">1</text>
      <text x="90" y="45" font-family="system-ui, sans-serif" font-size="22" font-weight="900" fill="#0F172A">Solicitud</text>
      <text x="90" y="68" font-family="system-ui, sans-serif" font-size="15" font-weight="600" fill="#64748B">(Ingreso de Datos)</text>
      
      <text x="30" y="130" font-family="system-ui, sans-serif" font-size="16" fill="#334155">
        <tspan x="30" dy="0">Tramitación de años</tspan>
        <tspan x="30" dy="24">sabáticos, comisiones de</tspan>
        <tspan x="30" dy="24">servicio, prórrogas o</tspan>
        <tspan x="30" dy="24">incapacidades.</tspan>
      </text>
    </g>

    <!-- Arrow 1 -->
    <path d="M 490 210 L 580 210" stroke="#F59E0B" stroke-width="5" stroke-linecap="round" marker-end="url(#arrow)"/>
    <polygon points="580,200 600,210 580,220" fill="#F59E0B" />

    <!-- Step 2 -->
    <g transform="translate(620, 80)">
      <rect width="380" height="260" rx="20" fill="#FFFFFF" stroke="#E2E8F0" stroke-width="2" filter="url(#cardShadow)"/>
      <circle cx="50" cy="50" r="25" fill="#FEF3C7"/>
      <text x="50" y="58" text-anchor="middle" font-family="system-ui, sans-serif" font-size="20" font-weight="900" fill="#D97706">2</text>
      <text x="90" y="45" font-family="system-ui, sans-serif" font-size="22" font-weight="900" fill="#0F172A">Aprobación</text>
      <text x="90" y="68" font-family="system-ui, sans-serif" font-size="15" font-weight="600" fill="#64748B">(Panel Filament)</text>

      <text x="30" y="130" font-family="system-ui, sans-serif" font-size="16" fill="#334155">
        <tspan x="30" dy="0">Control de vigencias</tspan>
        <tspan x="30" dy="24">semestrales/anuales</tspan>
        <tspan x="30" dy="24">mediante autorización de</tspan>
        <tspan x="30" dy="24">roles superiores.</tspan>
      </text>
    </g>

    <!-- Arrow 2 -->
    <path d="M 1030 210 L 1120 210" stroke="#F59E0B" stroke-width="5" stroke-linecap="round"/>
    <polygon points="1120,200 1140,210 1120,220" fill="#F59E0B" />

    <!-- Step 3 -->
    <g transform="translate(1160, 80)">
      <rect width="460" height="260" rx="20" fill="#FFFFFF" stroke="#E2E8F0" stroke-width="2" filter="url(#cardShadow)"/>
      <circle cx="50" cy="50" r="25" fill="#DCFCE7"/>
      <text x="50" y="58" text-anchor="middle" font-family="system-ui, sans-serif" font-size="20" font-weight="900" fill="#15803D">3</text>
      <text x="90" y="45" font-family="system-ui, sans-serif" font-size="22" font-weight="900" fill="#0F172A">Generación</text>
      <text x="90" y="68" font-family="system-ui, sans-serif" font-size="15" font-weight="600" fill="#64748B">(Motor DomPDF)</text>

      <text x="30" y="130" font-family="system-ui, sans-serif" font-size="16" fill="#334155">
        <tspan x="30" dy="0">Exportación masiva o individual</tspan>
        <tspan x="30" dy="24">(formato apaisado o vertical).</tspan>
        <tspan x="30" dy="24">Diseño oficial UNERG.</tspan>
      </text>
    </g>

    <!-- Document graphic and verification stamp -->
    <g transform="translate(700, 420)">
      <rect width="400" height="150" rx="16" fill="#FFFBEB" stroke="#D97706" stroke-width="2"/>
      <circle cx="60" cy="75" r="32" fill="#F59E0B"/>
      <text x="60" y="82" text-anchor="middle" font-family="system-ui, sans-serif" font-size="22" font-weight="900" fill="#FFFFFF">&check;</text>
      <text x="120" y="60" font-family="monospace" font-size="17" font-weight="800" fill="#B45309">UNERG-REP-YYYY-XXXX</text>
      <text x="120" y="90" font-family="system-ui, sans-serif" font-size="15" font-weight="700" fill="#78350F">Código de Autenticidad Institucional embebido</text>
    </g>
  </g>`,
  5
);

// SLIDE 6: Arquitectura Multi-Inquilino para un control de acceso estrictamente territorial
const slide6 = wrapSlide(
  'Arquitectura Multi-Inquilino para un control de acceso estrictamente territorial',
  'Spatie Laravel-Permission con filtros dinámicos por Sede y Área académica',
  `<g transform="translate(100, 220)">
    <!-- Left: Isometric Pyramid Graphic representation -->
    <g transform="translate(80, 80)">
      <polygon points="300,50 100,200 500,200" fill="#EFF6FF" stroke="#3B82F6" stroke-width="2"/>
      <polygon points="100,200 500,200 500,320 100,320" fill="#FEE2E2" stroke="#EF4444" stroke-width="2"/>
      <polygon points="100,320 500,320 300,460" fill="#FEF3C7" stroke="#F59E0B" stroke-width="2"/>
      <text x="300" y="150" text-anchor="middle" font-family="system-ui, sans-serif" font-size="18" font-weight="800" fill="#1D4ED8">Multi-Tenant</text>
      <text x="300" y="270" text-anchor="middle" font-family="system-ui, sans-serif" font-size="18" font-weight="800" fill="#DC2626">Spatie Roles</text>
    </g>

    <!-- Right: Structured Feature Blocks -->
    <g transform="translate(680, 40)">
      <!-- Block 1 -->
      <g transform="translate(0, 0)">
        <rect width="920" height="100" rx="16" fill="#FFFFFF" stroke="#E2E8F0" stroke-width="2" filter="url(#cardShadow)"/>
        <text x="40" y="40" font-family="system-ui, sans-serif" font-size="20" font-weight="900" fill="#003366">Autenticación de Perímetro</text>
        <text x="40" y="70" font-family="system-ui, sans-serif" font-size="16" fill="#475569">Acceso restringido exclusivamente a dominios corporativos (@sigedor.com y @unerg.edu.ve).</text>
      </g>

      <!-- Block 2 -->
      <g transform="translate(0, 125)">
        <rect width="920" height="110" rx="16" fill="#FFFFFF" stroke="#E2E8F0" stroke-width="2" filter="url(#cardShadow)"/>
        <text x="40" y="40" font-family="system-ui, sans-serif" font-size="20" font-weight="900" fill="#003366">Super Administrador</text>
        <text x="40" y="70" font-family="system-ui, sans-serif" font-size="16" fill="#475569">Visión global. Gestión total de usuarios, roles, sedes, reportes y métricas de toda la universidad.</text>
      </g>

      <!-- Block 3 -->
      <g transform="translate(0, 260)">
        <rect width="920" height="120" rx="16" fill="#FFFFFF" stroke="#E2E8F0" stroke-width="2" filter="url(#cardShadow)"/>
        <text x="40" y="40" font-family="system-ui, sans-serif" font-size="20" font-weight="900" fill="#003366">Jefe de Área (Filtrado Dinámico)</text>
        <text x="40" y="70" font-family="system-ui, sans-serif" font-size="16" fill="#475569">Las consultas de Eloquent se adaptan dinámicamente. Solo visualiza y gestiona docentes de su Sede y Área.</text>
      </g>

      <!-- Block 4 -->
      <g transform="translate(0, 405)">
        <rect width="920" height="110" rx="16" fill="#FFFFFF" stroke="#E2E8F0" stroke-width="2" filter="url(#cardShadow)"/>
        <text x="40" y="40" font-family="system-ui, sans-serif" font-size="20" font-weight="900" fill="#003366">Soporte Multi-Rol</text>
        <text x="40" y="70" font-family="system-ui, sans-serif" font-size="16" fill="#475569">Un usuario puede ser simultáneamente Docente y Jefe de Área con permisos entrelazados de manera segura.</text>
      </g>
    </g>
  </g>`,
  6
);

// SLIDE 7: Pipeline de ingesta masiva: De archivos planos a persistencia relacional
const slide7 = wrapSlide(
  'Pipeline de ingesta masiva: De archivos planos a persistencia relacional',
  'Data Factory y Seeders para inicialización atómica de catálogos institucionales',
  `<g transform="translate(100, 240)">
    <!-- 5 CSV Tank representations -->
    ${[
      { file: 'users.csv', desc: 'Cuentas, correos y roles (contraseñas hasheadas).' },
      { file: 'teachers.csv', desc: 'Expedientes y datos demográficos.' },
      { file: 'categories.csv', desc: 'Histórico de escalafón.' },
      { file: 'dedications.csv', desc: 'Horas y directivas.' },
      { file: 'sites.csv', desc: 'Adscripción por sedes y facultades.' },
    ].map((item, i) => `
      <g transform="translate(${80 + i * 200}, 40)">
        <rect width="170" height="260" rx="20" fill="#FFFFFF" stroke="#CBD5E1" stroke-width="2" filter="url(#cardShadow)"/>
        <circle cx="85" cy="50" r="30" fill="#EFF6FF"/>
        <text x="85" y="56" text-anchor="middle" font-family="monospace" font-size="14" font-weight="800" fill="#1D4ED8">CSV</text>
        <text x="85" y="115" text-anchor="middle" font-family="monospace" font-size="16" font-weight="800" fill="#0F172A">${item.file}</text>
        <text x="85" y="160" text-anchor="middle" font-family="system-ui, sans-serif" font-size="13" fill="#64748B" width="140">
          <tspan x="85" dy="0">${item.desc.split(' ')[0] || ''} ${item.desc.split(' ')[1] || ''}</tspan>
          <tspan x="85" dy="18">${item.desc.split(' ').slice(2).join(' ')}</tspan>
        </text>
      </g>
    `).join('')}

    <!-- Conveyor belt into Laravel Seeders -->
    <g transform="translate(1140, 80)">
      <rect width="480" height="220" rx="24" fill="#FFFFFF" stroke="#003366" stroke-width="3" filter="url(#cardShadow)"/>
      <circle cx="100" cy="110" r="45" fill="#003366"/>
      <text x="100" y="120" text-anchor="middle" font-family="system-ui, sans-serif" font-size="34" fill="#FFFFFF">&gear;</text>
      <text x="180" y="100" font-family="system-ui, sans-serif" font-size="32" font-weight="900" fill="#0F172A">Laravel Seeders</text>
      <text x="180" y="135" font-family="system-ui, sans-serif" font-size="16" fill="#64748B">Validación atómica y relaciones de FK</text>
    </g>

    <!-- Bottom Notification Card -->
    <g transform="translate(300, 440)">
      <rect width="1120" height="120" rx="16" fill="#FEF3C7" stroke="#F59E0B" stroke-width="2"/>
      <circle cx="60" cy="60" r="28" fill="#F59E0B"/>
      <text x="60" y="68" text-anchor="middle" font-family="system-ui, sans-serif" font-size="24" font-weight="900" fill="#FFFFFF">!</text>
      <text x="110" y="48" font-family="system-ui, sans-serif" font-size="20" font-weight="900" fill="#92400E">Importación estructurada y segura</text>
      <text x="110" y="80" font-family="system-ui, sans-serif" font-size="16" fill="#78350F">
        Las cuentas creadas requieren aprobación explícita del Administrador (is_approved) para ser activadas en el sistema.
      </text>
    </g>
  </g>`,
  7
);

// SLIDE 8: Arquitectura de software en tres capas para rendimiento y mantenibilidad
const slide8 = wrapSlide(
  'Arquitectura de software en tres capas para rendimiento y mantenibilidad',
  'Separación de responsabilidades: Presentación, Lógica de Negocio y Persistencia',
  `<g transform="translate(140, 240)">
    <!-- Layer 1: Presentación -->
    <g transform="translate(60, 40)">
      <rect width="450" height="460" rx="20" fill="#FFFFFF" stroke="#3B82F6" stroke-width="3" filter="url(#cardShadow)"/>
      <rect x="0" y="0" width="450" height="70" rx="20" fill="#EFF6FF"/>
      <text x="40" y="45" font-family="system-ui, sans-serif" font-size="22" font-weight="900" fill="#1D4ED8">Capa 1: Presentación</text>

      <g transform="translate(30, 100)">
        <rect width="390" height="90" rx="12" fill="#F8FAFC" stroke="#E2E8F0"/>
        <text x="20" y="40" font-family="system-ui, sans-serif" font-size="18" font-weight="800" fill="#0F172A">Panel Administrativo</text>
        <text x="20" y="65" font-family="system-ui, sans-serif" font-size="14" fill="#64748B">Filament v3 / React Moderno</text>
      </g>
      <g transform="translate(30, 210)">
        <rect width="390" height="90" rx="12" fill="#F8FAFC" stroke="#E2E8F0"/>
        <text x="20" y="40" font-family="system-ui, sans-serif" font-size="18" font-weight="800" fill="#0F172A">Vistas Públicas Web</text>
        <text x="20" y="65" font-family="system-ui, sans-serif" font-size="14" fill="#64748B">Blade / Interfaz Web Responsive</text>
      </g>
      <g transform="translate(30, 320)">
        <rect width="390" height="90" rx="12" fill="#F8FAFC" stroke="#E2E8F0"/>
        <text x="20" y="40" font-family="system-ui, sans-serif" font-size="18" font-weight="800" fill="#0F172A">Motor de Renderizado PDF</text>
        <text x="20" y="65" font-family="system-ui, sans-serif" font-size="14" fill="#64748B">DomPDF / Documentos Oficiales</text>
      </g>
    </g>

    <!-- Layer 2: Lógica de Negocio y Seguridad -->
    <g transform="translate(570, 40)">
      <rect width="450" height="460" rx="20" fill="#FFFFFF" stroke="#003366" stroke-width="3" filter="url(#cardShadow)"/>
      <rect x="0" y="0" width="450" height="70" rx="20" fill="#F0F4F8"/>
      <text x="40" y="45" font-family="system-ui, sans-serif" font-size="22" font-weight="900" fill="#003366">Capa 2: Lógica y Seguridad</text>

      <g transform="translate(30, 100)">
        <rect width="390" height="90" rx="12" fill="#F8FAFC" stroke="#E2E8F0"/>
        <text x="20" y="40" font-family="system-ui, sans-serif" font-size="18" font-weight="800" fill="#0F172A">Núcleo MVC</text>
        <text x="20" y="65" font-family="system-ui, sans-serif" font-size="14" fill="#64748B">Laravel 11 / Validaciones de Dominio</text>
      </g>
      <g transform="translate(30, 210)">
        <rect width="390" height="90" rx="12" fill="#F8FAFC" stroke="#E2E8F0"/>
        <text x="20" y="40" font-family="system-ui, sans-serif" font-size="18" font-weight="800" fill="#0F172A">Gestión de Autorizaciones</text>
        <text x="20" y="65" font-family="system-ui, sans-serif" font-size="14" fill="#64748B">Spatie Roles &amp; Permissions</text>
      </g>
      <g transform="translate(30, 320)">
        <rect width="390" height="90" rx="12" fill="#F8FAFC" stroke="#E2E8F0"/>
        <text x="20" y="40" font-family="system-ui, sans-serif" font-size="18" font-weight="800" fill="#0F172A">Trazabilidad y Auditoría</text>
        <text x="20" y="65" font-family="system-ui, sans-serif" font-size="14" fill="#64748B">ActivityLog / Histórico de Cambios</text>
      </g>
    </g>

    <!-- Layer 3: Persistencia de Datos -->
    <g transform="translate(1080, 40)">
      <rect width="450" height="460" rx="20" fill="#FFFFFF" stroke="#059669" stroke-width="3" filter="url(#cardShadow)"/>
      <rect x="0" y="0" width="450" height="70" rx="20" fill="#ECFDF5"/>
      <text x="40" y="45" font-family="system-ui, sans-serif" font-size="22" font-weight="900" fill="#059669">Capa 3: Persistencia</text>

      <g transform="translate(30, 100)">
        <rect width="390" height="140" rx="12" fill="#F8FAFC" stroke="#E2E8F0"/>
        <text x="20" y="45" font-family="system-ui, sans-serif" font-size="18" font-weight="800" fill="#0F172A">Modelos Relacionales</text>
        <text x="20" y="75" font-family="system-ui, sans-serif" font-size="14" fill="#64748B">ORM Eloquent con hooks de ciclo de vida</text>
        <text x="20" y="100" font-family="system-ui, sans-serif" font-size="14" fill="#64748B">e integridad referencial estricta.</text>
      </g>
      <g transform="translate(30, 260)">
        <rect width="390" height="140" rx="12" fill="#F8FAFC" stroke="#E2E8F0"/>
        <text x="20" y="45" font-family="system-ui, sans-serif" font-size="18" font-weight="800" fill="#0F172A">Base de Datos Transaccional</text>
        <text x="20" y="75" font-family="system-ui, sans-serif" font-size="14" fill="#64748B">MySQL 8.0+ / MariaDB 10.4+</text>
        <text x="20" y="100" font-family="system-ui, sans-serif" font-size="14" fill="#64748B">Índices optimizados y transacciones ACID.</text>
      </g>
    </g>
  </g>`,
  8
);

// SLIDE 9: Modelo relacional: La integridad estructural del dominio universitario
const slide9 = wrapSlide(
  'Modelo relacional: La integridad estructural del dominio universitario',
  'Diagrama de Entidad-Relación institucional para la gestión docente de la UNERG',
  `<g transform="translate(100, 220)">
    <!-- Diagram Canvas -->
    <g transform="translate(100, 40)">
      <!-- Top Row: SEDES, AREAS, PROGRAMAS -->
      <rect x="200" y="20" width="180" height="70" rx="12" fill="#DBEAFE" stroke="#2563EB" stroke-width="2"/>
      <text x="290" y="62" text-anchor="middle" font-family="system-ui, sans-serif" font-size="20" font-weight="900" fill="#1E40AF">SEDES</text>

      <rect x="520" y="20" width="180" height="70" rx="12" fill="#DBEAFE" stroke="#2563EB" stroke-width="2"/>
      <text x="610" y="62" text-anchor="middle" font-family="system-ui, sans-serif" font-size="20" font-weight="900" fill="#1E40AF">AREAS</text>

      <rect x="840" y="20" width="180" height="70" rx="12" fill="#DBEAFE" stroke="#2563EB" stroke-width="2"/>
      <text x="930" y="62" text-anchor="middle" font-family="system-ui, sans-serif" font-size="20" font-weight="900" fill="#1E40AF">PROGRAMAS</text>

      <!-- Connecting Lines down to Adscripción -->
      <path d="M 290 90 L 610 160" stroke="#94A3B8" stroke-width="2"/>
      <path d="M 610 90 L 610 160" stroke="#94A3B8" stroke-width="2"/>
      <path d="M 930 90 L 610 160" stroke="#94A3B8" stroke-width="2"/>

      <rect x="530" y="160" width="160" height="40" rx="8" fill="#F1F5F9" stroke="#94A3B8"/>
      <text x="610" y="185" text-anchor="middle" font-family="system-ui, sans-serif" font-size="14" font-weight="700" fill="#475569">1:N Adscripción</text>

      <!-- Center: TEACHERS -->
      <line x1="610" y1="200" x2="610" y2="250" stroke="#94A3B8" stroke-width="2"/>

      <rect x="490" y="250" width="240" height="110" rx="16" fill="#0F172A" stroke="#38BDF8" stroke-width="3" filter="url(#cardShadow)"/>
      <text x="610" y="300" text-anchor="middle" font-family="system-ui, sans-serif" font-size="28" font-weight="900" fill="#FFFFFF">TEACHERS</text>
      <text x="610" y="330" text-anchor="middle" font-family="system-ui, sans-serif" font-size="16" font-weight="600" fill="#94A3B8">(Docentes UNERG)</text>

      <!-- Left: USERS, PERMISSIONS, REPORTS -->
      <line x1="490" y1="305" x2="350" y2="305" stroke="#94A3B8" stroke-width="2"/>

      <rect x="140" y="160" width="200" height="60" rx="10" fill="#FFFFFF" stroke="#CBD5E1" stroke-width="2"/>
      <text x="240" y="196" text-anchor="middle" font-family="system-ui, sans-serif" font-size="16" font-weight="800" fill="#0F172A">USERS</text>

      <rect x="80" y="275" width="260" height="60" rx="10" fill="#FFFFFF" stroke="#CBD5E1" stroke-width="2"/>
      <text x="210" y="311" text-anchor="middle" font-family="system-ui, sans-serif" font-size="16" font-weight="800" fill="#0F172A">PERMISSIONSTEACHERS</text>

      <rect x="140" y="390" width="200" height="60" rx="10" fill="#FFFFFF" stroke="#CBD5E1" stroke-width="2"/>
      <text x="240" y="426" text-anchor="middle" font-family="system-ui, sans-serif" font-size="16" font-weight="800" fill="#0F172A">REPORTS</text>

      <!-- Right: CATEGORIES, DEDICATIONS -->
      <line x1="730" y1="305" x2="860" y2="305" stroke="#94A3B8" stroke-width="2"/>

      <rect x="860" y="220" width="260" height="70" rx="10" fill="#FEF3C7" stroke="#F59E0B" stroke-width="2"/>
      <text x="990" y="255" text-anchor="middle" font-family="system-ui, sans-serif" font-size="18" font-weight="800" fill="#92400E">CATEGORIES</text>
      <text x="990" y="278" text-anchor="middle" font-family="system-ui, sans-serif" font-size="13" fill="#78350F">(1:1 Escalafón)</text>

      <rect x="860" y="320" width="260" height="70" rx="10" fill="#E0E7FF" stroke="#6366F1" stroke-width="2"/>
      <text x="990" y="355" text-anchor="middle" font-family="system-ui, sans-serif" font-size="18" font-weight="800" fill="#312E81">DEDICATIONS</text>
      <text x="990" y="378" text-anchor="middle" font-family="system-ui, sans-serif" font-size="13" fill="#3730A3">(1:1 Carga Horaria)</text>

      <!-- Bottom: SITES -->
      <line x1="610" y1="360" x2="610" y2="440" stroke="#94A3B8" stroke-width="2"/>
      <rect x="490" y="440" width="240" height="70" rx="10" fill="#DCFCE7" stroke="#16A34A" stroke-width="2"/>
      <text x="610" y="475" text-anchor="middle" font-family="system-ui, sans-serif" font-size="18" font-weight="800" fill="#166534">SITES</text>
      <text x="610" y="498" text-anchor="middle" font-family="system-ui, sans-serif" font-size="13" fill="#15803D">(1:N Materias Asignadas)</text>
    </g>
  </g>`,
  9
);

// SLIDE 10: Interoperabilidad nativa: API RESTful bajo el estándar OpenAPI 3.0
const slide10 = wrapSlide(
  'Interoperabilidad nativa: API RESTful bajo el estándar OpenAPI 3.0',
  'Integración con nómina, portales estudiantiles y tableros de rectorado',
  `<g transform="translate(100, 220)">
    <!-- Top Banner -->
    <rect x="60" y="20" width="1600" height="80" rx="16" fill="#EFF6FF" stroke="#3B82F6" stroke-width="2"/>
    <text x="860" y="68" text-anchor="middle" font-family="system-ui, sans-serif" font-size="20" font-weight="700" fill="#1E40AF">
      SIGEDOR no es un silo de datos. Expone una API moderna para integrarse con sistemas de nómina, portales estudiantiles y tableros de rectorado.
    </text>

    <!-- Left: Swagger UI Mockup -->
    <g transform="translate(60, 140)">
      <rect width="840" height="380" rx="16" fill="#FFFFFF" stroke="#E2E8F0" stroke-width="2" filter="url(#cardShadow)"/>
      <rect x="0" y="0" width="840" height="45" rx="16" fill="#1E293B"/>
      <!-- Dots -->
      <circle cx="25" cy="22" r="6" fill="#EF4444"/>
      <circle cx="45" cy="22" r="6" fill="#F59E0B"/>
      <circle cx="65" cy="22" r="6" fill="#10B981"/>
      <text x="420" y="28" text-anchor="middle" font-family="system-ui, sans-serif" font-size="14" font-weight="600" fill="#94A3B8">Swagger UI - SIGEDOR API Documentation</text>

      <g transform="translate(40, 70)">
        <text x="0" y="30" font-family="system-ui, sans-serif" font-size="28" font-weight="900" fill="#0F172A">SIGEDOR API</text>
        <rect x="220" y="8" width="140" height="28" rx="6" fill="#DCFCE7"/>
        <text x="290" y="27" text-anchor="middle" font-family="monospace" font-size="12" font-weight="800" fill="#166534">v1.0.0 (OpenAPI 3.0)</text>

        <!-- Endpoint Rows -->
        <g transform="translate(0, 70)">
          <rect width="760" height="46" rx="8" fill="#F0FDF4" stroke="#86EFAC"/>
          <rect x="10" y="8" width="60" height="30" rx="4" fill="#16A34A"/>
          <text x="40" y="28" text-anchor="middle" font-family="monospace" font-size="14" font-weight="900" fill="#FFFFFF">GET</text>
          <text x="90" y="28" font-family="monospace" font-size="15" font-weight="700" fill="#0F172A">/api/v1/teachers</text>
          <text x="730" y="28" text-anchor="end" font-family="system-ui, sans-serif" font-size="13" fill="#64748B">Retrieve teachers</text>
        </g>

        <g transform="translate(0, 126)">
          <rect width="760" height="46" rx="8" fill="#EFF6FF" stroke="#93C5FD"/>
          <rect x="10" y="8" width="60" height="30" rx="4" fill="#2563EB"/>
          <text x="40" y="28" text-anchor="middle" font-family="monospace" font-size="14" font-weight="900" fill="#FFFFFF">POST</text>
          <text x="90" y="28" font-family="monospace" font-size="15" font-weight="700" fill="#0F172A">/api/v1/evaluations</text>
          <text x="730" y="28" text-anchor="end" font-family="system-ui, sans-serif" font-size="13" fill="#64748B">Submit evaluation</text>
        </g>

        <g transform="translate(0, 182)">
          <rect width="760" height="46" rx="8" fill="#F0FDF4" stroke="#86EFAC"/>
          <rect x="10" y="8" width="60" height="30" rx="4" fill="#16A34A"/>
          <text x="40" y="28" text-anchor="middle" font-family="monospace" font-size="14" font-weight="900" fill="#FFFFFF">GET</text>
          <text x="90" y="28" font-family="monospace" font-size="15" font-weight="700" fill="#0F172A">/api/v1/health</text>
          <text x="730" y="28" text-anchor="end" font-family="system-ui, sans-serif" font-size="13" fill="#64748B">System health status</text>
        </g>
      </g>
    </g>

    <!-- Right: Spec Highlights -->
    <g transform="translate(940, 140)">
      <g transform="translate(0, 0)">
        <rect width="720" height="110" rx="16" fill="#FFFFFF" stroke="#E2E8F0" stroke-width="2" filter="url(#cardShadow)"/>
        <text x="40" y="45" font-family="system-ui, sans-serif" font-size="22" font-weight="900" fill="#0F172A">Especificación Swagger</text>
        <text x="40" y="75" font-family="system-ui, sans-serif" font-size="16" fill="#475569">Autogenerada bajo el estándar OpenAPI (api-docs.json).</text>
      </g>
      <g transform="translate(0, 135)">
        <rect width="720" height="110" rx="16" fill="#FFFFFF" stroke="#E2E8F0" stroke-width="2" filter="url(#cardShadow)"/>
        <text x="40" y="45" font-family="system-ui, sans-serif" font-size="22" font-weight="900" fill="#0F172A">Endpoints de Estado de Salud</text>
        <text x="40" y="75" font-family="system-ui, sans-serif" font-size="16" fill="#475569">Healthchecks integrados para Kubernetes, Docker y CI/CD.</text>
      </g>
      <g transform="translate(0, 270)">
        <rect width="720" height="110" rx="16" fill="#FFFFFF" stroke="#E2E8F0" stroke-width="2" filter="url(#cardShadow)"/>
        <text x="40" y="45" font-family="system-ui, sans-serif" font-size="22" font-weight="900" fill="#0F172A">Consumo Seguro y Estandarizado</text>
        <text x="40" y="75" font-family="system-ui, sans-serif" font-size="16" fill="#475569">Token Bearer, control de throttling y serialización JSON estricta.</text>
      </g>
    </g>
  </g>`,
  10
);

// SLIDE 11: Fiabilidad de grado empresarial: Pruebas automatizadas y auditoría
const slide11 = wrapSlide(
  'Fiabilidad de grado empresarial: Pruebas automatizadas y auditoría',
  'QA Mission Control Dashboard y Trazabilidad Forense de Acciones',
  `<g transform="translate(100, 220)">
    <!-- 3 Big Cards -->
    <g transform="translate(60, 40)">
      <!-- Card 1: Pest / PHPUnit -->
      <rect width="480" height="460" rx="20" fill="#FFFFFF" stroke="#E2E8F0" stroke-width="2" filter="url(#cardShadow)"/>
      <text x="40" y="60" font-family="system-ui, sans-serif" font-size="20" font-weight="800" fill="#475569">Cobertura de Pruebas (Pest / PHPUnit)</text>

      <text x="40" y="160" font-family="system-ui, sans-serif" font-size="52" font-weight="900" fill="#EF4444">
        49 Pruebas
      </text>
      <text x="40" y="220" font-family="system-ui, sans-serif" font-size="52" font-weight="900" fill="#10B981">
        372 Aserciones
      </text>
      <text x="40" y="260" font-family="system-ui, sans-serif" font-size="20" font-weight="700" fill="#059669">100% Pasadas con éxito</text>

      <line x1="40" y1="300" x2="440" y2="300" stroke="#E2E8F0" stroke-width="1.5"/>
      <text x="40" y="340" font-family="system-ui, sans-serif" font-size="16" fill="#334155">
        <tspan x="40" dy="0">Validación de políticas multi-inquilino,</tspan>
        <tspan x="40" dy="24">reglas de escalafón UNERG y</tspan>
        <tspan x="40" dy="24">endpoints de la API v1.</tspan>
      </text>
    </g>

    <!-- Card 2: Trazabilidad ActivityLog -->
    <g transform="translate(580, 40)">
      <rect width="480" height="460" rx="20" fill="#FFFFFF" stroke="#E2E8F0" stroke-width="2" filter="url(#cardShadow)"/>
      <text x="40" y="60" font-family="system-ui, sans-serif" font-size="20" font-weight="800" fill="#475569">Trazabilidad (ActivityLog)</text>

      <!-- Timeline graphic -->
      <g transform="translate(40, 120)">
        <line x1="30" y1="30" x2="370" y2="30" stroke="#F59E0B" stroke-width="4"/>
        <circle cx="30" cy="30" r="10" fill="#F59E0B"/>
        <text x="30" y="65" text-anchor="middle" font-family="system-ui, sans-serif" font-size="13" font-weight="700" fill="#0F172A">Expediente Modificado</text>

        <circle cx="200" cy="30" r="10" fill="#F59E0B"/>
        <text x="200" y="65" text-anchor="middle" font-family="system-ui, sans-serif" font-size="13" font-weight="700" fill="#0F172A">Ascenso Aprobado</text>

        <circle cx="370" cy="30" r="10" fill="#F59E0B"/>
        <text x="370" y="65" text-anchor="middle" font-family="system-ui, sans-serif" font-size="13" font-weight="700" fill="#0F172A">Permiso Otorgado</text>
      </g>

      <text x="40" y="270" font-family="system-ui, sans-serif" font-size="18" font-weight="800" fill="#0F172A">Registro inmutable de auditoría</text>
      <text x="40" y="310" font-family="system-ui, sans-serif" font-size="16" fill="#334155">
        <tspan x="40" dy="0">Cada modificación de expediente,</tspan>
        <tspan x="40" dy="24">ascenso de categoría o permiso otorgado</tspan>
        <tspan x="40" dy="24">queda registrado con IP, usuario y timestamp.</tspan>
      </text>
    </g>

    <!-- Card 3: Seguridad Perimetral -->
    <g transform="translate(1100, 40)">
      <rect width="480" height="460" rx="20" fill="#FFFFFF" stroke="#E2E8F0" stroke-width="2" filter="url(#cardShadow)"/>
      <text x="40" y="60" font-family="system-ui, sans-serif" font-size="20" font-weight="800" fill="#475569">Seguridad Perimetral</text>

      <circle cx="240" cy="160" r="55" fill="#EFF6FF"/>
      <text x="240" y="175" text-anchor="middle" font-family="system-ui, sans-serif" font-size="44" fill="#1D4ED8">&#128274;</text>

      <g transform="translate(40, 260)">
        <text x="0" y="0" font-family="system-ui, sans-serif" font-size="16" fill="#334155">
          <tspan x="0" dy="0">&bull; Cabeceras de seguridad OWASP estrictas.</tspan>
          <tspan x="0" dy="28">&bull; Sanitización y protección de inyección CSV.</tspan>
          <tspan x="0" dy="28">&bull; Protección de rutas por roles e inquilino.</tspan>
          <tspan x="0" dy="28">&bull; Hasheo seguro de credenciales con Argon2id.</tspan>
        </text>
      </g>
    </g>
  </g>`,
  11
);

// SLIDE 12: Nacido como investigación, diseñado para escalar globalmente
const slide12 = wrapSlide(
  'Nacido como investigación, diseñado para escalar globalmente',
  'Liberado bajo Licencia MIT &bull; Autoría: Frank Sousa &bull; UNERG',
  `<g transform="translate(100, 220)">
    <!-- Top Callout -->
    <rect x="60" y="20" width="1600" height="80" rx="16" fill="#EFF6FF" stroke="#3B82F6" stroke-width="2"/>
    <text x="860" y="68" text-anchor="middle" font-family="system-ui, sans-serif" font-size="20" font-weight="700" fill="#1E40AF">
      Liberado bajo Licencia MIT, el código fuente está desacoplado siguiendo principios SOLID, permitiendo su bifurcación e implementación en nuevos ecosistemas académicos.
    </text>

    <!-- Center: Fork & Adapt Conceptual Map -->
    <g transform="translate(160, 160)">
      <!-- Origin: Repositorio Core -->
      <rect x="0" y="100" width="340" height="150" rx="20" fill="#0F172A" stroke="#38BDF8" stroke-width="3" filter="url(#cardShadow)"/>
      <text x="170" y="160" text-anchor="middle" font-family="system-ui, sans-serif" font-size="26" font-weight="900" fill="#FFFFFF">Repositorio Core</text>
      <text x="170" y="195" text-anchor="middle" font-family="system-ui, sans-serif" font-size="26" font-weight="900" fill="#38BDF8">SIGEDOR</text>

      <rect x="80" y="275" width="180" height="40" rx="10" fill="#FEF3C7"/>
      <text x="170" y="300" text-anchor="middle" font-family="system-ui, sans-serif" font-size="16" font-weight="800" fill="#92400E">Licencia MIT</text>

      <!-- Branches -->
      <path d="M 340 175 C 480 175, 480 50, 600 50" fill="none" stroke="#F59E0B" stroke-width="4"/>
      <path d="M 340 175 L 600 175" fill="none" stroke="#F59E0B" stroke-width="4"/>
      <path d="M 340 175 C 480 175, 480 300, 600 300" fill="none" stroke="#F59E0B" stroke-width="4"/>

      <!-- Branch 1: Estructura Modular -->
      <g transform="translate(600, 0)">
        <rect width="640" height="100" rx="16" fill="#FFFFFF" stroke="#E2E8F0" stroke-width="2" filter="url(#cardShadow)"/>
        <text x="35" y="40" font-family="system-ui, sans-serif" font-size="22" font-weight="900" fill="#0F172A">Estructura Modular</text>
        <text x="35" y="70" font-family="system-ui, sans-serif" font-size="16" fill="#475569">Expansión fácil hacia evaluación docente o control de posgrados.</text>
      </g>

      <!-- Branch 2: Datos Base -->
      <g transform="translate(600, 125)">
        <rect width="640" height="100" rx="16" fill="#FFFFFF" stroke="#E2E8F0" stroke-width="2" filter="url(#cardShadow)"/>
        <text x="35" y="40" font-family="system-ui, sans-serif" font-size="22" font-weight="900" fill="#0F172A">Datos Base</text>
        <text x="35" y="70" font-family="system-ui, sans-serif" font-size="16" fill="#475569">Modificación de catálogos mediante Seeders y CSVs institucionales propios.</text>
      </g>

      <!-- Branch 3: Identidad Visual -->
      <g transform="translate(600, 250)">
        <rect width="640" height="100" rx="16" fill="#FFFFFF" stroke="#E2E8F0" stroke-width="2" filter="url(#cardShadow)"/>
        <text x="35" y="40" font-family="system-ui, sans-serif" font-size="22" font-weight="900" fill="#0F172A">Identidad Visual</text>
        <text x="35" y="70" font-family="system-ui, sans-serif" font-size="16" fill="#475569">Personalización absoluta de vistas Blade, estilos y membretes PDF oficiales.</text>
      </g>
    </g>

    <!-- Bottom Banner -->
    <g transform="translate(500, 520)">
      <rect width="720" height="50" rx="25" fill="#F1F5F9"/>
      <text x="360" y="32" text-anchor="middle" font-family="system-ui, sans-serif" font-size="16" font-weight="700" fill="#475569">
        Un proyecto de código abierto construido sobre el rigor académico.
      </text>
    </g>
  </g>`,
  12
);

const slides = [
  slide1, slide2, slide3, slide4, slide5, slide6,
  slide7, slide8, slide9, slide10, slide11, slide12
];

slides.forEach((s, idx) => {
  fs.writeFileSync(path.join(slidesDir, `slide-${idx + 1}.svg`), s);
});

// Write Main Infographic
fs.writeFileSync(path.join(outDir, 'vision_integral_sigedor.svg'), infographicSvg);
fs.writeFileSync(path.join(outDir, 'Gestión_digital_docente_universitaria_SIGEDOR.svg'), infographicSvg);

console.log('All 12 slide SVGs and Infographic successfully created in public/images/');

