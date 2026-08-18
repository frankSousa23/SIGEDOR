<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIGEDOR - Sistema de Gestión Docente y Reportes</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #0b0f19;
            color: #f1f5f9;
        }
        .gradient-glow {
            background: radial-gradient(circle at 50% 20%, rgba(245, 158, 11, 0.15), transparent 70%);
        }
        .card-glass {
            background: rgba(17, 24, 39, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
    </style>
</head>
<body class="min-h-screen relative overflow-x-hidden gradient-glow">
    <!-- Navbar -->
    <header class="border-b border-slate-800/80 sticky top-0 z-50 bg-slate-950/70 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="h-10 w-10 rounded-xl bg-amber-500/20 border border-amber-500/40 flex items-center justify-center font-black text-amber-400 text-xl shadow-lg shadow-amber-500/10">
                    S
                </div>
                <div>
                    <span class="text-xl font-bold tracking-tight bg-gradient-to-r from-white via-slate-200 to-amber-400 bg-clip-text text-transparent">SIGEDOR</span>
                    <span class="block text-[10px] uppercase font-semibold tracking-widest text-slate-400">Open Source Academic System</span>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ url('/admin/login') }}" class="px-5 py-2.5 rounded-xl text-sm font-semibold bg-amber-500 hover:bg-amber-400 text-slate-950 transition-all duration-200 shadow-lg shadow-amber-500/20 hover:scale-105 active:scale-95">
                    Acceder al Sistema
                </a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24">
        <div class="text-center max-w-3xl mx-auto">
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-amber-500/10 border border-amber-500/20 text-amber-400 text-xs font-semibold uppercase tracking-wider mb-6">
                <span>Versión Open Source MIT</span>
                <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                <span>Laravel 11 + Filament v3</span>
            </div>
            <h1 class="text-4xl sm:text-6xl font-extrabold tracking-tight leading-tight mb-6">
                Gestión Integral para <span class="bg-gradient-to-r from-amber-400 via-orange-400 to-amber-200 bg-clip-text text-transparent">Docentes Universitarios</span>
            </h1>
            <p class="text-lg sm:text-xl text-slate-400 font-normal mb-10 leading-relaxed">
                Plataforma integral para administración de expedientes académicos, control de escalafón docente, asignación horaria por sedes/áreas y generación automatizada de informes oficiales en PDF.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ url('/admin/login') }}" class="w-full sm:w-auto px-8 py-4 rounded-xl text-base font-bold bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 transition shadow-xl shadow-amber-500/20">
                    Iniciar Sesión en el Panel
                </a>
                <a href="https://github.com/frankSousa23/SIGEDOR" target="_blank" class="w-full sm:w-auto px-8 py-4 rounded-xl text-base font-semibold bg-slate-900/80 hover:bg-slate-800 border border-slate-700 text-slate-200 transition">
                    Ver en GitHub
                </a>
            </div>
        </div>

        <!-- Demo Accounts Card -->
        <div class="mt-20 card-glass rounded-2xl p-8 border border-slate-800">
            <h2 class="text-xl font-bold text-center text-slate-200 mb-6 flex items-center justify-center gap-2">
                <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                Credenciales Demostrativas para Pruebas
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Admin -->
                <div class="bg-slate-900/60 rounded-xl p-5 border border-slate-800/80">
                    <div class="text-xs font-bold uppercase tracking-wider text-amber-400 mb-1">Super Administrador</div>
                    <div class="text-slate-200 font-semibold mb-2">Acceso Total al Sistema</div>
                    <p class="text-xs text-slate-400 font-mono">admin@sigedor.com</p>
                    <p class="text-xs text-slate-400 font-mono">password</p>
                </div>
                <!-- Area Manager -->
                <div class="bg-slate-900/60 rounded-xl p-5 border border-slate-800/80">
                    <div class="text-xs font-bold uppercase tracking-wider text-orange-400 mb-1">Jefe de Área / Decano</div>
                    <div class="text-slate-200 font-semibold mb-2">Supervisión Departamental</div>
                    <p class="text-xs text-slate-400 font-mono">areamanager@sigedor.com</p>
                    <p class="text-xs text-slate-400 font-mono">password</p>
                </div>
                <!-- Teacher -->
                <div class="bg-slate-900/60 rounded-xl p-5 border border-slate-800/80">
                    <div class="text-xs font-bold uppercase tracking-wider text-emerald-400 mb-1">Docente Académico</div>
                    <div class="text-slate-200 font-semibold mb-2">Consulta de Expediente</div>
                    <p class="text-xs text-slate-400 font-mono">docente@sigedor.com</p>
                    <p class="text-xs text-slate-400 font-mono">password</p>
                </div>
            </div>
        </div>

        <!-- Key Modules Grid -->
        <div class="mt-20 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="card-glass rounded-2xl p-6">
                <div class="h-12 w-12 rounded-xl bg-amber-500/10 text-amber-400 flex items-center justify-center mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-200 mb-2">Escalafón y Categorías</h3>
                <p class="text-sm text-slate-400 leading-relaxed">
                    Control sistemático de ascensos (Instructor, Asistente, Agregado, Asociado, Titular), títulos universitarios y validación de reglas de ascenso.
                </p>
            </div>

            <div class="card-glass rounded-2xl p-6">
                <div class="h-12 w-12 rounded-xl bg-orange-500/10 text-orange-400 flex items-center justify-center mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-200 mb-2">Carga y Dedicación Horaria</h3>
                <p class="text-sm text-slate-400 leading-relaxed">
                    Distribución de horas semanales, cátedras, secciones y modalidades contractuales (Tiempo Convencional, Medio Tiempo, Tiempo Completo, Exclusiva).
                </p>
            </div>

            <div class="card-glass rounded-2xl p-6">
                <div class="h-12 w-12 rounded-xl bg-blue-500/10 text-blue-400 flex items-center justify-center mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-200 mb-2">Reportes y Memorandos PDF</h3>
                <p class="text-sm text-slate-400 leading-relaxed">
                    Emisión instantánea de constancias de trabajo, memorandos administrativos y expedientes individuales o masivos listos para imprimir.
                </p>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-800/80 py-8 text-center text-xs text-slate-500">
        <p>SIGEDOR - Desarrollado por Frank Sousa. Liberado bajo Licencia MIT.</p>
    </footer>
</body>
</html>
