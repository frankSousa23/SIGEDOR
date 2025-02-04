@if(auth()->user()->hasRole('admin'))

@elseif(auth()->user()->hasRole('area_manager'))

@elseif(auth()->user()->hasRole('teacher'))

@endif
