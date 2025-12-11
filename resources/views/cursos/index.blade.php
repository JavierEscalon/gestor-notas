@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Gestión de Cursos Académicos</h1>
    <a href="{{ route('cursos.create') }}" class="btn btn-primary shadow-sm">
        <i class="bi bi-journal-plus"></i> Aperturar Curso
    </a>
</div>

<div class="card mb-4 shadow-sm border-0 bg-light">
    <div class="card-body p-3">
        <form action="{{ route('cursos.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-8">
                <label class="form-label small text-muted fw-bold">Buscar Curso</label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Materia, Docente o Grado..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-dark w-100">Buscar</button>
                @if(request()->has('search'))
                    <a href="{{ route('cursos.index') }}" class="btn btn-outline-secondary" title="Limpiar"><i class="bi bi-x-lg"></i></a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-secondary">
                    <tr>
                        <th class="ps-4">Materia</th>
                        <th>Docente</th>
                        <th>Grado / Sección</th>
                        <th>Período</th>
                        <th>Estado</th>
                        <th class="text-center pe-2">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cursos as $curso)
                        <tr>
                            <td class="ps-4">
                                <span class="fw-bold text-primary">
                                    <i class="bi bi-journal-text me-1"></i>{{ $curso->materia->name }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold">{{ $curso->docente->first_name }} {{ $curso->docente->last_name }}</span>
                                    <small class="text-muted">{{ $curso->docente->user->email }}</small>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info text-dark bg-opacity-25 border border-info">
                                    {{ $curso->grado->name }} - "{{ $curso->seccion->name }}"
                                </span>
                            </td>
                            <td>
                                <small class="text-muted fw-bold">{{ $curso->periodo->name }}</small>
                            </td>
                            <td>
                                @if($curso->is_calificaciones_closed)
                                    <span class="badge bg-danger">Cerrado</span>
                                @else
                                    <span class="badge bg-success">Abierto</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex gap-2 justify-content-end">
                                    <a href="{{ route('cursos.show', $curso->id) }}" class="btn btn-sm btn-info text-white fw-bold shadow-sm">
                                        <i class="bi bi-people-fill"></i> Inscripción
                                    </a>

                                    <a href="{{ route('cursos.edit', $curso->id) }}" class="btn btn-sm btn-warning text-dark fw-bold shadow-sm">
                                        <i class="bi bi-pencil-square"></i> Editar
                                    </a>
                                    
                                    <form action="{{ route('cursos.destroy', $curso->id) }}" method="post" onsubmit="return confirm('¿Estás seguro de eliminar este curso? Se borrarán todas las notas asociadas.');">
                                        @csrf 
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger text-white fw-bold shadow-sm">
                                            <i class="bi bi-trash-fill"></i> Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-journal-x display-6 d-block mb-2"></i>
                                No se encontraron cursos que coincidan con la búsqueda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white border-0 py-3">
        {{ $cursos->links() }}
    </div>
</div>
@endsection