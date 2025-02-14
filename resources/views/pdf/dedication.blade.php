@extends('pdf.base')

@section('content')
<table>
    <tr>
        <th colspan="4" class="header-bg">DEDICACIÓN DOCENTE</th>
    </tr>
    <tr>
        <td width="25%"><strong>Docente:</strong></td>
        <td width="25%">{{ $dedication->teacher->full_name }}</td>
        <td width="25%"><strong>Tipo:</strong></td>
        <td width="25%">{{ match($dedication->name) {
            'TCV' => 'Tiempo Convencional',
            'MT' => 'Medio Tiempo',
            'TC' => 'Tiempo Completo',
            'EX' => 'Exclusiva'
        } }}</td>
    </tr>
    <tr>
        <td><strong>Horas:</strong></td>
        <td>{{ $dedication->hours }}h</td>
        <td><strong>Cargo Directivo:</strong></td>
        <td>{{ $dedication->director ?? 'Ninguno' }}</td>
    </tr>
</table>
@endsection
