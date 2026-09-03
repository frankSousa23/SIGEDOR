@extends('pdf.base')

@section('title', 'Permiso Docente - Memo Nº ' . ($permission->memo_number ?? 'S/N'))

@section('content')
<h3 style="color: #003366; border-bottom: 1px solid #cbd5e1; padding-bottom: 4px; margin-bottom: 10px;">
    SOLICITUD / REGISTRO DE PERMISO DOCENTE
</h3>

<table>
    <tr>
        <th colspan="4" style="background: #f1f5f9; text-align: center; font-weight: bold;">DATOS DEL PERMISO ACADÉMICO</th>
    </tr>
    <tr>
        <td width="25%"><strong>Nº Memorando:</strong></td>
        <td width="25%">{{ $permission->memo_number ?? 'S/N' }}</td>
        <td width="25%"><strong>Tipo:</strong></td>
        <td width="25%">{{ $permission->type ?? 'N/A' }}</td>
    </tr>
    <tr>
        <td width="25%"><strong>Docente:</strong></td>
        <td width="25%">{{ $permission->teacher ? ($permission->teacher->name . ' ' . $permission->teacher->surName . ' (' . $permission->teacher->cdi . ')') : ($permission->teacher_cdi ?? 'Sin Docente') }}</td>
        <td width="25%"><strong>Estado:</strong></td>
        <td width="25%">{{ ucfirst($permission->status ?? 'Pendiente') }}</td>
    </tr>
    <tr>
        <td><strong>Fecha Inicio:</strong></td>
        <td>{{ $permission->start_date ? $permission->start_date->format('d/m/Y') : 'N/A' }}</td>
        <td><strong>Fecha Fin:</strong></td>
        <td>{{ $permission->end_date ? $permission->end_date->format('d/m/Y') : 'N/A' }}</td>
    </tr>
    <tr>
        <td><strong>Remunerado:</strong></td>
        <td>{{ $permission->is_paid ? 'Sí' : 'No' }}</td>
        <td><strong>Duración:</strong></td>
        <td>{{ $permission->getDurationLabel() }}</td>
    </tr>
    @if($permission->description)
    <tr>
        <td colspan="4"><strong>Descripción / Justificación:</strong></td>
    </tr>
    <tr>
        <td colspan="4">{{ $permission->description }}</td>
    </tr>
    @endif
</table>
@endsection
