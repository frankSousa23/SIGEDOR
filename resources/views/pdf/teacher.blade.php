@extends('pdf.base')

@section('content')
<table>
    <tr>
        <th colspan="4" style="background: #003366; color: white; text-align: center">
            FICHA DOCENTE - UNERG
        </th>
    </tr>
    <tr>
        <td width="20%"><strong>Cédula:</strong></td>
        <td width="30%">{{ $teacher->cdi }}</td>
        <td width="20%"><strong>Género:</strong></td>
        <td width="30%">{{ $teacher->genre == 'F' ? '♀ Femenino' : '♂ Masculino' }}</td>
    </tr>
    <tr>
        <td><strong>Nombre:</strong></td>
        <td>{{ $teacher->name }}</td>
        <td><strong>Apellidos:</strong></td>
        <td>{{ $teacher->surName }}</td>
    </tr>
    <tr>
        <td><strong>Sede:</strong></td>
        <td>{{ $teacher->sede->nombre ?? 'N/A' }}</td>
        <td><strong>Área:</strong></td>
        <td>{{ $teacher->area->nombre ?? 'N/A' }}</td>
    </tr>
    <tr>
        <td><strong>Teléfono:</strong></td>
        <td>{{ $teacher->phone }}</td>
        <td><strong>Correo:</strong></td>
        <td>{{ $teacher->email }}</td>
    </tr>
    <tr>
        <td><strong>Nacimiento:</strong></td>
        <td>{{ $teacher->birthDate->format('d/m/Y') }}</td>
        <td><strong>Promoción:</strong></td>
        <td>{{ $teacher->datePromotion->format('d/m/Y') }}</td>
    </tr>
</table>

<div style="margin-top: 15px; font-size: 8pt; color: #666">
    Generado el: {{ now()->setTimezone('America/Caracas')->format('d/m/Y H:i') }} (Hora Venezuela)
</div>
@endsection
