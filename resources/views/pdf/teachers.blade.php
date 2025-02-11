<!DOCTYPE html>
<html>
<head>
    <style>
        @page { size: landscape; margin: 20px; }
        .header { text-align: center; margin-bottom: 20px; }
        .footer { text-align: center; margin-top: 20px; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Institución Educativa</h2>
        <p>Reporte de Docentes - {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Cédula</th>
                <th>Nombres</th>
                <th>Apellidos</th>
                <th>Correo</th>
                <th>Teléfono</th>
                <th>Fecha Nac.</th>
                <th>Fecha Prom.</th>
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
                <td>{{ $teacher->birthDate ? $teacher->birthDate->format('d/m/Y') : 'N/A' }}</td>
                <td>{{ $teacher->datePromotion ? $teacher->datePromotion->format('d/m/Y') : 'N/A' }}</td>
                <td>{{ $teacher->site->name ?? 'N/A' }}</td>
                <td>{{ $teacher->category->current_category ?? 'N/A' }}</td>
                <td>{{ $teacher->dedication->name ?? 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Total de docentes: {{ $teachers->count() }}</p>
        <p>Generado automáticamente por el sistema</p>
    </div>
</body>
</html>
