@extends('pdf.base')

@section('title', 'Asignación de Sede - ' . ($site->teacher?->cdi ?? $site->teacher_cdi))

@section('content')
<h3 style="color: #003366; border-bottom: 1px solid #cbd5e1; padding-bottom: 4px; margin-bottom: 10px;">
    CONSTANCIA DE ASIGNACIÓN DE SEDE Y CARGA HORARIA
</h3>

<table>
    <tr>
        <th colspan="4" style="background: #f1f5f9; text-align: center; font-weight: bold;">DETALLES DE ASIGNACIÓN ACADÉMICA</th>
    </tr>
    <tr>
        <td width="25%"><strong>Docente:</strong></td>
        <td width="25%">{{ $site->teacher ? ($site->teacher->name . ' ' . $site->teacher->surName . ' (' . $site->teacher->cdi . ')') : ($site->teacher_cdi ?? 'Sin Docente') }}</td>
        <td width="25%"><strong>Programa:</strong></td>
        <td width="25%">{{ $site->programa?->nombre ?? 'Sin Asignar' }}</td>
    </tr>
    <tr>
        <td><strong>Horas Semanales:</strong></td>
        <td>{{ $site->weekHours ?? 0 }}h</td>
        <td><strong>Secciones:</strong></td>
        <td>{{ $site->sections ?? 0 }}</td>
    </tr>
</table>
@endsection
