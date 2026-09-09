@extends('pdf.base')

@section('title', ($report->typeReport ?? 'Informe Oficial') . ' - Nº ' . $report->memoNumber)

@section('content')
<div style="margin-bottom: 5mm;">
    <table style="width: 100%; border: none; border-collapse: collapse;">
        <tr>
            <td style="border: none; text-align: left; font-size: 8.5pt; color: #475569;">
                <strong>Nº DE OFICIO / MEMO:</strong> <span style="color: #003366; font-weight: bold;">{{ $report->memoNumber }}</span>
                <br>
                <strong>ESTADO:</strong> <span style="text-transform: uppercase;">{{ $report->status === 'issued' ? 'EMITIDO' : ($report->status === 'annulled' ? 'ANULADO' : 'BORRADOR') }}</span>
            </td>
            <td style="border: none; text-align: right; font-size: 8.5pt; color: #475569;">
                <strong>FECHA:</strong> {{ $report->created_at ? $report->created_at->setTimezone('America/Caracas')->format('d/m/Y') : now()->format('d/m/Y') }}
                <br>
                <strong>VERIFICACIÓN:</strong> <span class="badge-verified">{{ $report->verification_code ?? 'S/C' }}</span>
            </td>
        </tr>
    </table>
</div>

@if($report->typeReport === 'Constancia de Trabajo')
    <h3 class="doc-title">CONSTANCIA DE TRABAJO</h3>
    <p class="doc-subtitle">Certificación Oficial de Relación Académica y Laboral</p>

    <div style="text-align: justify; font-size: 10pt; line-height: 1.7; margin: 5mm 0 6mm 0;">
        Quien suscribe, en representación del <strong>Vicerrectorado Académico</strong> de la <strong>Universidad Nacional Experimental de los Llanos Centrales "Rómulo Gallegos" (UNERG)</strong>, por medio de la presente hace constar formalmente que el ciudadano(a):
    </div>
@elseif($report->typeReport === 'Memorando Administrativo')
    <h3 class="doc-title">MEMORANDO ADMINISTRATIVO</h3>
    <p class="doc-subtitle">Dirección de Gestión Docente y Coordinación Académica</p>

    <table class="data-table" style="margin-bottom: 5mm;">
        <tr>
            <th style="width: 20%;">PARA:</th>
            <td style="width: 80%;"><strong>{{ $report->teacher ? $report->teacher->full_name : $report->teacher_cdi }}</strong> (C.I. {{ $report->teacher_cdi }})</td>
        </tr>
        <tr>
            <th>DE:</th>
            <td>Jefatura de Área &bull; {{ $report->area?->nombre ?? 'Área Académica' }} (Sede {{ $report->sede?->nombre ?? 'Principal' }})</td>
        </tr>
        <tr>
            <th>ASUNTO:</th>
            <td>{{ $report->info ?? 'Notificación y Dictamen Académico Oficial' }}</td>
        </tr>
    </table>
@elseif($report->typeReport === 'Informe de Escalafón')
    <h3 class="doc-title">INFORME DE ESCALAFÓN Y ASCENSO DOCENTE</h3>
    <p class="doc-subtitle">Comisión de Carrera y Clasificación Académica</p>
@elseif($report->typeReport === 'Informe de Dedicación')
    <h3 class="doc-title">INFORME DE DEDICACIÓN Y CARGA HORARIA</h3>
    <p class="doc-subtitle">Control de Cumplimiento de Funciones Universitarias</p>
@else
    <h3 class="doc-title">{{ strtoupper($report->typeReport ?? 'INFORME ACADÉMICO') }}</h3>
    <p class="doc-subtitle">Expediente Oficial de Control Académico y Administrativo</p>
@endif

<table class="data-table">
    <thead>
        <tr>
            <th colspan="2" style="background-color: #003366; color: #ffffff; text-align: center;">
                DATOS DE FILIACIÓN INSTITUCIONAL DEL DOCENTE
            </th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="width: 32%;"><strong>Docente Ordinario:</strong></td>
            <td style="width: 68%;">{{ $report->teacher ? $report->teacher->full_name : 'No Asignado' }}</td>
        </tr>
        <tr>
            <td><strong>Cédula de Identidad:</strong></td>
            <td><strong>V-{{ $report->teacher_cdi }}</strong></td>
        </tr>
        <tr>
            <td><strong>Sede Universitaria:</strong></td>
            <td>{{ $report->sede?->nombre ?? $report->teacher?->sede?->nombre ?? 'Sin Asignar' }}</td>
        </tr>
        <tr>
            <td><strong>Área Académica:</strong></td>
            <td>{{ $report->area?->nombre ?? $report->teacher?->area?->nombre ?? 'Sin Asignar' }}</td>
        </tr>
        <tr>
            <td><strong>Categoría Académica:</strong></td>
            <td><strong>{{ $report->category?->current_category ?? $report->teacher?->category?->current_category ?? 'Instructor' }}</strong></td>
        </tr>
        <tr>
            <td><strong>Dedicación Contratada:</strong></td>
            <td>{{ $report->dedication?->name ?? $report->teacher?->dedication?->name ?? 'Sin Asignar' }}</td>
        </tr>
        @if($report->email || $report->teacher?->email)
        <tr>
            <td><strong>Correo Institucional:</strong></td>
            <td>{{ $report->email ?? $report->teacher?->email }}</td>
        </tr>
        @endif
    </tbody>
</table>

@if($report->report)
<div style="margin-top: 5mm;">
    <h4 style="color: #003366; font-size: 9.5pt; text-transform: uppercase; margin-bottom: 2mm; border-bottom: 1px solid #cbd5e1; padding-bottom: 1mm;">
        Dictamen / Contenido del Documento:
    </h4>
    <div style="background-color: #f8fafc; border: 1px solid #cbd5e1; padding: 3mm 4mm; border-radius: 3px; font-size: 9.5pt; line-height: 1.6; text-align: justify;">
        {!! nl2br(e($report->report)) !!}
    </div>
</div>
@endif

@if($report->info && $report->typeReport !== 'Memorando Administrativo')
<div style="margin-top: 4mm;">
    <h4 style="color: #003366; font-size: 9pt; text-transform: uppercase; margin-bottom: 1.5mm;">
        Observaciones y Notas Oficiales:
    </h4>
    <div style="background-color: #f8fafc; border: 1px solid #cbd5e1; padding: 2.5mm 4mm; border-radius: 3px; font-size: 8.5pt; color: #475569; line-height: 1.5;">
        {!! nl2br(e($report->info)) !!}
    </div>
</div>
@endif

<div class="signatures-container">
    <table class="signatures-table">
        <tr>
            <td>
                <div class="signature-line"></div>
                <div class="signature-name">Jefe de Área Académica</div>
                <div class="signature-role">
                    {{ $report->area?->nombre ?? 'Área de Adscripción' }}<br>
                    Sede {{ $report->sede?->nombre ?? 'Universitaria' }} &bull; UNERG
                </div>
            </td>
            <td>
                <div class="signature-line"></div>
                <div class="signature-name">Vicerrectorado Académico</div>
                <div class="signature-role">
                    Dirección de Gestión Docente y Control Curricular<br>
                    Sello Oficial Húmedo Institucional
                </div>
            </td>
        </tr>
    </table>
</div>
@endsection
