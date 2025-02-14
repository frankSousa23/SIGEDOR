@extends('pdf.base')

@section('content')
<table>
    <tr>
        <th colspan="4" class="header-bg">PERMISO DOCENTE</th>
    </tr>
    <tr>
        <td width="25%"><strong>Docente:</strong></td>
        <td width="25%">{{ $permission->teacher->full_name }}</td>
        <td width="25%"><strong>Estado:</strong></td>
        <td width="25%">{{ ucfirst($permission->status) }}</td>
    </tr>
    <tr>
        <td><strong>Fecha Inicio:</strong></td>
        <td>{{ $permission->start_date->format('d/m/Y') }}</td>
        <td><strong>Fecha Fin:</strong></td>
        <td>{{ $permission->end_date->format('d/m/Y') }}</td>
    </tr>
    <tr>
        <td colspan="4"><strong>Descripción:</strong></td>
    </tr>
    <tr>
        <td colspan="4">{{ $permission->description }}</td>
    </tr>
</table>
@endsection
