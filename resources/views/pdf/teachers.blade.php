<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Lista de Docentes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        h1 {
            text-align: center;
            color: #333;
        }
    </style>
</head>
<body>
    <h1>Lista de Docentes</h1>
    
    <table>
        <thead>
            <tr>
                <th>Cédula</th>
                <th>Nombres</th>
                <th>Apellidos</th>
                <th>Correo</th>
                <th>Teléfono</th>
                <th>Sede</th>
                <th>Categoría</th>
                <th>Dedicación</th>
            </tr>
        </thead>
        <tbody>
            @foreach($teachers as $teacher)
            <tr>
                <td>{{ $teacher->cdi }}</td>
                <td>{{ $teacher->name }}</td>
                <td>{{ $teacher->surName }}</td>
                <td>{{ $teacher->email }}</td>
                <td>{{ $teacher->phone }}</td>
                <td>{{ $teacher->site?->name ?? 'N/A' }}</td>
                <td>{{ $teacher->category?->name ?? 'N/A' }}</td>
                <td>{{ $teacher->dedication?->name ?? 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
