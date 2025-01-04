<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Docentes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Docentes</h1>
        <p>Fecha de generación: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Docente</th>
                <th>Categoría</th>
                <th>Dedicación</th>
                <th>Permiso</th>
                <th>Reporte</th>
                <th>Número de Memo</th>
                <th>Tipo de Reporte</th>
                <th>Correo Electrónico</th>
                <th>Información Adicional</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $report->teacher->name }}</td>
                <td>{{ $report->teacher->category->current_category }}</td>
                <td>{{ $report->teacher->dedication->name }}</td>
                <td>{{ implode(', ', $report->teacher->permissionTeachers->pluck('name')->toArray()) }}</td>
                <td>{{ $report->report }}</td>
                <td>{{ $report->memoNumber }}</td>
                <td>{{ $report->typeReport }}</td>
                <td>{{ $report->email }}</td>
                <td>{{ $report->info }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
