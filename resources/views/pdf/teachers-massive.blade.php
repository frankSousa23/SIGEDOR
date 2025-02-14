<table style="width: 100%; border-collapse: collapse; margin-top: 15px">
    <thead>
        <tr style="background: #f0f0f0">
            <th style="padding: 8px; border: 1px solid #ddd">Cédula</th>
            <th style="padding: 8px; border: 1px solid #ddd">Nombre Completo</th>
            <th style="padding: 8px; border: 1px solid #ddd">Sede</th>
            <th style="padding: 8px; border: 1px solid #ddd">Área</th>
            <th style="padding: 8px; border: 1px solid #ddd">Email</th>
            <th style="padding: 8px; border: 1px solid #ddd">Teléfono</th>
            <th style="padding: 8px; border: 1px solid #ddd">Nacimiento</th>
            <th style="padding: 8px; border: 1px solid #ddd">Promoción</th>
        </tr>
    </thead>
    <tbody>
        @foreach($teachers as $teacher)
        <tr>
            <td style="padding: 6px; border: 1px solid #ddd">{{ $teacher->cdi }}</td>
            <td style="padding: 6px; border: 1px solid #ddd">{{ $teacher->name }} {{ $teacher->surname }}</td>
            <td style="padding: 6px; border: 1px solid #ddd">{{ $teacher->sede->nombre }}</td>
            <td style="padding: 6px; border: 1px solid #ddd">{{ $teacher->area->nombre }}</td>
            <td style="padding: 6px; border: 1px solid #ddd">{{ $teacher->email }}</td>
            <td style="padding: 6px; border: 1px solid #ddd">{{ $teacher->phone }}</td>
            <td style="padding: 6px; border: 1px solid #ddd">{{ $teacher->birthDate->format('d/m/Y') }}</td>
            <td style="padding: 6px; border: 1px solid #ddd">{{ $teacher->datePromotion->format('d/m/Y') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
