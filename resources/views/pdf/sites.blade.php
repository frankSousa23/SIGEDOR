<!DOCTYPE html>
<html>
<head>
    <style>
        @page { size: landscape; margin: 20px; }
        .header { text-align: center; margin-bottom: 20px; }
        .footer { text-align: center; margin-top: 20px; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .title { font-size: 18px; font-weight: bold; }
        .subtitle { font-size: 14px; margin-top: 5px; }
        .footer-text { font-size: 12px; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Universidad Nacional Experimental "Rómulo Gallegos"</div>
        <div class="subtitle">Control Académico - Reporte de Sedes - {{ now()->format('d/m/Y H:i') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Docente</th>
                <th>Área</th>
                <th>Programa</th>
                <th>Unidad Curricular</th>
                <th>Horas Semanales</th>
                <th>Secciones</th>
                <th>Activo</th>
                <th>Disponible</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sites as $site)
                <tr>
                    <td>{{ $site->id }}</td>
                    <td>{{ $site->name }}</td>
                    <td>{{ $site->teacher->cdi ?? 'Sin Docente' }}</td>
                    <td>{{ $site->area->nombre ?? 'Sin Área' }}</td>
                    <td>{{ $site->programa->nombre ?? 'Sin Programa' }}</td>
                    <td>{{ $site->uc }}</td>
                    <td>{{ $site->weekHours }}</td>
                    <td>{{ $site->sections }}</td>
                    <td>{{ $site->is_active ? 'Sí' : 'No' }}</td>
                    <td>{{ $site->is_available ? 'Sí' : 'No' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <div class="footer-text">Total de sedes: {{ $sites->count() }}</div>
        <div class="footer-text">SIGEDOR - Sistema para Gestión de Docentes Ordinarios</div>
        <div class="footer-text">Generado automáticamente por el sistema</div>
    </div>
</body>
</html>
