<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Documento Oficial - SIGEDOR UNERG')</title>
    <style>
        @page {
            margin: 38mm 16mm 24mm 16mm;
            size: @yield('page_size', 'A4 portrait');
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 9.5pt;
            line-height: 1.5;
            color: #1e293b;
            margin: 0;
            padding: 0;
        }

        .header {
            position: fixed;
            top: -32mm;
            left: 0;
            right: 0;
            border-bottom: 2.5px solid #003366;
            padding-bottom: 3mm;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            border: none;
        }

        .header-table td {
            border: none;
            padding: 0;
            vertical-align: middle;
        }

        .header-logo {
            width: 22mm;
            height: auto;
            max-height: 22mm;
        }

        .header-text {
            text-align: center;
            padding-left: 10px;
        }

        .header-text h1 {
            font-size: 8.5pt;
            font-weight: bold;
            color: #0f172a;
            margin: 0;
            text-transform: uppercase;
            line-height: 1.25;
        }

        .header-text h2 {
            font-size: 9.5pt;
            font-weight: bold;
            color: #003366;
            margin: 1.5mm 0 0 0;
            text-transform: uppercase;
            line-height: 1.25;
        }

        .header-text p {
            font-size: 7.5pt;
            color: #475569;
            margin: 1mm 0 0 0;
            letter-spacing: 0.5px;
        }

        .footer {
            position: fixed;
            bottom: -15mm;
            left: 0;
            right: 0;
            border-top: 1px solid #cbd5e1;
            padding-top: 2mm;
            font-size: 7.5pt;
            color: #64748b;
        }

        .footer-table {
            width: 100%;
            border-collapse: collapse;
            border: none;
        }

        .footer-table td {
            border: none;
            padding: 0;
            vertical-align: top;
        }

        .content {
            margin-top: 2mm;
        }

        /* Estilos de tablas formales */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 3mm 0;
        }

        table.data-table th {
            background-color: #f1f5f9;
            color: #003366;
            font-weight: bold;
            font-size: 8.5pt;
            text-transform: uppercase;
            padding: 6px 8px;
            border: 1px solid #cbd5e1;
            text-align: left;
        }

        table.data-table td {
            padding: 5px 8px;
            border: 1px solid #cbd5e1;
            font-size: 9pt;
        }

        table.data-table tr:nth-child(even) {
            background-color: #f8fafc;
        }

        /* Títulos de sección */
        .doc-title {
            color: #003366;
            text-align: center;
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0 0 4mm 0;
            letter-spacing: 0.5px;
        }

        .doc-subtitle {
            color: #475569;
            text-align: center;
            font-size: 9pt;
            margin: -2mm 0 4mm 0;
        }

        .badge-verified {
            display: inline-block;
            background-color: #e0f2fe;
            color: #0369a1;
            border: 1px solid #bae6fd;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8pt;
            font-weight: bold;
            font-family: monospace;
        }

        /* Bloque de firmas */
        .signatures-container {
            margin-top: 15mm;
            page-break-inside: avoid;
            width: 100%;
        }

        .signatures-table {
            width: 100%;
            border-collapse: collapse;
            border: none;
            text-align: center;
        }

        .signatures-table td {
            border: none;
            padding: 0 15px;
            vertical-align: top;
            width: 50%;
        }

        .signature-line {
            border-top: 1.2px solid #0f172a;
            margin: 0 auto 3px auto;
            width: 80%;
        }

        .signature-name {
            font-size: 8.5pt;
            font-weight: bold;
            color: #0f172a;
        }

        .signature-role {
            font-size: 7.5pt;
            color: #475569;
            line-height: 1.3;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <table class="header-table">
            <tr>
                <td style="width: 15%; text-align: left;">
                    @if(file_exists(public_path('images/LogoUnerg.png')))
                        <img src="{{ public_path('images/LogoUnerg.png') }}" class="header-logo" alt="UNERG">
                    @endif
                </td>
                <td style="width: 85%;" class="header-text">
                    <h1>República Bolivariana de Venezuela</h1>
                    <h1>Ministerio del Poder Popular para la Educación Universitaria</h1>
                    <h2>Universidad Nacional Experimental "Rómulo Gallegos"</h2>
                    <p>Vicerrectorado Académico &bull; Dirección de Gestión y Control Docente</p>
                    <p>SIGEDOR - Sistema para Gestión de Docentes Ordinarios</p>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <table class="footer-table">
            <tr>
                <td style="width: 70%; text-align: left;">
                    <strong>SIGEDOR UNERG</strong> &bull; Documento Oficial Expedido por la Plataforma Digital
                    <br>
                    <span>Emisión: {{ now()->setTimezone('America/Caracas')->format('d/m/Y h:i A') }} (Hora Legal de Venezuela)</span>
                    @if(isset($report) && $report->verification_code)
                        <br>
                        <span>Código de Validación: <strong class="badge-verified">{{ $report->verification_code }}</strong></span>
                    @elseif(isset($verificationCode))
                        <br>
                        <span>Código de Validación: <strong class="badge-verified">{{ $verificationCode }}</strong></span>
                    @endif
                </td>
                <td style="width: 30%; text-align: right;">
                    <span>San Juan de los Morros</span><br>
                    <span>Estado Guárico, Venezuela</span>
                </td>
            </tr>
        </table>
    </div>

    @if(config('dompdf.options.enable_php', false))
    <script type="text/php">
    if (isset($pdf)) {
        $text = "Pág. " . $PAGE_NUM . " de " . $PAGE_COUNT;
        $font = $fontMetrics->getFont("Helvetica", "normal");
        $size = 7.5;
        $pdf->page_text(520, 815, $text, $font, $size, array(100/255, 116/255, 139/255));
    }
    </script>
    @endif

    <div class="content">
        @yield('content')
    </div>
</body>
</html>
