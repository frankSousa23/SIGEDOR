import fs from 'fs';
import path from 'path';

const slidesDir = path.resolve('public/images/slides');
if (!fs.existsSync(slidesDir)) fs.mkdirSync(slidesDir, { recursive: true });

function baseSlide(title, subtitle, content, slideNum) {
  return `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1920 1080" width="1920" height="1080">
  <defs>
    <linearGradient id="bg" x1="0" y1="0" x2="0" y2="1">
      <stop offset="0%" stop-color="#F8F9FB"/>
      <stop offset="100%" stop-color="#EFF2F6"/>
    </linearGradient>
    <filter id="cardShadow" x="-5%" y="-5%" width="110%" height="110%">
      <feDropShadow dx="0" dy="4" stdDeviation="8" flood-color="#0F172A" flood-opacity="0.06"/>
    </filter>
  </defs>

  <rect width="1920" height="1080" fill="url(#bg)"/>
  <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
    <path d="M 40 0 L 0 0 0 40" fill="none" stroke="#E2E8F0" stroke-width="0.8" opacity="0.7"/>
  </pattern>
  <rect width="1920" height="1080" fill="url(#grid)"/>

  <!-- Header -->
  <g transform="translate(80, 80)">
    <text x="0" y="45" font-family="'Plus Jakarta Sans', system-ui, sans-serif" font-size="44" font-weight="900" fill="#0F172A" letter-spacing="-0.5">
      ${title}
    </text>
    ${subtitle ? `<text x="0" y="85" font-family="'Plus Jakarta Sans', system-ui, sans-serif" font-size="20" font-weight="500" fill="#475569">
      ${subtitle}
    </text>` : ''}
  </g>

  <!-- Body Content -->
  ${content}

  <!-- Footer -->
  <g transform="translate(80, 1045)">
    <text x="0" y="0" font-family="system-ui, sans-serif" font-size="14" font-weight="600" fill="#94A3B8">
      SIGEDOR &bull; Proyecto de Investigación &bull; UNERG &bull; Frank Sousa &bull; Lámina ${slideNum} de 12
    </text>
    <text x="1760" y="0" text-anchor="end" font-family="system-ui, sans-serif" font-size="14" font-weight="600" fill="#64748B">
      ✦ Gemini Notebook
    </text>
  </g>
</svg>`;
}

// SLIDE 1
const s1 = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1920 1080" width="1920" height="1080">
  <defs>
    <linearGradient id="s1Bg" x1="0" y1="0" x2="0" y2="1">
      <stop offset="0%" stop-color="#F8F9FB"/>
      <stop offset="100%" stop-color="#EFF2F6"/>
    </linearGradient>
    <filter id="shadow" x="-5%" y="-5%" width="110%" height="110%">
      <feDropShadow dx="0" dy="6" stdDeviation="12" flood-color="#0F172A" flood-opacity="0.08"/>
    </filter>
  </defs>
  <rect width="1920" height="1080" fill="url(#s1Bg)"/>
  <pattern id="s1Grid" width="40" height="40" patternUnits="userSpaceOnUse">
    <path d="M 40 0 L 0 0 0 40" fill="none" stroke="#E2E8F0" stroke-width="0.8" opacity="0.7"/>
  </pattern>
  <rect width="1920" height="1080" fill="url(#s1Grid)"/>

  <!-- Column Monument / Pillar graphic in Center -->
  <g transform="translate(960, 310)">
    <circle cx="0" cy="0" r="190" fill="none" stroke="#93C5FD" stroke-width="1.5" stroke-dasharray="4 4" opacity="0.6"/>
    <circle cx="0" cy="0" r="140" fill="none" stroke="#60A5FA" stroke-width="2" opacity="0.7"/>
    <circle cx="0" cy="0" r="80" fill="none" stroke="#2563EB" stroke-width="2.5"/>
    
    <rect x="-120" y="160" width="240" height="24" rx="4" fill="#0F172A"/>
    <rect x="-90" y="140" width="180" height="20" rx="3" fill="#1E3A8A"/>
    <rect x="-60" y="-120" width="120" height="260" rx="6" fill="#1E40AF"/>
    <rect x="-80" y="-140" width="160" height="20" rx="3" fill="#1E3A8A"/>
    <rect x="-120" y="-160" width="240" height="20" rx="4" fill="#0F172A"/>

    <circle cx="0" cy="0" r="45" fill="#EFF6FF" stroke="#3B82F6" stroke-width="6"/>
    <circle cx="0" cy="0" r="20" fill="#1E3A8A"/>
    
    <circle cx="-135" cy="-95" r="8" fill="#38BDF8"/>
    <circle cx="135" cy="-95" r="8" fill="#38BDF8"/>
    <circle cx="-135" cy="95" r="8" fill="#38BDF8"/>
    <circle cx="135" cy="95" r="8" fill="#38BDF8"/>
  </g>

  <!-- Title and subtitle -->
  <text x="960" y="610" text-anchor="middle" font-family="'Plus Jakarta Sans', system-ui, sans-serif" font-size="52" font-weight="900" fill="#0F172A" letter-spacing="-0.5">
    SIGEDOR: El Motor Digital del
  </text>
  <text x="960" y="680" text-anchor="middle" font-family="'Plus Jakarta Sans', system-ui, sans-serif" font-size="52" font-weight="900" fill="#003366" letter-spacing="-0.5">
    Control Académico Universitario
  </text>
  <text x="960" y="745" text-anchor="middle" font-family="system-ui, sans-serif" font-size="22" font-weight="500" fill="#475569">
    Una arquitectura integral para la gestión de expedientes, escalafón docente y emisión documental con sello criptográfico.
  </text>

  <!-- Badges -->
  <g transform="translate(960, 830)">
    <g transform="translate(-360, 0)">
      <rect x="0" y="0" width="160" height="46" rx="23" fill="#FFFFFF" stroke="#CBD5E1" stroke-width="1.5" filter="url(#shadow)"/>
      <text x="80" y="29" text-anchor="middle" font-family="system-ui, sans-serif" font-size="16" font-weight="700" fill="#1E293B">[Laravel 11]</text>
    </g>
    <g transform="translate(-180, 0)">
      <rect x="0" y="0" width="160" height="46" rx="23" fill="#FFFFFF" stroke="#CBD5E1" stroke-width="1.5" filter="url(#shadow)"/>
      <text x="80" y="29" text-anchor="middle" font-family="system-ui, sans-serif" font-size="16" font-weight="700" fill="#1E293B">[Filament v3]</text>
    </g>
    <g transform="translate(10, 0)">
      <rect x="0" y="0" width="170" height="46" rx="23" fill="#FFFFFF" stroke="#CBD5E1" stroke-width="1.5" filter="url(#shadow)"/>
      <text x="85" y="29" text-anchor="middle" font-family="system-ui, sans-serif" font-size="16" font-weight="700" fill="#1E293B">[OpenAPI 3.0]</text>
    </g>
    <g transform="translate(200, 0)">
      <rect x="0" y="0" width="170" height="46" rx="23" fill="#FFFFFF" stroke="#CBD5E1" stroke-width="1.5" filter="url(#shadow)"/>
      <text x="85" y="29" text-anchor="middle" font-family="system-ui, sans-serif" font-size="16" font-weight="700" fill="#1E293B">[Licencia MIT]</text>
    </g>
  </g>

  <g transform="translate(80, 1045)">
    <text x="1760" y="0" text-anchor="end" font-family="system-ui, sans-serif" font-size="14" font-weight="600" fill="#64748B">
      ✦ Gemini Notebook
    </text>
  </g>
</svg>`;

// SLIDE 2
const s2 = baseSlide(
  'De la Fragmentación Documental a la Inteligencia Institucional',
  'La administración universitaria tradicional sufre por el aislamiento de datos. SIGEDOR centraliza el núcleo de la universidad en un único ecosistema digital automatizado, garantizando integridad relacional y trazabilidad forense.',
  `<g transform="translate(80, 220)">
    <!-- Left Box: El Problema: Fragmentación -->
    <g transform="translate(0, 0)">
      <rect width="840" height="540" rx="20" fill="#FFFFFF" stroke="#E2E8F0" stroke-width="1.5" filter="url(#cardShadow)"/>
      <text x="420" y="55" text-anchor="middle" font-family="system-ui" font-size="22" font-weight="800" fill="#64748B">El Problema: Fragmentación</text>

      <g transform="translate(140, 120)">
        <rect width="140" height="70" rx="10" fill="#F1F5F9" stroke="#CBD5E1" stroke-width="1.5"/>
        <text x="70" y="42" text-anchor="middle" font-family="system-ui" font-size="16" font-weight="700" fill="#334155">Nómina</text>
      </g>
      <g transform="translate(520, 120)">
        <rect width="140" height="70" rx="10" fill="#F1F5F9" stroke="#CBD5E1" stroke-width="1.5"/>
        <text x="70" y="36" text-anchor="middle" font-family="system-ui" font-size="15" font-weight="700" fill="#334155">Recursos</text>
        <text x="70" y="54" text-anchor="middle" font-family="system-ui" font-size="15" font-weight="700" fill="#334155">Humanos</text>
      </g>
      <g transform="translate(190, 340)">
        <rect width="140" height="70" rx="10" fill="#F1F5F9" stroke="#CBD5E1" stroke-width="1.5"/>
        <text x="70" y="36" text-anchor="middle" font-family="system-ui" font-size="15" font-weight="700" fill="#334155">Control de</text>
        <text x="70" y="54" text-anchor="middle" font-family="system-ui" font-size="15" font-weight="700" fill="#334155">Estudios</text>
      </g>
      <g transform="translate(560, 340)">
        <rect width="140" height="70" rx="10" fill="#F1F5F9" stroke="#CBD5E1" stroke-width="1.5"/>
        <text x="70" y="36" text-anchor="middle" font-family="system-ui" font-size="15" font-weight="700" fill="#334155">Archivos</text>
        <text x="70" y="54" text-anchor="middle" font-family="system-ui" font-size="15" font-weight="700" fill="#334155">de Papel</text>
      </g>

      <path d="M 280 155 Q 400 240 520 155" fill="none" stroke="#94A3B8" stroke-width="2" stroke-dasharray="6 4"/>
      <path d="M 210 190 Q 300 270 260 340" fill="none" stroke="#94A3B8" stroke-width="2" stroke-dasharray="6 4"/>
      <path d="M 590 190 Q 500 270 630 340" fill="none" stroke="#94A3B8" stroke-width="2" stroke-dasharray="6 4"/>
      <path d="M 280 170 Q 420 280 560 360" fill="none" stroke="#EF4444" stroke-width="2" opacity="0.7"/>
      <path d="M 330 375 Q 440 260 520 170" fill="none" stroke="#EF4444" stroke-width="2" opacity="0.7"/>
    </g>

    <!-- Right Box: La Solución: El Ecosistema Centralizado -->
    <g transform="translate(880, 0)">
      <rect width="880" height="540" rx="20" fill="#FFFFFF" stroke="#E2E8F0" stroke-width="1.5" filter="url(#cardShadow)"/>
      <text x="440" y="55" text-anchor="middle" font-family="system-ui" font-size="22" font-weight="800" fill="#003366">La Solución: El Ecosistema Centralizado</text>

      <circle cx="440" cy="290" r="160" fill="none" stroke="#E2E8F0" stroke-width="1.5"/>
      <circle cx="440" cy="290" r="110" fill="none" stroke="#DBEAFE" stroke-width="2"/>
      
      <circle cx="440" cy="290" r="70" fill="#003366" filter="url(#cardShadow)"/>
      <text x="440" y="297" text-anchor="middle" font-family="system-ui" font-size="22" font-weight="900" fill="#FFFFFF">SIGEDOR</text>

      <line x1="440" y1="220" x2="440" y2="150" stroke="#F59E0B" stroke-width="3"/>
      <rect x="370" y="110" width="140" height="42" rx="8" fill="#1E293B"/>
      <text x="440" y="137" text-anchor="middle" font-family="system-ui" font-size="14" font-weight="700" fill="#FFFFFF">Expedientes</text>

      <line x1="510" y1="290" x2="580" y2="290" stroke="#F59E0B" stroke-width="3"/>
      <rect x="580" y="269" width="140" height="42" rx="8" fill="#1E293B"/>
      <text x="650" y="295" text-anchor="middle" font-family="system-ui" font-size="14" font-weight="700" fill="#FFFFFF">Control de Carga</text>

      <line x1="440" y1="360" x2="440" y2="430" stroke="#F59E0B" stroke-width="3"/>
      <rect x="370" y="430" width="140" height="42" rx="8" fill="#1E293B"/>
      <text x="440" y="457" text-anchor="middle" font-family="system-ui" font-size="14" font-weight="700" fill="#FFFFFF">Escalafón</text>

      <line x1="370" y1="290" x2="300" y2="290" stroke="#F59E0B" stroke-width="3"/>
      <rect x="160" y="269" width="140" height="42" rx="8" fill="#1E293B"/>
      <text x="230" y="287" text-anchor="middle" font-family="system-ui" font-size="13" font-weight="700" fill="#FFFFFF">Emisión de</text>
      <text x="230" y="303" text-anchor="middle" font-family="system-ui" font-size="13" font-weight="700" fill="#FFFFFF">Reportes</text>
    </g>

    <!-- Bottom 3 Pillars -->
    <g transform="translate(0, 570)">
      <g transform="translate(0, 0)">
        <rect width="560" height="90" rx="12" fill="#FFFFFF" stroke="#E2E8F0" stroke-width="1.5" filter="url(#cardShadow)"/>
        <text x="30" y="38" font-family="system-ui" font-size="16" font-weight="800" fill="#0F172A">Integridad 1:1 y 1:N: <tspan font-weight="500" fill="#475569">Modelado de</tspan></text>
        <text x="30" y="62" font-family="system-ui" font-size="15" fill="#475569">dominio universitario exacto.</text>
      </g>
      <g transform="translate(580, 0)">
        <rect width="560" height="90" rx="12" fill="#FFFFFF" stroke="#E2E8F0" stroke-width="1.5" filter="url(#cardShadow)"/>
        <text x="30" y="38" font-family="system-ui" font-size="16" font-weight="800" fill="#0F172A">Trazabilidad Forense: <tspan font-weight="500" fill="#475569">Auditoría con</tspan></text>
        <text x="30" y="62" font-family="system-ui" font-size="15" fill="#475569">spatie/laravel-activitylog.</text>
      </g>
      <g transform="translate(1160, 0)">
        <rect width="600" height="90" rx="12" fill="#FFFFFF" stroke="#E2E8F0" stroke-width="1.5" filter="url(#cardShadow)"/>
        <text x="30" y="38" font-family="system-ui" font-size="16" font-weight="800" fill="#0F172A">Respuesta Inmediata: <tspan font-weight="500" fill="#475569">Flujos digitales</tspan></text>
        <text x="30" y="62" font-family="system-ui" font-size="15" fill="#475569">en milisegundos.</text>
      </g>
    </g>
  </g>`,
  2
);

// SLIDE 3: Anatomía del Expediente Integral 360°
const s3 = baseSlide(
  'Anatomía del Expediente Integral 360°',
  'Un expediente unificado donde 4 gestores de relaciones operan simultáneamente mediante hooks de ciclo de vida Eloquent.',
  `<g transform="translate(80, 220)">
    <!-- Center Graphic: ID Card and Radar -->
    <g transform="translate(880, 310)">
      <circle cx="0" cy="0" r="240" fill="none" stroke="#E2E8F0" stroke-width="2"/>
      <circle cx="0" cy="0" r="170" fill="none" stroke="#D97706" stroke-width="1.5" stroke-dasharray="6 6"/>
      
      <!-- Card Icon -->
      <rect x="-100" y="-130" width="200" height="260" rx="16" fill="#FFFFFF" stroke="#CBD5E1" stroke-width="2" filter="url(#cardShadow)"/>
      <circle cx="0" cy="-40" r="36" fill="#94A3B8"/>
      <rect x="-45" y="15" width="90" height="12" rx="6" fill="#003366"/>
      <rect x="-65" y="40" width="130" height="8" rx="4" fill="#CBD5E1"/>
      <rect x="-65" y="58" width="100" height="8" rx="4" fill="#CBD5E1"/>
    </g>

    <!-- Top Left: Adscripción Institucional -->
    <g transform="translate(100, 40)">
      <text x="0" y="30" font-family="system-ui" font-size="24" font-weight="900" fill="#0F172A">Adscripción Institucional</text>
      <g transform="translate(0, 50)">
        <rect x="0" y="0" width="14" height="14" rx="2" fill="#64748B"/>
        <text x="28" y="13" font-family="system-ui" font-size="18" fill="#334155">Sedes</text>
      </g>
      <g transform="translate(0, 85)">
        <rect x="0" y="0" width="14" height="14" rx="2" fill="#64748B"/>
        <text x="28" y="13" font-family="system-ui" font-size="18" fill="#334155">Áreas</text>
      </g>
      <g transform="translate(0, 120)">
        <rect x="0" y="0" width="14" height="14" rx="2" fill="#64748B"/>
        <text x="28" y="13" font-family="system-ui" font-size="18" fill="#334155">Programas</text>
      </g>
      <rect x="0" y="160" width="220" height="36" rx="18" fill="#0284C7"/>
      <text x="110" y="184" text-anchor="middle" font-family="monospace" font-size="15" font-weight="700" fill="#FFFFFF">$teacher-&gt;sites()</text>
    </g>

    <!-- Top Right: Escalafón Universitario -->
    <g transform="translate(1250, 40)">
      <text x="0" y="30" font-family="system-ui" font-size="24" font-weight="900" fill="#0F172A">Escalafón Universitario</text>
      <!-- Bar Chart Icon -->
      <g transform="translate(20, 50)">
        <rect x="0" y="50" width="18" height="50" rx="2" fill="#0284C7"/>
        <rect x="25" y="30" width="18" height="70" rx="2" fill="#0284C7"/>
        <rect x="50" y="0" width="18" height="100" rx="2" fill="#0284C7"/>
      </g>
      <rect x="0" y="160" width="250" height="36" rx="18" fill="#0284C7"/>
      <text x="125" y="184" text-anchor="middle" font-family="monospace" font-size="15" font-weight="700" fill="#FFFFFF">$teacher-&gt;category()</text>
    </g>

    <!-- Bottom Left: Carga Académica -->
    <g transform="translate(100, 440)">
      <text x="0" y="30" font-family="system-ui" font-size="24" font-weight="900" fill="#0F172A">Carga Académica</text>
      <!-- Clock Icon -->
      <circle cx="50" cy="90" r="35" fill="none" stroke="#003366" stroke-width="4"/>
      <line x1="50" y1="90" x2="50" y2="70" stroke="#003366" stroke-width="4" stroke-linecap="round"/>
      <line x1="50" y1="90" x2="68" y2="90" stroke="#003366" stroke-width="4" stroke-linecap="round"/>
      <rect x="0" y="150" width="260" height="36" rx="18" fill="#0284C7"/>
      <text x="130" y="174" text-anchor="middle" font-family="monospace" font-size="15" font-weight="700" fill="#FFFFFF">$teacher-&gt;dedication()</text>
    </g>

    <!-- Bottom Right: Permisos y Licencias -->
    <g transform="translate(1250, 440)">
      <text x="0" y="30" font-family="system-ui" font-size="24" font-weight="900" fill="#0F172A">Permisos y Licencias</text>
      <!-- Calendar Icon -->
      <rect x="20" y="55" width="70" height="65" rx="8" fill="none" stroke="#003366" stroke-width="4"/>
      <line x1="20" y1="75" x2="90" y2="75" stroke="#003366" stroke-width="3"/>
      <circle cx="38" cy="90" r="3" fill="#003366"/>
      <circle cx="55" cy="90" r="3" fill="#003366"/>
      <circle cx="72" cy="90" r="3" fill="#003366"/>
      <circle cx="38" cy="105" r="3" fill="#003366"/>
      <circle cx="55" cy="105" r="3" fill="#003366"/>
      <circle cx="72" cy="105" r="3" fill="#003366"/>
      <rect x="0" y="150" width="280" height="36" rx="18" fill="#0284C7"/>
      <text x="140" y="174" text-anchor="middle" font-family="monospace" font-size="15" font-weight="700" fill="#FFFFFF">$teacher-&gt;permissions()</text>
    </g>

    <!-- Connecting Gold Lines -->
    <path d="M 320 130 L 680 280" fill="none" stroke="#D97706" stroke-width="2"/>
    <path d="M 1250 130 L 1080 280" fill="none" stroke="#D97706" stroke-width="2"/>
    <path d="M 320 520 L 680 340" fill="none" stroke="#D97706" stroke-width="2"/>
    <path d="M 1250 520 L 1080 340" fill="none" stroke="#D97706" stroke-width="2"/>
  </g>`,
  3
);

// SLIDE 4: Validación Automatizada de Reglas de Negocio Universitarias
const s4 = baseSlide(
  'Validación Automatizada de Reglas de Negocio Universitarias',
  'El núcleo lógico del sistema no es solo un registro; es un motor de cálculo. SIGEDOR cruza automáticamente el estatus del escalafón cronológico con las modalidades de contratación horaria, impidiendo inconsistencias administrativas.',
  `<g transform="translate(80, 220)">
    <!-- Matrix Grid Container -->
    <g transform="translate(0, 0)">
      <!-- Matrix Table Box -->
      <rect width="1180" height="460" rx="16" fill="#FFFFFF" stroke="#CBD5E1" stroke-width="1.5" filter="url(#cardShadow)"/>

      <!-- Column Labels (Dedicación Horaria) -->
      <g transform="translate(240, 400)">
        <text x="120" y="0" text-anchor="middle" font-family="system-ui" font-size="20" font-weight="800" fill="#0F172A">TCV</text>
        <text x="360" y="0" text-anchor="middle" font-family="system-ui" font-size="20" font-weight="800" fill="#0F172A">MT</text>
        <text x="600" y="0" text-anchor="middle" font-family="system-ui" font-size="20" font-weight="800" fill="#0F172A">TC</text>
        <text x="840" y="0" text-anchor="middle" font-family="system-ui" font-size="20" font-weight="800" fill="#0F172A">EX</text>
        <text x="480" y="38" text-anchor="middle" font-family="system-ui" font-size="22" font-weight="900" fill="#0284C7">Dedicación Horaria</text>
      </g>

      <!-- Row Labels (Escalafón) -->
      <g transform="translate(40, 20)">
        <!-- Vertical Label -->
        <text x="20" y="190" transform="rotate(-90 20 190)" text-anchor="middle" font-family="system-ui" font-size="24" font-weight="900" fill="#0F172A">Escalafón</text>

        <rect x="60" y="0" width="160" height="60" rx="8" fill="#F8FAFC" stroke="#E2E8F0" stroke-width="1"/>
        <text x="140" y="38" text-anchor="middle" font-family="system-ui" font-size="18" font-weight="700" fill="#334155">Instructor</text>

        <rect x="60" y="70" width="160" height="60" rx="8" fill="#F8FAFC" stroke="#E2E8F0" stroke-width="1"/>
        <text x="140" y="108" text-anchor="middle" font-family="system-ui" font-size="18" font-weight="700" fill="#334155">Asistente</text>

        <rect x="60" y="140" width="160" height="60" rx="8" fill="#F8FAFC" stroke="#E2E8F0" stroke-width="1"/>
        <text x="140" y="178" text-anchor="middle" font-family="system-ui" font-size="18" font-weight="700" fill="#334155">Agregado</text>

        <rect x="60" y="210" width="160" height="60" rx="8" fill="#F8FAFC" stroke="#E2E8F0" stroke-width="1"/>
        <text x="140" y="248" text-anchor="middle" font-family="system-ui" font-size="18" font-weight="700" fill="#334155">Asociado</text>

        <rect x="60" y="280" width="160" height="60" rx="8" fill="#F8FAFC" stroke="#E2E8F0" stroke-width="1"/>
        <text x="140" y="318" text-anchor="middle" font-family="system-ui" font-size="18" font-weight="700" fill="#334155">Titular</text>
      </g>

      <!-- Grid Matrix Lines -->
      <g stroke="#E2E8F0" stroke-width="1.5">
        <line x1="260" y1="20" x2="1160" y2="20"/>
        <line x1="260" y1="90" x2="1160" y2="90"/>
        <line x1="260" y1="160" x2="1160" y2="160"/>
        <line x1="260" y1="230" x2="1160" y2="230"/>
        <line x1="260" y1="300" x2="1160" y2="300"/>
        <line x1="260" y1="370" x2="1160" y2="370"/>

        <line x1="260" y1="20" x2="260" y2="370"/>
        <line x1="485" y1="20" x2="485" y2="370"/>
        <line x1="710" y1="20" x2="710" y2="370"/>
        <line x1="935" y1="20" x2="935" y2="370"/>
        <line x1="1160" y1="20" x2="1160" y2="370"/>
      </g>

      <!-- Badges in Grid -->
      <!-- Instructor + EX (Blocked) -->
      <g transform="translate(1045, 45)">
        <circle cx="0" cy="0" r="14" fill="#FEE2E2"/>
        <text x="0" y="6" text-anchor="middle" font-size="14">⚠️</text>
        <text x="0" y="28" text-anchor="middle" font-family="system-ui" font-size="12" font-weight="700" fill="#DC2626">Validación Bloqueada</text>
      </g>

      <!-- Agregado + TC (Tope 40h) -->
      <g transform="translate(820, 185)">
        <circle cx="0" cy="0" r="14" fill="#FEF3C7"/>
        <text x="0" y="6" text-anchor="middle" font-size="14">ℹ️</text>
        <text x="0" y="28" text-anchor="middle" font-family="system-ui" font-size="12" font-weight="700" fill="#D97706">Tope de 40 horas</text>
      </g>

      <!-- Titular + EX (Doctorado) -->
      <g transform="translate(1045, 325)">
        <circle cx="0" cy="0" r="14" fill="#D1FAE5"/>
        <text x="0" y="6" text-anchor="middle" font-size="14">✅</text>
        <text x="0" y="28" text-anchor="middle" font-family="system-ui" font-size="12" font-weight="700" fill="#059669">Requiere Doctorado</text>
      </g>
    </g>

    <!-- Right Side Callout Card (Gold / Amber) -->
    <g transform="translate(1220, 0)">
      <rect width="540" height="460" rx="16" fill="#D97706" filter="url(#cardShadow)"/>
      <text x="40" y="80" font-family="system-ui" font-size="28" font-weight="900" fill="#FFFFFF">Seguimiento</text>
      <text x="40" y="115" font-family="system-ui" font-size="28" font-weight="900" fill="#FFFFFF">cronológico</text>
      <text x="40" y="150" font-family="system-ui" font-size="28" font-weight="900" fill="#FFFFFF">preciso de años</text>
      <text x="40" y="185" font-family="system-ui" font-size="28" font-weight="900" fill="#FFFFFF">de servicio y</text>
      <text x="40" y="220" font-family="system-ui" font-size="28" font-weight="900" fill="#FFFFFF">validación</text>
      <text x="40" y="255" font-family="system-ui" font-size="28" font-weight="900" fill="#FFFFFF">estricta de</text>
      <text x="40" y="290" font-family="system-ui" font-size="28" font-weight="900" fill="#FFFFFF">requisitos de</text>
      <text x="40" y="325" font-family="system-ui" font-size="28" font-weight="900" fill="#FFFFFF">ascenso según</text>
      <text x="40" y="360" font-family="system-ui" font-size="28" font-weight="900" fill="#FFFFFF">la normativa</text>
      <text x="40" y="395" font-family="system-ui" font-size="28" font-weight="900" fill="#FFFFFF">institucional.</text>
    </g>
  </g>`,
  4
);

// SLIDE 5: El Ciclo de Vida Documental: Del Dato a la Certificación Oficial
const s5 = baseSlide(
  'El Ciclo de Vida Documental: Del Dato a la Certificación Oficial',
  'La verdadera potencia de SIGEDOR radica en materializar datos digitales en documentos con validez legal instantánea. Un cambio en el expediente desencadena la generación automatizada de constancias en PDF.',
  `<g transform="translate(80, 240)">
    <!-- 3 Step Containers -->
    <!-- 1. Evento -->
    <g transform="translate(20, 0)">
      <rect width="460" height="340" rx="16" fill="#FFFFFF" stroke="#CBD5E1" stroke-width="1.5" filter="url(#cardShadow)"/>
      <rect x="0" y="0" width="460" height="48" rx="16" fill="#003366"/>
      <text x="25" y="32" font-family="system-ui" font-size="16" font-weight="700" fill="#FFFFFF">Panel de Control</text>

      <!-- Toggle Switch & Button -->
      <rect x="40" y="90" width="70" height="36" rx="18" fill="#0284C7"/>
      <circle cx="85" cy="108" r="14" fill="#FFFFFF"/>

      <line x1="140" y1="108" x2="400" y2="108" stroke="#E2E8F0" stroke-width="6" stroke-linecap="round"/>

      <rect x="230" y="190" width="190" height="48" rx="10" fill="#059669"/>
      <text x="325" y="220" text-anchor="middle" font-family="system-ui" font-size="15" font-weight="800" fill="#FFFFFF">Guardar y Procesar ✓</text>

      <text x="230" y="390" text-anchor="middle" font-family="system-ui" font-size="26" font-weight="900" fill="#0F172A">1. Evento</text>
      <text x="230" y="420" text-anchor="middle" font-family="system-ui" font-size="16" fill="#475569">Disparado por la interfaz</text>
      <text x="230" y="445" text-anchor="middle" font-family="system-ui" font-size="16" fill="#475569">de usuario Filament / Web.</text>
    </g>

    <!-- Arrow 1 -->
    <path d="M 520 170 L 590 170 L 590 150 L 630 170 L 590 190 L 590 170" fill="#0284C7"/>

    <!-- 2. Sincronización -->
    <g transform="translate(670, 0)">
      <g transform="translate(230, 150)">
        <circle cx="-50" cy="0" r="45" fill="#F59E0B"/>
        <text x="-50" y="12" text-anchor="middle" font-size="34">⚙️</text>
        
        <rect x="20" y="-45" width="90" height="90" rx="12" fill="#1E293B"/>
        <text x="65" y="12" text-anchor="middle" font-size="34">🗄️</text>
      </g>

      <text x="230" y="390" text-anchor="middle" font-family="system-ui" font-size="26" font-weight="900" fill="#0F172A">2. Sincronización</text>
      <text x="230" y="420" text-anchor="middle" font-family="system-ui" font-size="16" fill="#475569">Eloquent Hook actualiza al instante</text>
      <text x="230" y="445" text-anchor="middle" font-family="system-ui" font-size="16" fill="#475569">las tablas del perfil.</text>
    </g>

    <!-- Arrow 2 -->
    <path d="M 1170 170 L 1240 170 L 1240 150 L 1280 170 L 1240 190 L 1240 170" fill="#0284C7"/>

    <!-- 3. Certificación -->
    <g transform="translate(1320, 0)">
      <!-- A4 Sheet Paper Mockup -->
      <g transform="translate(120, 10)">
        <rect width="220" height="280" rx="8" fill="#FFFFFF" stroke="#CBD5E1" stroke-width="2" filter="url(#cardShadow)"/>
        <!-- Header logo / lines -->
        <rect x="20" y="20" width="40" height="20" fill="#003366"/>
        <text x="70" y="35" font-family="system-ui" font-size="10" font-weight="800" fill="#003366">UNERG</text>
        <line x1="20" y1="50" x2="200" y2="50" stroke="#CBD5E1" stroke-width="2"/>
        
        <line x1="20" y1="75" x2="200" y2="75" stroke="#E2E8F0" stroke-width="4" stroke-linecap="round"/>
        <line x1="20" y1="95" x2="180" y2="95" stroke="#E2E8F0" stroke-width="4" stroke-linecap="round"/>
        <line x1="20" y1="115" x2="190" y2="115" stroke="#E2E8F0" stroke-width="4" stroke-linecap="round"/>
        <line x1="20" y1="135" x2="160" y2="135" stroke="#E2E8F0" stroke-width="4" stroke-linecap="round"/>

        <!-- QR Code & Badge -->
        <rect x="150" y="200" width="50" height="50" fill="#0F172A"/>
        <circle cx="55" cy="225" r="22" fill="#D97706"/>
        <text x="55" y="232" text-anchor="middle" font-size="16">✓</text>
        <text x="110" y="268" text-anchor="middle" font-family="monospace" font-size="8" fill="#64748B">UNERG-REP-YYYY-XXXX</text>
      </g>

      <text x="230" y="390" text-anchor="middle" font-family="system-ui" font-size="26" font-weight="900" fill="#0F172A">3. Certificación</text>
      
      <!-- Sub-card info -->
      <g transform="translate(0, 415)">
        <rect width="460" height="90" rx="10" fill="#FFFFFF" stroke="#CBD5E1" stroke-width="1.5"/>
        <text x="20" y="32" font-family="system-ui" font-size="14" fill="#1E293B">
          &bull; <tspan font-weight="bold">Motor:</tspan> barryvdh/laravel-dompdf (Soporte A4, Membrete Oficial)
        </text>
        <text x="20" y="62" font-family="system-ui" font-size="14" fill="#1E293B">
          &bull; <tspan font-weight="bold">Seguridad:</tspan> Sello criptográfico y código de verificación: UNERG-REP-YYYY-XXXX
        </text>
      </g>
    </g>
  </g>`,
  5
);

// SLIDE 6: Arquitectura Multi-Inquilino: Aislamiento Territorial Dinámico
const s6 = baseSlide(
  'Arquitectura Multi-Inquilino: Aislamiento Territorial Dinámico',
  'Garantizar la privacidad de los datos es crítico. SIGEDOR utiliza Dynamic Eloquent Scopes acoplados al rol del usuario para filtrar el acceso a nivel de base de datos.',
  `<g transform="translate(80, 220)">
    <!-- Funnel Diagram (Left) -->
    <g transform="translate(100, 20)">
      <text x="400" y="30" text-anchor="middle" font-family="system-ui" font-size="22" font-weight="900" fill="#0F172A">Datos Globales Universitarios</text>
      
      <!-- Global Data Elements -->
      <g transform="translate(200, 60)">
        <circle cx="50" cy="20" r="10" fill="#3B82F6"/>
        <rect x="120" y="10" width="20" height="20" fill="#F59E0B"/>
        <polygon points="210,10 225,30 195,30" fill="#EF4444"/>
        <circle cx="280" cy="20" r="10" fill="#10B981"/>
        <rect x="350" y="10" width="20" height="20" fill="#8B5CF6"/>
      </g>

      <!-- Funnel Cone -->
      <polygon points="120,120 680,120 460,340 340,340" fill="#EFF6FF" stroke="#3B82F6" stroke-width="3"/>
      
      <rect x="170" y="195" width="460" height="46" rx="23" fill="#1E3A8A"/>
      <text x="400" y="225" text-anchor="middle" font-family="system-ui" font-size="16" font-weight="800" fill="#FFFFFF">
        Filtro de Sede/Área - Jefe de Área Salud
      </text>

      <!-- Bottom Container (Isolated View) -->
      <g transform="translate(180, 390)">
        <rect width="440" height="150" rx="14" fill="#FFFFFF" stroke="#003366" stroke-width="2" filter="url(#cardShadow)"/>
        <rect x="0" y="0" width="440" height="30" rx="14" fill="#003366"/>
        <!-- Filtered Elements -->
        <circle cx="160" cy="70" r="10" fill="#F59E0B"/>
        <circle cx="200" cy="70" r="10" fill="#F59E0B"/>
        <circle cx="240" cy="70" r="10" fill="#F59E0B"/>
        <circle cx="280" cy="70" r="10" fill="#F59E0B"/>
        <circle cx="180" cy="105" r="10" fill="#F59E0B"/>
        <circle cx="220" cy="105" r="10" fill="#F59E0B"/>
        <circle cx="260" cy="105" r="10" fill="#F59E0B"/>
      </g>
      <text x="40" y="470" font-family="system-ui" font-size="20" font-weight="900" fill="#0F172A">Vista Aislada del Usuario</text>
    </g>

    <!-- Right Side Panel: Pilares del Aislamiento -->
    <g transform="translate(1080, 20)">
      <rect width="680" height="540" rx="20" fill="#FFFFFF" stroke="#CBD5E1" stroke-width="1.5" filter="url(#cardShadow)"/>
      <text x="340" y="60" text-anchor="middle" font-family="system-ui" font-size="24" font-weight="900" fill="#0F172A">Pilares del Aislamiento</text>

      <!-- Pillar 1 -->
      <g transform="translate(40, 110)">
        <text x="0" y="30" font-family="system-ui" font-size="20" font-weight="800" fill="#0284C7">Visibilidad Contextual</text>
        <text x="0" y="60" font-family="system-ui" font-size="16" fill="#475569">El panel adapta sus consultas SQL en milisegundos.</text>
      </g>
      <line x1="40" y1="210" x2="640" y2="210" stroke="#E2E8F0" stroke-width="1.5"/>

      <!-- Pillar 2 -->
      <g transform="translate(40, 240)">
        <text x="0" y="30" font-family="system-ui" font-size="20" font-weight="800" fill="#0284C7">Barrera de Sede</text>
        <text x="0" y="60" font-family="system-ui" font-size="16" fill="#475569">Un Jefe de Área visualiza exclusivamente los expedientes</text>
        <text x="0" y="85" font-family="system-ui" font-size="16" fill="#475569">de su recinto geográfico.</text>
      </g>
      <line x1="40" y1="365" x2="640" y2="365" stroke="#E2E8F0" stroke-width="1.5"/>

      <!-- Pillar 3 -->
      <g transform="translate(40, 395)">
        <text x="0" y="30" font-family="system-ui" font-size="20" font-weight="800" fill="#0284C7">Prevención de Fugas</text>
        <text x="0" y="60" font-family="system-ui" font-size="16" fill="#475569">Filtrado estructural, imposible de evadir desde</text>
        <text x="0" y="85" font-family="system-ui" font-size="16" fill="#475569">la interfaz de usuario.</text>
      </g>
    </g>
  </g>`,
  6
);

// SLIDE 7: Matriz de Control de Acceso Basado en Roles (RBAC)
const s7 = baseSlide(
  'Matriz de Control de Acceso Basado en Roles (RBAC)',
  'Implementado sobre spatie/laravel-permission, el sistema soporta Múltiples Roles (e.g., un Jefe de Área que también ejerce como Docente), gestionando la superposición de permisos de forma fluida y segura.',
  `<g transform="translate(80, 220)">
    <!-- RBAC Matrix Table -->
    <rect width="1760" height="520" rx="16" fill="#FFFFFF" stroke="#CBD5E1" stroke-width="2" filter="url(#cardShadow)"/>

    <!-- Headers -->
    <rect x="0" y="0" width="1760" height="90" rx="16" fill="#F8FAFC"/>
    <text x="490" y="55" text-anchor="middle" font-family="system-ui" font-size="22" font-weight="900" fill="#0F172A">Super Administrador</text>
    <text x="980" y="55" text-anchor="middle" font-family="system-ui" font-size="22" font-weight="900" fill="#0284C7">Jefe de Área</text>
    <text x="1460" y="55" text-anchor="middle" font-family="system-ui" font-size="22" font-weight="900" fill="#64748B">Docente Académico</text>

    <!-- Grid lines -->
    <g stroke="#E2E8F0" stroke-width="1.5">
      <line x1="0" y1="90" x2="1760" y2="90"/>
      <line x1="0" y1="195" x2="1760" y2="195"/>
      <line x1="0" y1="300" x2="1760" y2="300"/>
      <line x1="0" y1="405" x2="1760" y2="405"/>

      <line x1="320" y1="0" x2="320" y2="520"/>
      <line x1="780" y1="0" x2="780" y2="520"/>
      <line x1="1240" y1="0" x2="1240" y2="520"/>
    </g>

    <!-- Row 1: Alcance de Visibilidad -->
    <text x="30" y="150" font-family="system-ui" font-size="18" font-weight="800" fill="#0F172A">Alcance de Visibilidad</text>
    <text x="490" y="150" text-anchor="middle" font-family="system-ui" font-size="17" fill="#334155">Global Institucional</text>
    <text x="980" y="140" text-anchor="middle" font-family="system-ui" font-size="16" fill="#334155">Restringido por</text>
    <text x="980" y="165" text-anchor="middle" font-family="system-ui" font-size="16" fill="#334155">Sede/Área</text>
    <text x="1460" y="150" text-anchor="middle" font-family="system-ui" font-size="17" fill="#334155">Exclusivo Personal</text>

    <!-- Row 2: Edición de Expedientes -->
    <text x="30" y="255" font-family="system-ui" font-size="18" font-weight="800" fill="#0F172A">Edición de Expedientes</text>
    <text x="490" y="245" text-anchor="middle" font-family="system-ui" font-size="17" font-weight="700" fill="#334155">Total</text>
    <text x="490" y="270" text-anchor="middle" font-family="system-ui" font-size="15" fill="#64748B">(Lectura/Escritura)</text>
    <text x="980" y="245" text-anchor="middle" font-family="system-ui" font-size="17" font-weight="700" fill="#334155">Parcial</text>
    <text x="980" y="270" text-anchor="middle" font-family="system-ui" font-size="15" fill="#64748B">(Aprobación/Lectura)</text>
    <text x="1460" y="255" text-anchor="middle" font-family="system-ui" font-size="17" fill="#334155">Solo Lectura</text>

    <!-- Row 3: Gestión de Usuarios y Roles -->
    <text x="30" y="360" font-family="system-ui" font-size="18" font-weight="800" fill="#0F172A">Gestión de Usuarios y Roles</text>
    <text x="490" y="360" text-anchor="middle" font-family="system-ui" font-size="18" font-weight="700" fill="#059669">✅ <tspan fill="#475569" font-size="15">(con toggle is_active)</tspan></text>
    <text x="980" y="360" text-anchor="middle" font-size="22" fill="#DC2626">❌</text>
    <text x="1460" y="360" text-anchor="middle" font-size="22" fill="#DC2626">❌</text>

    <!-- Row 4: Generación de Reportes -->
    <text x="30" y="470" font-family="system-ui" font-size="18" font-weight="800" fill="#0F172A">Generación de Reportes</text>
    <text x="490" y="470" text-anchor="middle" font-family="system-ui" font-size="17" fill="#334155">Masivo e Individual</text>
    <text x="980" y="460" text-anchor="middle" font-family="system-ui" font-size="16" fill="#334155">Individual</text>
    <text x="980" y="485" text-anchor="middle" font-family="system-ui" font-size="14" fill="#64748B">(Solo su Sede)</text>
    <text x="1460" y="460" text-anchor="middle" font-family="system-ui" font-size="16" fill="#334155">Personal</text>
    <text x="1460" y="485" text-anchor="middle" font-family="system-ui" font-size="14" fill="#64748B">(Solo sus Constancias)</text>
  </g>`,
  7
);

// SLIDE 8: Interfaz Universal: Experiencia Multidispositivo (v2.0.0)
const s8 = baseSlide(
  'Interfaz Universal: Experiencia Multidispositivo (v2.0.0)',
  'La capa de presentación, potenciada por Filament v3, ofrece una experiencia ergonómica y responsiva sin importar el hardware de acceso.',
  `<g transform="translate(80, 220)">
    <!-- Left Graphic: Desktop Monitor and Smartphone -->
    <g transform="translate(40, 20)">
      <!-- Monitor Stand -->
      <rect x="420" y="450" width="80" height="50" fill="#CBD5E1"/>
      <ellipse cx="460" cy="500" rx="140" ry="12" fill="#94A3B8"/>
      
      <!-- Monitor Screen -->
      <rect x="40" y="0" width="760" height="450" rx="16" fill="#0F172A" stroke="#334155" stroke-width="4" filter="url(#cardShadow)"/>
      <rect x="55" y="15" width="730" height="420" rx="10" fill="#FFFFFF"/>

      <!-- Dense Audit Table UI Mockup -->
      <rect x="55" y="15" width="160" height="420" fill="#0F172A"/>
      <text x="75" y="45" font-family="system-ui" font-size="12" font-weight="800" fill="#FFFFFF">Dense Audit Table</text>
      <rect x="230" y="35" width="535" height="30" rx="6" fill="#F1F5F9"/>
      
      <g transform="translate(230, 80)">
        <line x1="0" y1="0" x2="535" y2="0" stroke="#E2E8F0" stroke-width="2"/>
        <line x1="0" y1="40" x2="535" y2="40" stroke="#E2E8F0" stroke-width="1"/>
        <line x1="0" y1="80" x2="535" y2="80" stroke="#E2E8F0" stroke-width="1"/>
        <line x1="0" y1="120" x2="535" y2="120" stroke="#E2E8F0" stroke-width="1"/>
        <line x1="0" y1="160" x2="535" y2="160" stroke="#E2E8F0" stroke-width="1"/>
        <line x1="0" y1="200" x2="535" y2="200" stroke="#E2E8F0" stroke-width="1"/>
        <line x1="0" y1="240" x2="535" y2="240" stroke="#E2E8F0" stroke-width="1"/>
      </g>

      <!-- Smartphone in Foreground -->
      <g transform="translate(720, 100)">
        <rect width="250" height="450" rx="36" fill="#0F172A" stroke="#334155" stroke-width="4" filter="url(#cardShadow)"/>
        <rect x="12" y="12" width="226" height="426" rx="28" fill="#FFFFFF"/>
        <!-- Notch -->
        <rect x="80" y="18" width="90" height="12" rx="6" fill="#0F172A"/>
        
        <!-- Cards inside mobile -->
        <rect x="25" y="60" width="200" height="70" rx="10" fill="#FEF3C7" stroke="#F59E0B" stroke-width="1"/>
        <rect x="25" y="145" width="200" height="70" rx="10" fill="#F8FAFC" stroke="#E2E8F0" stroke-width="1"/>
        <rect x="25" y="230" width="200" height="70" rx="10" fill="#F8FAFC" stroke="#E2E8F0" stroke-width="1"/>
        
        <!-- Bottom bar -->
        <rect x="12" y="385" width="226" height="53" fill="#0F172A"/>
        <circle cx="50" cy="410" r="10" fill="#38BDF8"/>
        <circle cx="125" cy="410" r="10" fill="#64748B"/>
        <circle cx="200" cy="410" r="10" fill="#64748B"/>
      </g>
    </g>

    <!-- Right Side Details Cards -->
    <g transform="translate(1120, 40)">
      <!-- Card 1 -->
      <g transform="translate(0, 0)">
        <rect width="640" height="150" rx="16" fill="#FFFFFF" stroke="#003366" stroke-width="2" filter="url(#cardShadow)"/>
        <circle cx="40" cy="40" r="8" fill="#003366"/>
        <text x="60" y="45" font-family="system-ui" font-size="20" font-weight="900" fill="#0F172A">Modo Tabla (Auditoría):</text>
        <text x="60" y="78" font-family="system-ui" font-size="16" fill="#475569">Diseñado para monitores 4K y</text>
        <text x="60" y="104" font-family="system-ui" font-size="16" fill="#475569">administradores que requieren densidad de datos.</text>
      </g>

      <!-- Card 2 -->
      <g transform="translate(0, 180)">
        <rect width="640" height="150" rx="16" fill="#FFFFFF" stroke="#003366" stroke-width="2" filter="url(#cardShadow)"/>
        <circle cx="40" cy="40" r="8" fill="#003366"/>
        <text x="60" y="45" font-family="system-ui" font-size="20" font-weight="900" fill="#0F172A">Modo Cuadrícula (Táctil):</text>
        <text x="60" y="78" font-family="system-ui" font-size="16" fill="#475569">Optimizado para smartphones (hasta 320px) con</text>
        <text x="60" y="104" font-family="system-ui" font-size="16" fill="#475569">barra inferior fija y cajones contextuales.</text>
      </g>

      <!-- Card 3 -->
      <g transform="translate(0, 360)">
        <rect width="640" height="150" rx="16" fill="#FFFFFF" stroke="#003366" stroke-width="2" filter="url(#cardShadow)"/>
        <circle cx="40" cy="40" r="8" fill="#003366"/>
        <text x="60" y="45" font-family="system-ui" font-size="20" font-weight="900" fill="#0F172A">Accesibilidad:</text>
        <text x="60" y="78" font-family="system-ui" font-size="16" fill="#475569">Interfaz rápida y diseñada para reducir la fatiga</text>
        <text x="60" y="104" font-family="system-ui" font-size="16" fill="#475569">visual administrativa.</text>
      </g>
    </g>
  </g>`,
  8
);

// SLIDE 9: Ingesta de Datos: Desde Archivos Planos a Integridad Relacional
const s9 = baseSlide(
  'Ingesta de Datos: Desde Archivos Planos a Integridad Relacional',
  'SIGEDOR resuelve la migración de datos históricos mediante un pipeline de inyección estructurado. Los Seeders transforman conjuntos de datos estáticos en relaciones dinámicas sin comprometer la integridad estructural.',
  `<g transform="translate(80, 220)">
    <!-- Diagram Flow -->
    <g transform="translate(40, 40)">
      <!-- Left CSV Files -->
      <g transform="translate(0, 0)">
        <rect x="0" y="0" width="180" height="56" rx="8" fill="#003366"/>
        <text x="90" y="35" text-anchor="middle" font-family="monospace" font-size="16" font-weight="700" fill="#FFFFFF">users.csv</text>

        <rect x="0" y="110" width="180" height="56" rx="8" fill="#003366"/>
        <text x="90" y="145" text-anchor="middle" font-family="monospace" font-size="16" font-weight="700" fill="#FFFFFF">teachers.csv</text>

        <rect x="0" y="220" width="180" height="56" rx="8" fill="#003366"/>
        <text x="90" y="255" text-anchor="middle" font-family="monospace" font-size="16" font-weight="700" fill="#FFFFFF">sites.csv</text>
      </g>

      <!-- Center Pipeline Box -->
      <g transform="translate(340, 20)">
        <rect width="640" height="240" rx="16" fill="#F8FAFC" stroke="#003366" stroke-width="2" stroke-dasharray="6 6"/>
        <text x="320" y="45" text-anchor="middle" font-family="system-ui" font-size="22" font-weight="900" fill="#0F172A">Pipeline de Ingesta Automatizado</text>

        <g transform="translate(200, 110)">
          <circle cx="0" cy="0" r="30" fill="#3B82F6"/>
          <text x="0" y="10" text-anchor="middle" font-size="24">⚙️</text>
          
          <circle cx="120" cy="0" r="30" fill="#003366"/>
          <text x="120" y="10" text-anchor="middle" font-size="24">🔄</text>

          <circle cx="240" cy="0" r="30" fill="#10B981"/>
          <text x="240" y="10" text-anchor="middle" font-size="24">✓</text>
        </g>
      </g>

      <!-- Right Database Disks -->
      <g transform="translate(1200, 0)">
        <ellipse cx="100" cy="40" rx="90" ry="25" fill="#1E293B"/>
        <ellipse cx="100" cy="110" rx="90" ry="25" fill="#1E293B"/>
        <ellipse cx="100" cy="180" rx="90" ry="25" fill="#1E293B"/>
        <ellipse cx="100" cy="250" rx="90" ry="25" fill="#003366"/>
      </g>

      <!-- Arrows -->
      <path d="M 180 28 L 340 100" fill="none" stroke="#F59E0B" stroke-width="3"/>
      <path d="M 180 138 L 340 140" fill="none" stroke="#F59E0B" stroke-width="3"/>
      <path d="M 180 248 L 340 180" fill="none" stroke="#F59E0B" stroke-width="3"/>
      <path d="M 980 140 L 1190 140" fill="none" stroke="#F59E0B" stroke-width="3"/>
    </g>

    <!-- Bottom 3 Breakdown Cards -->
    <g transform="translate(0, 420)">
      <g transform="translate(0, 0)">
        <rect width="560" height="150" rx="16" fill="#FFFFFF" stroke="#CBD5E1" stroke-width="1.5" filter="url(#cardShadow)"/>
        <text x="30" y="45" font-family="system-ui" font-size="18" font-weight="900" fill="#0F172A">users.csv: <tspan font-weight="normal" fill="#475569">Hasheo de</tspan></text>
        <text x="30" y="75" font-family="system-ui" font-size="16" fill="#475569">contraseñas y asignación de</text>
        <text x="30" y="105" font-family="system-ui" font-size="16" fill="#475569">roles.</text>
      </g>

      <g transform="translate(600, 0)">
        <rect width="560" height="150" rx="16" fill="#FFFFFF" stroke="#CBD5E1" stroke-width="1.5" filter="url(#cardShadow)"/>
        <text x="30" y="45" font-family="system-ui" font-size="18" font-weight="900" fill="#0F172A">teachers.csv &amp; categories.csv:</text>
        <text x="30" y="75" font-family="system-ui" font-size="16" fill="#475569">Expedientes demográficos e</text>
        <text x="30" y="105" font-family="system-ui" font-size="16" fill="#475569">histórico de ascensos.</text>
      </g>

      <g transform="translate(1200, 0)">
        <rect width="560" height="150" rx="16" fill="#FFFFFF" stroke="#CBD5E1" stroke-width="1.5" filter="url(#cardShadow)"/>
        <text x="30" y="45" font-family="system-ui" font-size="18" font-weight="900" fill="#0F172A">dedications.csv &amp; sites.csv:</text>
        <text x="30" y="75" font-family="system-ui" font-size="16" fill="#475569">Asignaciones de carga horaria y</text>
        <text x="30" y="105" font-family="system-ui" font-size="16" fill="#475569">adscripción territorial.</text>
      </g>
    </g>
  </g>`,
  9
);

// SLIDE 10: Interoperabilidad Estándar: API RESTful OpenAPI 3.0
const s10 = baseSlide(
  'Interoperabilidad Estándar: API RESTful OpenAPI 3.0',
  'El sistema funciona como la "Fuente de la Verdad" académica, exponiendo datos de manera segura para sistemas externos o aplicaciones móviles institucionales.',
  `<g transform="translate(80, 220)">
    <!-- Diagram Flow -->
    <g transform="translate(40, 20)">
      <!-- Left systems -->
      <g transform="translate(0, 40)">
        <rect width="320" height="70" rx="10" fill="#FFFFFF" stroke="#003366" stroke-width="3"/>
        <text x="160" y="45" text-anchor="middle" font-family="system-ui" font-size="22" font-weight="900" fill="#0F172A">Nómina</text>
      </g>
      <g transform="translate(0, 160)">
        <rect width="320" height="70" rx="10" fill="#FFFFFF" stroke="#003366" stroke-width="3"/>
        <text x="160" y="45" text-anchor="middle" font-family="system-ui" font-size="22" font-weight="900" fill="#0F172A">Control de Estudios</text>
      </g>

      <!-- Center Hub -->
      <g transform="translate(680, 135)">
        <circle cx="0" cy="0" r="140" fill="none" stroke="#E2E8F0" stroke-width="2"/>
        <circle cx="0" cy="0" r="90" fill="#003366" filter="url(#cardShadow)"/>
        <text x="0" y="8" text-anchor="middle" font-family="system-ui" font-size="22" font-weight="900" fill="#FFFFFF">SIGEDOR</text>
      </g>

      <!-- Arrows bidirectional -->
      <path d="M 330 75 L 580 110" fill="none" stroke="#3B82F6" stroke-width="4"/>
      <path d="M 330 195 L 580 160" fill="none" stroke="#3B82F6" stroke-width="4"/>
      <path d="M 780 135 L 1050 135" fill="none" stroke="#F59E0B" stroke-width="4"/>

      <!-- Right Swagger Card -->
      <g transform="translate(1080, 0)">
        <rect width="560" height="270" rx="14" fill="#FFFFFF" stroke="#003366" stroke-width="2" filter="url(#cardShadow)"/>
        <rect x="0" y="0" width="560" height="42" rx="14" fill="#003366"/>
        <text x="25" y="27" font-family="system-ui" font-size="15" font-weight="700" fill="#FFFFFF">Swagger UI - Documentación Interactiva</text>

        <!-- Swagger Endpoints -->
        <g transform="translate(20, 60)">
          <rect width="520" height="45" rx="6" fill="#F0FDF4" stroke="#86EFAC" stroke-width="1"/>
          <rect x="10" y="10" width="55" height="25" rx="4" fill="#10B981"/>
          <text x="37" y="27" text-anchor="middle" font-family="monospace" font-size="12" font-weight="800" fill="#FFFFFF">GET</text>
          <text x="80" y="27" font-family="monospace" font-size="14" font-weight="700" fill="#1E293B">/api/v1/teachers</text>
        </g>

        <g transform="translate(20, 115)">
          <rect width="520" height="45" rx="6" fill="#F0FDF4" stroke="#86EFAC" stroke-width="1"/>
          <rect x="10" y="10" width="55" height="25" rx="4" fill="#10B981"/>
          <text x="37" y="27" text-anchor="middle" font-family="monospace" font-size="12" font-weight="800" fill="#FFFFFF">GET</text>
          <text x="80" y="27" font-family="monospace" font-size="14" font-weight="700" fill="#1E293B">/api/v1/reports</text>
        </g>

        <g transform="translate(20, 170)">
          <rect width="520" height="45" rx="6" fill="#EFF6FF" stroke="#93C5FD" stroke-width="1"/>
          <rect x="10" y="10" width="55" height="25" rx="4" fill="#2563EB"/>
          <text x="37" y="27" text-anchor="middle" font-family="monospace" font-size="12" font-weight="800" fill="#FFFFFF">GET</text>
          <text x="80" y="27" font-family="monospace" font-size="14" font-weight="700" fill="#1E293B">/api/ping</text>
        </g>
      </g>
    </g>

    <!-- Bottom 3 Breakdown Cards -->
    <g transform="translate(0, 400)">
      <g transform="translate(0, 0)">
        <rect width="560" height="170" rx="16" fill="#FFFFFF" stroke="#CBD5E1" stroke-width="1.5" filter="url(#cardShadow)"/>
        <text x="30" y="45" font-family="system-ui" font-size="20" font-weight="900" fill="#0F172A">Endpoints Oficiales (v1)</text>
        <text x="30" y="80" font-family="system-ui" font-size="16" fill="#475569">Catálogo paginado en GET</text>
        <text x="30" y="105" font-family="system-ui" font-size="16" fill="#475569">/api/v1/teachers y verificación</text>
        <text x="30" y="130" font-family="system-ui" font-size="16" fill="#475569">criptográfica en GET /api/v1/reports.</text>
      </g>

      <g transform="translate(600, 0)">
        <rect width="560" height="170" rx="16" fill="#FFFFFF" stroke="#CBD5E1" stroke-width="1.5" filter="url(#cardShadow)"/>
        <text x="30" y="45" font-family="system-ui" font-size="20" font-weight="900" fill="#0F172A">Protección de PII</text>
        <text x="30" y="80" font-family="system-ui" font-size="16" fill="#475569">La capa pública limpia</text>
        <text x="30" y="105" font-family="system-ui" font-size="16" fill="#475569">automáticamente datos personales</text>
        <text x="30" y="130" font-family="system-ui" font-size="16" fill="#475569">sensibles antes de la respuesta.</text>
      </g>

      <g transform="translate(1200, 0)">
        <rect width="560" height="170" rx="16" fill="#FFFFFF" stroke="#CBD5E1" stroke-width="1.5" filter="url(#cardShadow)"/>
        <text x="30" y="45" font-family="system-ui" font-size="20" font-weight="900" fill="#0F172A">Swagger UI</text>
        <text x="30" y="80" font-family="system-ui" font-size="16" fill="#475569">Documentación viva e interactiva</text>
        <text x="30" y="105" font-family="system-ui" font-size="16" fill="#475569">integrada, generada dinámicamente</text>
        <text x="30" y="130" font-family="system-ui" font-size="16" fill="#475569">mediante anotaciones @OA.</text>
      </g>
    </g>
  </g>`,
  10
);

// SLIDE 11: Arquitectura en Tres Capas: Separación Estricta de Responsabilidades
const s11 = baseSlide(
  'Arquitectura en Tres Capas: Separación Estricta de Responsabilidades',
  'Un diseño de software desacoplado que garantiza mantenibilidad a largo plazo y rendimiento excepcional. El núcleo de Laravel 11 orquesta la lógica, mientras Filament renderiza y Spatie protege el perímetro.',
  `<g transform="translate(80, 220)">
    <!-- 3 Layer Stack Diagram (Left) -->
    <g transform="translate(40, 20)">
      <!-- Layer 1: Presentación -->
      <polygon points="120,40 880,40 800,140 40,140" fill="#38BDF8" filter="url(#cardShadow)"/>
      <text x="460" y="80" text-anchor="middle" font-family="system-ui" font-size="24" font-weight="900" fill="#0F172A">Capa de Presentación</text>
      <text x="460" y="115" text-anchor="middle" font-family="system-ui" font-size="14" font-weight="700" fill="#1E293B">
        Filament v3 Panel &bull; Vistas Blade &bull; DomPDF Engine
      </text>

      <!-- Down Arrow -->
      <path d="M 460 140 L 460 170" stroke="#003366" stroke-width="4"/>

      <!-- Layer 2: Lógica de Negocio -->
      <polygon points="120,180 880,180 800,280 40,280" fill="#F59E0B" filter="url(#cardShadow)"/>
      <text x="460" y="220" text-anchor="middle" font-family="system-ui" font-size="24" font-weight="900" fill="#0F172A">Lógica de Negocio y Seguridad</text>
      <text x="460" y="255" text-anchor="middle" font-family="system-ui" font-size="14" font-weight="700" fill="#78350F">
        Núcleo Laravel 11 &bull; Spatie Roles &amp; Permisos &bull; Modelos Eloquent
      </text>

      <!-- Down Arrow -->
      <path d="M 460 280 L 460 310" stroke="#003366" stroke-width="4"/>

      <!-- Layer 3: Persistencia -->
      <polygon points="120,320 880,320 800,420 40,420" fill="#60A5FA" filter="url(#cardShadow)"/>
      <text x="460" y="360" text-anchor="middle" font-family="system-ui" font-size="24" font-weight="900" fill="#0F172A">Persistencia de Datos</text>
      <text x="460" y="395" text-anchor="middle" font-family="system-ui" font-size="14" font-weight="700" fill="#1E3A8A">
        MySQL / MariaDB &bull; Relaciones 1:1, 1:N
      </text>
    </g>

    <!-- Right Side Callout Card: Ventaja Arquitectónica -->
    <g transform="translate(1050, 40)">
      <line x1="0" y1="0" x2="0" y2="400" stroke="#0F172A" stroke-width="4"/>
      <g transform="translate(30, 20)">
        <text x="0" y="40" font-family="system-ui" font-size="28" font-weight="900" fill="#0F172A">Ventaja Arquitectónica</text>
        <text x="0" y="90" font-family="system-ui" font-size="20" fill="#475569">El diseño modular facilita la</text>
        <text x="0" y="125" font-family="system-ui" font-size="20" fill="#475569">incorporación futura de nuevos</text>
        <text x="0" y="160" font-family="system-ui" font-size="20" fill="#475569">módulos (ej. concursos de</text>
        <text x="0" y="195" font-family="system-ui" font-size="20" fill="#475569">oposición, evaluaciones docentes)</text>
        <text x="0" y="230" font-family="system-ui" font-size="20" fill="#475569">sin alterar el código base existente,</text>
        <text x="0" y="265" font-family="system-ui" font-size="20" fill="#475569">asegurando escalabilidad técnica.</text>
      </g>
    </g>
  </g>`,
  11
);

// SLIDE 12: Fiabilidad Demostrada y Código Abierto
const s12 = baseSlide(
  'Fiabilidad Demostrada y Código Abierto',
  'Nacido como investigación universitaria y diseñado para escalar, SIGEDOR está validado por pruebas automatizadas exhaustivas y liberado a la comunidad para su adopción global.',
  `<g transform="translate(80, 240)">
    <!-- Left Card: 100% Tests -->
    <g transform="translate(80, 0)">
      <rect width="760" height="520" rx="20" fill="#FFFFFF" stroke="#CBD5E1" stroke-width="1.5" filter="url(#cardShadow)"/>
      
      <!-- Big 100% Circle -->
      <g transform="translate(380, 160)">
        <circle cx="0" cy="0" r="110" fill="none" stroke="#E2E8F0" stroke-width="12"/>
        <circle cx="0" cy="0" r="110" fill="none" stroke="#0284C7" stroke-width="12" stroke-dasharray="691 691"/>
        <text x="0" y="25" text-anchor="middle" font-family="system-ui" font-size="64" font-weight="900" fill="#0F172A">100%</text>
      </g>

      <text x="380" y="340" text-anchor="middle" font-family="system-ui" font-size="22" font-weight="900" fill="#0F172A">
        Pruebas Automatizadas (Pest/PHPUnit):
      </text>
      <text x="380" y="380" text-anchor="middle" font-family="system-ui" font-size="18" fill="#475569">
        49 Pruebas de integración y 372 Aserciones al
      </text>
      <text x="380" y="410" text-anchor="middle" font-family="system-ui" font-size="18" fill="#475569">
        100%. Validación de autenticación, políticas
      </text>
      <text x="380" y="440" text-anchor="middle" font-family="system-ui" font-size="18" fill="#475569">
        multi-inquilino y generación de PDF.
      </text>
    </g>

    <!-- Right Card: Licencia MIT -->
    <g transform="translate(920, 0)">
      <rect width="760" height="520" rx="20" fill="#FFFFFF" stroke="#CBD5E1" stroke-width="1.5" filter="url(#cardShadow)"/>

      <!-- Shield with Global Grid -->
      <g transform="translate(380, 160)">
        <circle cx="0" cy="0" r="110" fill="none" stroke="#D97706" stroke-width="2"/>
        <line x1="-110" y1="0" x2="110" y2="0" stroke="#D97706" stroke-width="1.5"/>
        <ellipse cx="0" cy="0" rx="60" ry="110" fill="none" stroke="#D97706" stroke-width="1.5"/>
        <path d="M 0 -70 L 60 -40 L 60 40 Q 0 90 0 90 Q -60 40 -60 40 L -60 -40 Z" fill="#003366"/>
      </g>

      <text x="380" y="340" text-anchor="middle" font-family="system-ui" font-size="22" font-weight="900" fill="#0F172A">
        Código Abierto bajo Licencia MIT:
      </text>
      <text x="380" y="380" text-anchor="middle" font-family="system-ui" font-size="18" fill="#475569">
        Desacoplado y listo para ser personalizado e
      </text>
      <text x="380" y="410" text-anchor="middle" font-family="system-ui" font-size="18" fill="#475569">
        implementado en otras instituciones universitarias de
      </text>
      <text x="380" y="440" text-anchor="middle" font-family="system-ui" font-size="18" fill="#475569">
        educación superior. Liberado para la comunidad.
      </text>
    </g>
  </g>`,
  12
);

// Write all 12 slides
const slides = [s1, s2, s3, s4, s5, s6, s7, s8, s9, s10, s11, s12];
slides.forEach((svg, index) => {
  fs.writeFileSync(path.join(slidesDir, `slide-${index + 1}.svg`), svg);
});

console.log('All 12 modern slides generated successfully!');
