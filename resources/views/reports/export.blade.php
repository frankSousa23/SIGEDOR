<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
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
                <th>CDI</th>
                <th>Nombres</th>
                <th>Apellidos</th>
                <th>Categoría</th>
                <th>Dedicación</th>
                <th>Sede</th>
                <th>Reporte</th>
                <th>Número de Memo</th>
                <th>Tipo de Reporte</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reports as $report)
                <tr>
                    <td>{{ $report->teacher->cdi }}</td>
                    <td>{{ $report->teacher->name }}</td>
                    <td>{{ $report->teacher->surName }}</td>
                    <td>{{ $report->category->name ?? 'N/A' }}</td>
                    <td>{{ $report->dedication->name ?? 'N/A' }}</td>
                    <td>{{ $report->site->name ?? 'N/A' }}</td>
                    <td>{{ $report->report }}</td>
                    <td>{{ $report->memoNumber }}</td>
                    <td>{{ $report->typeReport }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
