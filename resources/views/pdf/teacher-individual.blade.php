@extends('pdf.base')

@section('title', 'Expediente Docente - ' . ($teacher->name . ' ' . $teacher->surName))

@section('content')
<h3 style="color: #003366; border-bottom: 1px solid #cbd5e1; padding-bottom: 4px; margin-bottom: 10px;">
    EXPEDIENTE INDIVIDUAL DEL DOCENTE
</h3>

<table style="width: 100%;">
    <tr>
        <td style="width: 30%;"><strong>Cédula de Identidad:</strong></td>
        <td style="width: 70%;">{{ $teacher->cdi }}</td>
    </tr>
    <tr>
        <td><strong>Nombre Completo:</strong></td>
        <td>{{ $teacher->name }} {{ $teacher->surName }}</td>
    </tr>
    <tr>
        <td><strong>Sede Universitaria:</strong></td>
        <td>{{ $teacher->sede?->nombre ?? 'Sin Asignar' }}</td>
    </tr>
    <tr>
        <td><strong>Área Académica:</strong></td>
        <td>{{ $teacher->area?->nombre ?? 'Sin Asignar' }}</td>
    </tr>
    <tr>
        <td><strong>Programa Académico:</strong></td>
        <td>{{ $teacher->programa?->nombre ?? 'Sin Asignar' }}</td>
    </tr>
    <tr>
        <td><strong>Correo Electrónico:</strong></td>
        <td>{{ $teacher->email }}</td>
    </tr>
    <tr>
        <td><strong>Teléfono de Contacto:</strong></td>
        <td>{{ $teacher->phone ?? 'N/A' }}</td>
    </tr>
    <tr>
        <td><strong>Fecha de Nacimiento:</strong></td>
        <td>{{ $teacher->birthDate ? $teacher->birthDate->format('d/m/Y') : 'N/A' }}</td>
    </tr>
    <tr>
        <td><strong>Fecha de Promoción:</strong></td>
        <td>{{ $teacher->datePromotion ? $teacher->datePromotion->format('d/m/Y') : 'N/A' }}</td>
    </tr>
    <tr>
        <td><strong>Asignatura de Promoción:</strong></td>
        <td>{{ $teacher->asignaturePromotion ?? 'N/A' }}</td>
    </tr>
    <tr>
        <td><strong>Categoría Actual:</strong></td>
        <td>{{ $teacher->category?->current_category ?? 'Instructor' }}</td>
    </tr>
    <tr>
        <td><strong>Dedicación:</strong></td>
        <td>{{ $teacher->dedication?->name ?? 'Sin Asignar' }}</td>
    </tr>
</table>
@endsection
