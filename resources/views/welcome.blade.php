<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIGEDOR - Sistema de Gestión Docente</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #0f172a;
            color: #e2e8f0;
            background-image: linear-gradient(rgba(15, 23, 42, 0.9), rgba(15, 23, 42, 0.9)), url('{{ asset('images/FondoUnerg.jpg') }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        .gradient-text {
            background: linear-gradient(45deg, #3b82f6, #8b5cf6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .image-container img {
            transition: transform 0.3s ease;
        }
        .image-container:hover img {
            transform: scale(1.05);
        }
        .hero-section {
            background-image: linear-gradient(rgba(15, 23, 42, 0.8), rgba(15, 23, 42, 0.9)), url('{{ asset('images/FondoUnerg2.jpg') }}');
            background-size: cover;
            background-position: center;
            border-radius: 1rem;
            padding: 2rem;
            margin-top: 2rem;
        }
    </style>
</head>
<body class="antialiased">
    <div class="relative min-h-screen">
        <!-- Navbar -->
        <nav class="bg-slate-900/80 backdrop-blur-sm border-b border-slate-800 fixed w-full z-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <span class="text-xl font-bold gradient-text">SIGEDOR</span>
                    </div>
                    <div class="flex items-center">
                        @if (Route::has('login'))
                            <div class="space-x-4">
                                @auth
                                    <a href="{{ url('/dashboard') }}" 
                                       class="text-sm text-slate-300 hover:text-white transition">Escritorio</a>
                                @else
                                    <a href="{{ url('/dashboard/login') }}" 
                                       class="text-sm text-slate-300 hover:text-white transition">Iniciar Sesión</a>
                                @endauth
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="pt-24">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="hero-section">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                        <div>
                            <h1 class="text-4xl sm:text-5xl font-bold mb-6 leading-tight">
                                Sistema de Gestión para
                                <span class="gradient-text">Docentes Ordinarios de la UNERG</span>
                            </h1>
                            <p class="text-slate-300 text-lg mb-8">
                                Sistema integral diseñado para gestionar el proceso de ascenso del personal docente ordinario
                                de la Universidad Nacional Experimental Rómulo Gallegos. Facilitando el seguimiento de categorías
                                académicas y el desarrollo profesional de nuestros docentes.
                            </p>
                            <div class="flex space-x-4">
                                @auth
                                    <a href="{{ url('/dashboard') }}" 
                                       class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg 
                                              font-medium transition-all shadow-lg hover:shadow-blue-500/25">
                                        Escritorio
                                    </a>
                                @else
                                    <a href="{{ url('/dashboard/login') }}" 
                                       class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg 
                                              font-medium transition-all shadow-lg hover:shadow-blue-500/25">
                                        Comenzar
                                    </a>
                                @endauth
                            </div>
                        </div>
                        <div class="flex justify-center">
                            <div class="image-container bg-slate-800/60 backdrop-blur-sm rounded-xl p-6 shadow-xl max-w-md">
                                <img src="{{ asset('images/LogoUnerg.png') }}" 
                                     alt="Logo UNERG" 
                                     class="w-full h-auto rounded-lg">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Features Section -->
                <div class="mt-24 grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="p-6 bg-slate-800/50 backdrop-blur-sm rounded-xl shadow-lg 
                                hover:bg-slate-800 transition-all duration-300">
                        <h3 class="text-xl font-semibold mb-4 text-blue-400">Control de Categorías</h3>
                        <p class="text-slate-300">
                            Gestión eficiente de las categorías académicas del personal docente ordinario,
                            desde Instructor hasta Titular. Seguimiento detallado de los tiempos de permanencia
                            y requisitos para cada ascenso.
                        </p>
                    </div>
                    <div class="p-6 bg-slate-800/50 backdrop-blur-sm rounded-xl shadow-lg 
                                hover:bg-slate-800 transition-all duration-300">
                        <h3 class="text-xl font-semibold mb-4 text-purple-400">Validación Académica</h3>
                        <p class="text-slate-300">
                            Proceso automatizado para la validación de títulos académicos y credenciales.
                            Verificación de requisitos específicos para cada categoría y control de 
                            documentación requerida.
                        </p>
                    </div>
                    <div class="p-6 bg-slate-800/50 backdrop-blur-sm rounded-xl shadow-lg 
                                hover:bg-slate-800 transition-all duration-300">
                        <h3 class="text-xl font-semibold mb-4 text-indigo-400">Seguimiento de Ascensos</h3>
                        <p class="text-slate-300">
                            Monitoreo preciso de los períodos de ascenso, notificaciones oportunas
                            y control de los procesos administrativos relacionados con la promoción
                            del personal docente.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-slate-900/80 backdrop-blur-sm border-t border-slate-800 mt-24">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="text-center text-slate-400">
                    <p>&copy; {{ date('Y') }} SIGEDOR. Todos los derechos reservados.</p>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
