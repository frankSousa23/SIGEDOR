@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Panel de Jefe de Área') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h2>Bienvenido Jefe de Área</h2>
                    <p>Email: {{ Auth::user()->email }}</p>
                    
                    <div class="mt-4">
                        <h3>Acciones Disponibles:</h3>
                        <ul class="list-group">
                            <li class="list-group-item">Gestionar Profesores</li>
                            <li class="list-group-item">Ver Reportes del Área</li>
                            <li class="list-group-item">Gestionar Dedicaciones</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
