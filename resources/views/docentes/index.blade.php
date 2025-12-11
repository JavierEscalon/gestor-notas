@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Gestión de Planta Docente</h1>
    <a href="{{ route('docentes.create') }}" class="btn btn-success">
        <i class="bi bi-person-plus-fill"></i> Contratar Nuevo Docente
    </a>
</div>

<div class="card mb-4 shadow-sm border-0 bg-light">
    <div class="card-body p-3">
        <form action="{{ route('docentes.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-8">
                <label class="form-label small text-muted fw-bold">Buscar Docente</label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Nombre, Apellido o Correo..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-dark w-100">Buscar</button>
                @if(request()->has('search'))
                    <a href="{{ route('docentes.index') }}" class="btn btn-outline-secondary" title="Limpiar"><i class="bi bi-x-lg"></i></a>
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
                        <th class="ps-4">Docente</th>
                        <th>Contacto (Email / Tel)</th>
                        <th>Especialidad</th>
                        <th>Fecha Ingreso</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($docentes as $docente)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="bg-success text-white rounded-circle d-flex justify-content-center align-items-center me-3" style="width: 40px; height: 40px;">
                                        {{ strtoupper(substr($docente->first_name, 0, 1)) }}{{ strtoupper(substr($docente->last_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <span class="fw-bold d-block">{{ $docente->last_name }}, {{ $docente->first_name }}</span>
                                        <small class="text-muted">ID: {{ $docente->id }}</small>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <div class="d-flex flex-column">
                                    <span>{{ $docente->user->email }}</span>
                                    <small class="text-muted"><i class="bi bi-telephone"></i> {{ $docente->phone ?? 'N/A' }}</small>
                                </div>
                            </td>

                            <td>
                                <span class="badge bg-info text-dark bg-opacity-25 border border-info">
                                    {{ $docente->specialty }}
                                </span>
                            </td>

                            <td>
                                <small class="text-muted">{{ $docente->created_at->format('d/m/Y') }}</small>
                            </td>

                            <td class="text-end pe-4">
                                <div class="d-flex gap-2 justify-content-end">
                                    <a href="{{ route('docentes.edit', $docente->id) }}" class="btn btn-sm btn-warning text-dark fw-bold shadow-sm">
                                        <i class="bi bi-pencil-square"></i> Editar
                                    </a>
                                    
                                    <form action="{{ route('docentes.destroy', $docente->id) }}" method="post" onsubmit="return confirm('¿Desvincular a este docente?');">
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
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-people display-6 d-block mb-2"></i>
                                No se encontraron docentes registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white border-0 py-3">
        {{ $docentes->links() }}
    </div>
</div>
@endsection