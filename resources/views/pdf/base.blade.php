<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Reporte SIGEDOR')</title>
    <style>
        @page {
            margin: 30mm 15mm 20mm 15mm;
            size: A4 portrait;
        }

        body {
            font-family: 'Inter', sans-serif;
            font-size: 10pt;
            line-height: 1.5;
            color: #1e293b;
        }

        .header {
            position: fixed;
            top: -25mm;
            left: 0;
            right: 0;
            text-align: center;
            border-bottom: 2px solid #003366;
            padding-bottom: 2mm;
        }

        .logo {
            height: 25mm;
            margin-bottom: 2mm;
        }

        .footer {
            position: fixed;
            bottom: -10mm;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8pt;
            color: #64748b;
        }

        .content {
            margin-top: 30mm;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 2mm 0;
        }

        th {
            background: #f1f5f9;
            padding: 4px;
            border: 1px solid #cbd5e1;
            text-align: left;
        }

        td {
            padding: 4px;
            border: 1px solid #cbd5e1;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('images/LogoUnerg.png') }}" class="logo">
        <h2 style="color: #003366; margin: 0;">
            Universidad Nacional Experimental "Rómulo Gallegos"<br>
            <span style="font-size: 9pt;">Vicerrectorado Académico</span>
        </h2>
        <p style="font-size: 8pt; margin: 2mm 0 0 0;">
            Av. Principal de San Juan de los Morros, Estado Guárico<br>
            Teléfono: (0246) 431.83.00
        </p>
    </div>

    <div class="footer">
        SIGEDOR: Sistema para Gestión de Docentes Ordinarios -
        {{ now()->setTimezone('America/Caracas')->format('d/m/Y H:i') }} (Hora Venezuela)
    </div>

    <div class="content">
        @yield('content')
    </div>
</body>
</html>
