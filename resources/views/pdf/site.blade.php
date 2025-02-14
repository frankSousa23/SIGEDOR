@extends('pdf.base')

@section('content')
<table>
    <tr>
        <th colspan="4" class="header-bg">ASIGNACIÓN DE SEDE</th>
    </tr>
    <tr>
        <td width="25%"><strong>Docente:</strong></td>
        <td width="25%">{{ $site->teacher->full_name }}</td>
        <td width="25%"><strong>Programa:</strong></td>
        <td width="25%">{{ $site->programa->nombre }}</td>
    </tr>
    <tr>
        <td><strong>Horas Semanales:</strong></td>
        <td>{{ $site->weekHours }}h</td>
        <td><strong>Secciones:</strong></td>
        <td>{{ $site->sections }}</td>
    </tr>
</table>
@endsection
