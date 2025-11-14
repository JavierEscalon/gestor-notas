@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Gestión de Materias</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('materias.create') }}" class="btn btn-sm btn-primary">
            + Crear Nueva Materia
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
                <th scope="col">Nombre de la Materia</th>
                <th scope="col">Descripción</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($materias as $materia)
                <tr>
                    <td>{{ $materia->id }}</td>
                    <td>{{ $materia->name }}</td>
                    <td>{{ $materia->description }}</td>
                    <td class="d-flex">
                        <a href="{{ route('materias.edit', $materia->id) }}" class="btn btn-sm btn-warning me-1">Editar</a>

                        <form action="{{ route('materias.destroy', $materia->id) }}" method="post" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta materia?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No hay materias registradas aún.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection