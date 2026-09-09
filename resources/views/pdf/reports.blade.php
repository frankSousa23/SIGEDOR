@extends('pdf.base')

@section('page_size', 'A4 landscape')
@section('title', 'Reporte Consolidado de Memorandos y Dictámenes - SIGEDOR UNERG')

@section('content')
<h3 class="doc-title" style="margin-bottom: 2mm;">LISTADO CONSOLIDADO DE REPORTES Y MEMORANDOS ACADÉMICOS</h3>
<p class="doc-subtitle" style="margin-bottom: 5mm;">
    Registro Oficial de Documentos Emitidos &bull; Total de Registros: <strong>{{ $reports->count() }}</strong>
</p>

<table class="data-table" style="font-size: 8pt;">
    <thead>
        <tr>
            <th style="width: 10%;">Nº Memo</th>
            <th style="width: 16%;">Tipo de Informe</th>
            <th style="width: 22%;">Docente (Nombre y C.I.)</th>
            <th style="width: 14%;">Sede</th>
            <th style="width: 14%;">Área</th>
            <th style="width: 12%;">Categoría</th>
            <th style="width: 12%;">Fecha Emisión</th>
        </tr>
    </thead>
    <tbody>
        @forelse($reports as $rep)
            <tr>
                <td><strong>{{ $rep->memoNumber }}</strong></td>
                <td>{{ $rep->typeReport }}</td>
                <td>
                    {{ $rep->teacher ? $rep->teacher->full_name : 'No Asignado' }}
                    <br>
                    <span style="font-size: 7.5pt; color: #64748b;">C.I. {{ $rep->teacher_cdi }}</span>
                </td>
                <td>{{ $rep->sede?->nombre ?? 'Sin Asignar' }}</td>
                <td>{{ $rep->area?->nombre ?? 'Sin Asignar' }}</td>
                <td>{{ $rep->category?->current_category ?? $rep->teacher?->category?->current_category ?? 'Instructor' }}</td>
                <td>{{ $rep->created_at ? $rep->created_at->format('d/m/Y') : 'N/A' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="7" style="text-align: center; color: #64748b; padding: 10px;">
                    No se encontraron reportes registrados para los criterios seleccionados.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

<div style="margin-top: 6mm; text-align: right; font-size: 8pt; color: #475569;">
    <strong>Resumen:</strong> {{ $reports->count() }} documento(s) procesado(s) e incorporados a la presente relación.
</div>
@endsection
