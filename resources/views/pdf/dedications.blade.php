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
        <div class="subtitle">Control Académico - Reporte de Dedicaciones - {{ now()->format('d/m/Y H:i') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Docente</th>
                <th>Dedicación</th>
                <th>Horas</th>
                <th>Cargo Directivo</th>
                <th>Número de Estudiantes</th>
                <th>Horas de Asesoría</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dedications as $dedication)
                <tr>
                    <td>{{ $dedication->id }}</td>
                    <td>{{ $dedication->teacher->cdi ?? 'Sin Docente' }}</td>
                    <td>{{ match ($dedication->name) {
                        'TCV' => 'Tiempo Convencional',
                        'MT' => 'Medio Tiempo',
                        'TC' => 'Tiempo Completo',
                        'EX' => 'Exclusiva',
                        default => $dedication->name,
                    } }}</td>
                    <td>{{ $dedication->hours }}</td>
                    <td>{{ $dedication->director ?? 'Sin Cargo' }}</td>
                    <td>{{ $dedication->studentNumber }}</td>
                    <td>{{ $dedication->studentHours }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <div class="footer-text">Total de dedicaciones: {{ $dedications->count() }}</div>
        <div class="footer-text">SIGEDOR - Sistema para Gestión de Docentes Ordinarios</div>
        <div class="footer-text">Generado automáticamente por el sistema</div>
    </div>
</body>
</html>
