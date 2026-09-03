@extends('pdf.base')

@section('title', 'Dedicación Docente - ' . ($dedication->teacher?->cdi ?? $dedication->teacher_cdi))

@section('content')
<h3 style="color: #003366; border-bottom: 1px solid #cbd5e1; padding-bottom: 4px; margin-bottom: 10px;">
    CONSTANCIA DE DEDICACIÓN DOCENTE
</h3>

<table>
    <tr>
        <th colspan="4" style="background: #f1f5f9; text-align: center; font-weight: bold;">DATOS DE DEDICACIÓN Y CARGA HORARIA</th>
    </tr>
    <tr>
        <td width="25%"><strong>Docente:</strong></td>
        <td width="25%">{{ $dedication->teacher ? ($dedication->teacher->name . ' ' . $dedication->teacher->surName) : $dedication->teacher_cdi }}</td>
        <td width="25%"><strong>Tipo:</strong></td>
        <td width="25%">{{ match(trim((string)$dedication->name)) {
            'TCV', 'Tiempo Convencional' => 'Tiempo Convencional',
            'MT', 'Medio Tiempo' => 'Medio Tiempo',
            'TC', 'Tiempo Completo' => 'Tiempo Completo',
            'EX', 'Dedicación Exclusiva', 'Exclusiva' => 'Dedicación Exclusiva',
            default => $dedication->name ?? 'Sin Definir'
        } }}</td>
    </tr>
    <tr>
        <td><strong>Horas Semanales:</strong></td>
        <td>{{ $dedication->hours ?? 0 }}h</td>
        <td><strong>Cargo Directivo:</strong></td>
        <td>{{ $dedication->director ?? 'Ninguno' }}</td>
    </tr>
</table>
@endsection
