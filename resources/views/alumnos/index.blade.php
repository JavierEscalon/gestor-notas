@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Gestión de Alumnos</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('alumnos.create') }}" class="btn btn-sm btn-primary">
            + Crear Nuevo Alumno
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
                <th scope="col">Carnet</th>
                <th scope="col">Nombre</th>
                <th scope="col">Email (Usuario)</th>
                <th scope="col">Grado</th>
                <th scope="col">Sección</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($alumnos as $alumno)
                <tr>
                    <td>{{ $alumno->id }}</td>
                    <td>{{ $alumno->student_id_code }}</td>
                    <td>{{ $alumno->first_name }} {{ $alumno->last_name }}</td>
                    
                    <td>{{ $alumno->user->email }}</td>
                    
                    <td>{{ $alumno->grado->name }}</td>
                    <td>{{ $alumno->seccion->name }}</td>

                    <td class="d-flex">
                        <a href="{{ route('alumnos.edit', $alumno->id) }}" class="btn btn-sm btn-warning me-1">Editar</a>

                        <form action="{{ route('alumnos.destroy', $alumno->id) }}" method="post" onsubmit="return confirm('¿Estás seguro de que deseas eliminar a este alumno?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">No hay alumnos registrados aún.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection