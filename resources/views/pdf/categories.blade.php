<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Categorías</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Reporte de Categorías</h1>
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
</body>
</html>
