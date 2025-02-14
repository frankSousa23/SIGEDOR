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
        <div class="subtitle">Control Académico - Reporte General - {{ now()->format('d/m/Y H:i') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Docente</th>
                <th>Categoría</th>
                <th>Dedicación</th>
                <th>Sede</th>
                <th>Reporte</th>
                <th>Número de Memo</th>
                <th>Tipo de Reporte</th>
                <th>Correo Electrónico</th>
                <th>Información Adicional</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reports as $report)
                <tr>
                    <td>{{ $report->id }}</td>
                    <td>{{ $report->teacher->cdi ?? 'Sin Docente' }}</td>
                    <td>{{ $report->category->current_category ?? 'Sin Categoría' }}</td>
                    <td>{{ $report->dedication->name ?? 'Sin Dedicación' }}</td>
                    <td>{{ $report->site->name ?? 'Sin Sede' }}</td>
                    <td>{{ $report->report }}</td>
                    <td>{{ $report->memoNumber }}</td>
                    <td>{{ $report->typeReport }}</td>
                    <td>{{ $report->email }}</td>
                    <td>{{ $report->info }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <div class="footer-text">Total de reportes: {{ $reports->count() }}</div>
        <div class="footer-text">SIGEDOR - Sistema para Gestión de Docentes Ordinarios</div>
        <div class="footer-text">Generado automáticamente por el sistema</div>
    </div>
</body>
</html>
