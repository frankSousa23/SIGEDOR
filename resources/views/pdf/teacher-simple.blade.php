@extends('pdf.base-simple')

@section('title', 'Detalles Docente')

@section('content')
<table>
    <tr>
        <td>Nombre:</td>
        <td>{{ $teacher->name }}</td>
    </tr>
    <tr>
        <td>Email:</td>
        <td>{{ $teacher->email }}</td>
    </tr>
    <tr>
        <td>Teléfono:</td>
        <td>{{ $teacher->phone }}</td>
    </tr>
</table>
@endsection
