<x-filament-widgets::widget>
    <x-filament::section>
        <h3>Estadísticas Generales</h3>
        @if($totalTeachers > 0 || $approvedPermissions > 0 || $activeUsers > 0)
            <p>Total de Docentes: {{ $totalTeachers }}</p>
            <p>Permisos Aprobados: {{ $approvedPermissions }}</p>
            <p>Usuarios Activos: {{ $activeUsers }}</p>
        @else
            <p>No hay datos disponibles en este momento.</p>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>