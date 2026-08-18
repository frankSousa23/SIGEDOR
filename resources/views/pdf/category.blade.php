<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Constancia de Categoría Docente - SIGEDOR</title>
    <style>
        @page { size: portrait; margin: 30px; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; line-height: 1.5; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #2563eb; padding-bottom: 15px; margin-bottom: 25px; }
        .institution { font-size: 16px; font-weight: bold; text-transform: uppercase; color: #1e3a8a; }
        .system-title { font-size: 14px; font-weight: bold; color: #475569; }
        .doc-title { font-size: 15px; font-weight: bold; margin-top: 15px; text-decoration: underline; }
        .section-title { font-size: 13px; font-weight: bold; color: #1e3a8a; border-bottom: 1px solid #cbd5e1; padding-bottom: 4px; margin-top: 20px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 6px 10px; text-align: left; }
        th { width: 35%; background-color: #f8fafc; font-weight: bold; border-bottom: 1px solid #e2e8f0; }
        td { border-bottom: 1px solid #e2e8f0; }
        .footer { margin-top: 40px; text-align: center; font-size: 10px; color: #64748b; }
        .signature-box { margin-top: 60px; width: 100%; text-align: center; }
        .signature-line { width: 250px; border-top: 1px solid #000; margin: 0 auto; padding-top: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="institution">Sistema de Gestión Docente y Reportes</div>
        <div class="system-title">SIGEDOR - Plataforma Académica</div>
        <div class="doc-title">EXPEDIENTE DE ESCALAFÓN Y CATEGORIZACIÓN DOCENTE</div>
    </div>

    <div class="section-title">1. Datos del Docente</div>
    <table>
        <tr>
            <th>Cédula de Identidad:</th>
            <td>{{ $category->teacher_cdi ?? $category->teacher?->cdi ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Nombre Completo:</th>
            <td>{{ $category->teacher?->name ?? '' }} {{ $category->teacher?->surName ?? '' }}</td>
        </tr>
        <tr>
            <th>Correo Electrónico:</th>
            <td>{{ $category->teacher?->email ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Categoría Actual:</th>
            <td><strong>{{ $category->current_category ?? 'Instructor' }}</strong></td>
        </tr>
    </table>

    <div class="section-title">2. Formación Académica</div>
    <table>
        <tr>
            <th>Título de Pregrado:</th>
            <td>{{ $category->preTitle ?? 'No especificado' }}</td>
        </tr>
        <tr>
            <th>Último Título Obtenido:</th>
            <td>{{ $category->lastTitle ?? 'No especificado' }}</td>
        </tr>
    </table>

    <div class="section-title">3. Historial de Ascensos y Fechas</div>
    <table>
        <tr>
            <th>Instructor:</th>
            <td>{{ $category->instructor ? \Carbon\Carbon::parse($category->instructor)->format('d/m/Y') : 'N/A' }}</td>
        </tr>
        <tr>
            <th>Asistente:</th>
            <td>{{ $category->asistente ? \Carbon\Carbon::parse($category->asistente)->format('d/m/Y') : 'N/A' }}</td>
        </tr>
        <tr>
            <th>Agregado:</th>
            <td>{{ $category->agregado ? \Carbon\Carbon::parse($category->agregado)->format('d/m/Y') : 'N/A' }}</td>
        </tr>
        <tr>
            <th>Asociado:</th>
            <td>{{ $category->asociado ? \Carbon\Carbon::parse($category->asociado)->format('d/m/Y') : 'N/A' }}</td>
        </tr>
        <tr>
            <th>Titular:</th>
            <td>{{ $category->titular ? \Carbon\Carbon::parse($category->titular)->format('d/m/Y') : 'N/A' }}</td>
        </tr>
    </table>

    <div class="signature-box">
        <div class="signature-line">
            Firma y Sello de la Dirección Académica
        </div>
    </div>

    <div class="footer">
        Documento generado automáticamente por SIGEDOR el {{ now()->format('d/m/Y H:i:s') }}
    </div>
</body>
</html>
