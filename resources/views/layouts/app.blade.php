
@if(auth()->user()->hasRole('admin'))
    <li><a href="{{ route('users.index') }}">Usuarios</a></li>
@endif

@if(auth()->user()->hasAnyRole(['admin', 'area_manager', 'teacher']))
    <li><a href="{{ route('reports.index') }}">Reportes</a></li>
@endif

