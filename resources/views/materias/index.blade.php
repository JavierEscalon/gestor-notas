@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Catálogo de Materias</h1>
    <a href="{{ route('materias.create') }}" class="btn btn-primary">
        <i class="bi bi-journal-plus"></i> Nueva Materia
    </a>
</div>

<div class="card mb-4 shadow-sm border-0 bg-light">
    <div class="card-body p-3">
        <form action="{{ route('materias.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-8">
                <label class="form-label small text-muted fw-bold">Buscar Materia</label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Nombre de la asignatura..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-dark w-100">Buscar</button>
                @if(request()->has('search'))
                    <a href="{{ route('materias.index') }}" class="btn btn-outline-secondary" title="Limpiar"><i class="bi bi-x-lg"></i></a>
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
                        <th class="ps-4" style="width: 5%;">ID</th>
                        <th style="width: 30%;">Nombre de la Materia</th>
                        <th style="width: 45%;">Descripción</th>
                        <th class="text-end pe-4" style="width: 20%;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($materias as $materia)
                        <tr>
                            <td class="ps-4 fw-bold text-muted">#{{ $materia->id }}</td>
                            <td>
                                <span class="fw-bold text-primary">
                                    <i class="bi bi-journal-bookmark-fill me-2"></i>{{ $materia->name }}
                                </span>
                            </td>
                            <td>
                                <span class="text-muted small">
                                    {{ $materia->description ?? 'Sin descripción registrada.' }}
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex gap-2 justify-content-end">
                                    <a href="{{ route('materias.edit', $materia->id) }}" class="btn btn-sm btn-warning text-dark fw-bold shadow-sm">
                                        <i class="bi bi-pencil-square"></i> Editar
                                    </a>
                                    
                                    <form action="{{ route('materias.destroy', $materia->id) }}" method="post" onsubmit="return confirm('¿Eliminar esta materia? Esto podría afectar cursos existentes.');">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger text-white fw-bold shadow-sm">
                                            <i class="bi bi-trash"></i> Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="bi bi-journal-x display-6 d-block mb-2"></i>
                                No se encontraron materias registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white border-0 py-3">
        {{ $materias->links() }}
    </div>
</div>
@endsection