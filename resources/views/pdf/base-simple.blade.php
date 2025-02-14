<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'SIGEDOR')</title>
    <style>
        body { font-family: Arial; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .footer { text-align: center; font-size: 10px; color: #666; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 5px; }
        th { background: #f4f4f4; }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('images/LogoUnerg.png') }}" style="height: 50px; margin-bottom: 10px">
        <h2 style="margin: 5px 0">Universidad Nacional Experimental "Rómulo Gallegos"</h2>
        <h3 style="margin: 0; font-size: 14px">SIGEDOR - Sistema de Gestión Docente</h3>
    </div>

    @yield('content')

    <div class="footer">
        Generado el {{ now()->format('d/m/Y H:i') }} por {{ $user->name }}
    </div>
</body>
</html>
