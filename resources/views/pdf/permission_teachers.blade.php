<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Permisos de Docentes</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Reporte de Permisos de Docentes</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Docente</th>
                <th>Nombre del Permiso</th>
                <th>Estado</th>
                <th>Fecha de Inicio</th>
                <th>Fecha de Fin</th>
                <th>Descripción</th>
            </tr>
        </thead>
        <tbody>
            @foreach($permission_teachers as $permission_teacher)
                <tr>
                    <td>{{ $permission_teacher->id }}</td>
                    <td>{{ $permission_teacher->teacher->cdi ?? 'Sin Docente' }}</td>
                    <td>{{ $permission_teacher->name }}</td>
                    <td>{{ match ($permission_teacher->status) {
                        'pending' => 'Pendiente',
                        'approved' => 'Aprobado',
                        'rejected' => 'Rechazado',
                        default => $permission_teacher->status,
                    } }}</td>
                    <td>{{ $permission_teacher->start_date }}</td>
                    <td>{{ $permission_teacher->end_date }}</td>
                    <td>{{ $permission_teacher->description }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
