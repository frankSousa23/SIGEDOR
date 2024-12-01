<x-filament-widgets::widget>
    <x-filament::section>
        @php
            $teacher = $this->getTeacher();
        @endphp

        @if($teacher)
            <div class="space-y-4">
                <div class="flex items-center space-x-4">
                    <div class="flex-1">
                        <h3 class="text-lg font-medium">{{ $teacher->name }} {{ $teacher->surName }}</h3>
                        <p class="text-sm text-gray-500">{{ $teacher->email }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Cédula</h4>
                        <p>{{ $teacher->cdi }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Teléfono</h4>
                        <p>{{ $teacher->phone }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Fecha de Nacimiento</h4>
                        <p>{{ $teacher->birthDate }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Fecha de Promoción</h4>
                        <p>{{ $teacher->datePromotion }}</p>
                    </div>
                    @if($teacher->site)
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Sede</h4>
                            <p>{{ $teacher->site->site }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Área</h4>
                            <p>{{ $teacher->site->area }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Programa</h4>
                            <p>{{ $teacher->site->program }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Unidad Curricular</h4>
                            <p>{{ $teacher->site->uc }}</p>
                        </div>
                    @endif
                    @if($teacher->dedication)
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Dedicación</h4>
                            <p>{{ $teacher->dedication->dedication }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Horas Semanales</h4>
                            <p>{{ $teacher->dedication->weekHours }} horas</p>
                        </div>
                    @endif
                    @if($teacher->category)
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Categoría Actual</h4>
                            <p>{{ $teacher->category->current_category }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Último Título</h4>
                            <p>{{ $teacher->category->lastTitle }}</p>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div class="text-center text-gray-500">
                No se encontró información del profesor
            </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
