@extends('filament::layouts.app')

@section('content')
    <div>
        @if ($isAdmin)
            <h2>Bienvenido, Administrador</h2>
            <p>Aquí puedes gestionar usuarios, roles y permisos.</p>
            @include('filament.widgets.admin-statistics-widget')
        @elseif ($isAreaManager)
            <h2>Bienvenido, Jefe de Área</h2>
            <p>Aquí puedes supervisar a los docentes y gestionar permisos.</p>
            @include('filament.widgets.area-manager-overview-widget')
        @elseif ($isTeacher)
            <h2>Bienvenido, Profesor</h2>
            <p>Aquí puedes ver tus permisos y asignaciones.</p>
            @include('filament.widgets.teacher-permissions-widget')
        @else
            <h2>Acceso Denegado</h2>
            <p>No tienes permisos para acceder a esta sección.</p>
        @endif
    </div>
@endsection