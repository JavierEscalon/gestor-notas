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
<table class="table table-striped table-sm align-middle">
        <thead>
            <tr>
                <th>ID</th>
                <th>Materia</th>
                <th>Docente</th>
                <th>Grado/Sección</th>
                <th>Período</th>
                <th>Estado</th> <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($cursos as $curso)
                <tr>
                    <td>{{ $curso->id }}</td>
                    <td>{{ $curso->materia->name }}</td>
                    <td>{{ $curso->docente->first_name }} {{ $curso->docente->last_name }}</td>
                    <td>{{ $curso->grado->name }} "{{ $curso->seccion->name }}"</td>
                    <td>{{ $curso->periodo->name }}</td>
                    
                    <td>
                        @if($curso->is_calificaciones_closed)
                            <span class="badge bg-danger">Cerrado</span>
                        @else
                            <span class="badge bg-success">Abierto</span>
                        @endif
                    </td>

                    <td class="d-flex">
                        @if($curso->is_calificaciones_closed)
                            <form action="{{ route('cursos.reabrir', $curso->id) }}" method="POST" class="me-2" onsubmit="return confirm('¿Reabrir este curso para edición?');">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Reabrir Período">
                                    Reabrir
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('cursos.show', $curso->id) }}" class="btn btn-sm btn-info me-1" title="Inscribir Alumnos">Inscripción</a>
                        <a href="{{ route('cursos.edit', $curso->id) }}" class="btn btn-sm btn-warning me-1">Editar</a>
                        
                        <form action="{{ route('cursos.destroy', $curso->id) }}" method="post" onsubmit="return confirm('¿Eliminar curso?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">X</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7">No hay cursos registrados.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection