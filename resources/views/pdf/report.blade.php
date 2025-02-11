<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Reporte</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Reporte de Reporte</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Docente</th>
                <th>Categoría</th>
                <th>Dedicación</th>
                <th>Permiso</th>
                <th>Sede</th>
                <th>Reporte</th>
                <th>Número de Memo</th>
                <th>Tipo de Reporte</th>
                <th>Correo Electrónico</th>
                <th>Información Adicional</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $report->id }}</td>
                <td>{{ $report->teacher->cdi ?? 'Sin Docente' }}</td>
                <td>{{ $report->category->current_category ?? 'Sin Categoría' }}</td>
                <td>{{ $report->dedication->name ?? 'Sin Dedicación' }}</td>
                <td>{{ $report->permissionTeacher->name ?? 'Sin Permiso' }}</td>
                <td>{{ $report->site->name ?? 'Sin Sede' }}</td>
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
