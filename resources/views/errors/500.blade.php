<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>500 - Error del Servidor | SIGEDOR</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased min-h-screen bg-cover bg-center" style="background-image: url('{{ asset('images/FondoUnerg.jpg') }}');">
        <div class="relative min-h-screen bg-gradient-to-br from-slate-900/90 to-blue-900/80">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Navigation -->
                <div class="flex justify-between items-center py-6">
                    <div class="flex items-center">
                        <a href="{{ url('/') }}" class="text-2xl font-bold text-white">
                            SIGEDOR
                        </a>
                    </div>
                    <a href="{{ url('/') }}" 
                       class="text-sm text-slate-300 hover:text-white transition">
                        Volver al Inicio
                    </a>
                </div>

                <!-- Error Content -->
                <div class="flex flex-col items-center justify-center min-h-[80vh] text-center">
                    <div class="bg-slate-800/50 backdrop-blur-sm rounded-xl p-8 shadow-xl max-w-2xl">
                        <img src="{{ asset('images/LogoUnerg.png') }}" 
                             alt="Logo UNERG" 
                             class="w-32 h-auto mx-auto mb-6">
                        <h1 class="text-6xl font-bold text-white mb-4">500</h1>
                        <h2 class="text-2xl font-semibold text-blue-400 mb-4">Error del Servidor</h2>
                        <p class="text-slate-300 text-lg mb-8">
                            Lo sentimos, ha ocurrido un error interno en el servidor. 
                            Nuestro equipo técnico ha sido notificado y está trabajando en la solución.
                        </p>
                        <a href="{{ url('/') }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg 
                                  font-medium transition-all shadow-lg hover:shadow-blue-500/25">
                            Volver al Inicio
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
