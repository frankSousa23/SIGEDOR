<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Lista de Profesores</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
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
            background-color: #f4f4f4;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>SIGEDOR - Lista de Profesores</h1>
        <p>Generado el {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Sede</th>
                <th>Área</th>
                <th>Dedicación</th>
            </tr>
        </thead>
        <tbody>
            @foreach($teachers as $teacher)
            <tr>
                <td>{{ $teacher->name }}</td>
                <td>{{ $teacher->email }}</td>
                <td>{{ $teacher->phone }}</td>
                <td>{{ $teacher->headquarters->name }}</td>
                <td>{{ $teacher->area->name }}</td>
                <td>{{ $teacher->dedication->name }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>SIGEDOR - Sistema de Gestión Docente</p>
        <p>Documento generado por {{ $user->name }}</p>
    </div>
</body>
</html>
