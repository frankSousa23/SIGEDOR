<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Dedicaciones</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Reporte de Dedicaciones</h1>
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
</body>
</html>
