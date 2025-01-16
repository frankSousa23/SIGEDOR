<x-filament-widgets::widget>
    <x-filament::section>
        <h3>Supervisión de Docentes</h3>
        <p>Docentes Activos: {{ $activeTeachers }}</p>
        <p>Permisos Pendientes: {{ $pendingPermissions }}</p>
    </x-filament::section>
</x-filament-widgets::widget>