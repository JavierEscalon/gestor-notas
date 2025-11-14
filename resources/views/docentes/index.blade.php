@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Gestión De Docentes</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('docentes.create') }}" class="btn btn-sm btn-primary">
            + Crear Nuevo Docente
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
                <th scope="col">Nombre</th>
                <th scope="col">Email (Usuario)</th>
                <th scope="col">Especialidad</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($docentes as $docente)
                <tr>
                    <td>{{ $docente->id }}</td>
                    <td>{{ $docente->first_name }} {{ $docente->last_name }}</td>

                    <td>{{ $docente->user->email }}</td>

                    <td>{{ $docente->specialty }}</td>
                    <td class="d-flex">
                        <a href="{{ route('docentes.edit', $docente->id) }}" class="btn btn-sm btn-warning me-1">Editar</a>

                        <form action="{{ route('docentes.destroy', $docente->id) }}" method="post" onsubmit="return confirm('¿Estás seguro de que deseas eliminar a este docente?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No hay docentes registrados aún.</td>
                </tr>
            @endforelse
    </tbody>
    </table>
</div>

@endsection