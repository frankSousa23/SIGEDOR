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
        <div class="subtitle">Control Académico - Reporte de Categorías - {{ now()->format('d/m/Y H:i') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Docente</th>
                <th>Categoría Actual</th>
                <th>Título de Pregrado</th>
                <th>Título Actual</th>
                <th>Promoción Inmediata</th>
                <th>Fecha de Instructor</th>
                <th>Fecha de Asistente</th>
                <th>Fecha de Agregado</th>
                <th>Fecha de Asociado</th>
                <th>Fecha de Titular</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>{{ $category->teacher->cdi ?? 'Sin Docente' }}</td>
                    <td>{{ $category->current_category }}</td>
                    <td>{{ $category->preTitle }}</td>
                    <td>{{ $category->lastTitle }}</td>
                    <td>{{ $category->disable_assistant_rule ? 'Sí' : 'No' }}</td>
                    <td>{{ $category->instructor }}</td>
                    <td>{{ $category->asistente }}</td>
                    <td>{{ $category->agregado }}</td>
                    <td>{{ $category->asociado }}</td>
                    <td>{{ $category->titular }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <div class="footer-text">Total de categorías: {{ $categories->count() }}</div>
        <div class="footer-text">SIGEDOR - Sistema para Gestión de Docentes Ordinarios</div>
        <div class="footer-text">Generado automáticamente por el sistema</div>
    </div>
</body>
</html>
