@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Gestión de Cursos</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('cursos.create') }}" class="btn btn-sm btn-primary">
            + Crear Nuevo Curso
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
                <th scope="col">Materia</th>
                <th scope="col">Docente</th>
                <th scope="col">Grado</th>
                <th scope="col">Sección</th>
                <th scope="col">Período</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($cursos as $curso)
                <tr>
                    <td>{{ $curso->id }}</td>
                    <td>{{ $curso->materia->name }}</td>
                    <td>{{ $curso->docente->first_name }} {{ $curso->docente->last_name }}</td>
                    <td>{{ $curso->grado->name }}</td>
                    <td>{{ $curso->seccion->name }}</td>
                    <td>{{ $curso->periodo->name }}</td>
                    <td class="d-flex">
                        <a href="{{ route('cursos.show', $curso->id) }}" class="btn btn-sm btn-info me-1">
                            Inscribir Alumnos
                        </a>    
                        <a href="{{ route('cursos.edit', $curso->id) }}" class="btn btn-sm btn-warning me-1">Editar</a>
                        <form action="{{ route('cursos.destroy', $curso->id) }}" method="post" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este curso?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">No hay cursos registrados aún.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection