@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Gestión de Períodos Escolares</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('periodos.create') }}" class="btn btn-sm btn-primary">
            + Crear Nuevo Período
        </a>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success mt-3" role="alert">
        {{ session('success') }}
    </div>
@endif

<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Nombre del Período</th>
                <th scope="col">Fecha de Inicio</th>
                <th scope="col">Fecha de Fin</th>
                <th scope="col">Activo</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($periodos as $periodo)
                <tr>
                    <td>{{ $periodo->id }}</td>
                    <td>{{ $periodo->name }}</td>
                    <td>{{ $periodo->start_date }}</td>
                    <td>{{ $periodo->end_date }}</td>
                    <td>
                        @if ($periodo->is_active)
                            <span class="badge bg-success">Sí</span>
                        @else
                            <span class="badge bg-secondary">No</span>
                        @endif
                    </td>
                    <td classd="d-flex">
                        <a href="{{ route('periodos.edit', $periodo->id) }}" class="btn btn-sm btn-warning me-1">Editar</a>

                        <form action="{{ route('periodos.destroy', $periodo->id) }}" method="post" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este período?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No hay períodos registrados aún.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection