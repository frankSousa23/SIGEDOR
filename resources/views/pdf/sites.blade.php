<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Sedes</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Reporte de Sedes</h1>
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
</body>
</html>
