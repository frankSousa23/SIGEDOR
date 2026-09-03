@extends('pdf.base')

@section('title', 'Informe Académico - Memo Nº ' . $report->memoNumber)

@section('content')
<h3 style="color: #003366; border-bottom: 1px solid #cbd5e1; padding-bottom: 4px; margin-bottom: 10px;">
    CONSTANCIA DE INFORME Y DICTAMEN ACADÉMICO
</h3>

<table style="margin-bottom: 15px;">
    <tr>
        <th colspan="2" style="background: #f1f5f9; text-align: center; font-weight: bold;">DATOS DEL INFORME</th>
    </tr>
    <tr>
        <td style="width: 30%;"><strong>Nº de Memorando:</strong></td>
        <td style="width: 70%;">{{ $report->memoNumber }}</td>
    </tr>
    <tr>
        <td><strong>Tipo de Informe:</strong></td>
        <td>{{ $report->typeReport }}</td>
    </tr>
    <tr>
        <td><strong>Docente:</strong></td>
        <td>{{ $report->teacher ? ($report->teacher->name . ' ' . $report->teacher->surName . ' - C.I. ' . $report->teacher->cdi) : ($report->teacher_cdi ?? 'Sin Docente') }}</td>
    </tr>
    <tr>
        <td><strong>Sede Universitaria:</strong></td>
        <td>{{ $report->sede?->nombre ?? 'Sin Asignar' }}</td>
    </tr>
    <tr>
        <td><strong>Área Académica:</strong></td>
        <td>{{ $report->area?->nombre ?? 'Sin Asignar' }}</td>
    </tr>
    <tr>
        <td><strong>Categoría:</strong></td>
        <td>{{ $report->category?->current_category ?? $report->teacher?->category?->current_category ?? 'Instructor' }}</td>
    </tr>
    <tr>
        <td><strong>Dedicación:</strong></td>
        <td>{{ $report->dedication?->name ?? $report->teacher?->dedication?->name ?? 'Sin Asignar' }}</td>
    </tr>
    @if($report->email)
    <tr>
        <td><strong>Correo de Notificación:</strong></td>
        <td>{{ $report->email }}</td>
    </tr>
    @endif
</table>

@if($report->report)
<div style="margin-top: 15px;">
    <h4 style="color: #003366; margin-bottom: 5px;">Contenido / Dictamen:</h4>
    <div style="background: #f8fafc; border: 1px solid #cbd5e1; padding: 10px; border-radius: 4px; font-size: 9.5pt; line-height: 1.6;">
        {!! nl2br(e($report->report)) !!}
    </div>
</div>
@endif

@if($report->info)
<div style="margin-top: 15px;">
    <h4 style="color: #003366; margin-bottom: 5px;">Observaciones Adicionales:</h4>
    <div style="background: #f8fafc; border: 1px solid #cbd5e1; padding: 10px; border-radius: 4px; font-size: 9pt; color: #475569;">
        {!! nl2br(e($report->info)) !!}
    </div>
</div>
@endif
@endsection
