<x-filament-widgets::widget>
    <x-filament::section>
        <h3>Mis Permisos</h3>
        <ul>
            @foreach($teacherPermissions as $permission)
                <li>{{ $permission->name }} - {{ $permission->status }}</li>
            @endforeach
        </ul>
    </x-filament::section>
</x-filament-widgets::widget>