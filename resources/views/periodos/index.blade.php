@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Gestión de Períodos Escolares</h1>
    <a href="{{ route('periodos.create') }}" class="btn btn-primary">
        <i class="bi bi-calendar-plus"></i> Nuevo Período
    </a>
</div>

<div class="card mb-4 shadow-sm border-0 bg-light">
    <div class="card-body p-3">
        <form action="{{ route('periodos.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-8">
                <label class="form-label small text-muted fw-bold">Buscar Período</label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Ej. Trimestre 1 - 2025..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-dark w-100">Buscar</button>
                @if(request()->has('search'))
                    <a href="{{ route('periodos.index') }}" class="btn btn-outline-secondary" title="Limpiar"><i class="bi bi-x-lg"></i></a>
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
                        <th class="ps-4">Nombre del Período</th>
                        <th>Fecha de Inicio</th>
                        <th>Fecha de Fin</th>
                        <th>Estado</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($periodos as $periodo)
                        <tr class="{{ $periodo->is_active ? 'table-success bg-opacity-10' : '' }}">
                            <td class="ps-4">
                                <span class="fw-bold text-primary">
                                    <i class="bi bi-calendar-event me-2"></i>{{ $periodo->name }}
                                </span>
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($periodo->start_date)->format('d/m/Y') }}
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($periodo->end_date)->format('d/m/Y') }}
                            </td>
                            <td>
                                @if($periodo->is_active)
                                    <span class="badge bg-success text-white shadow-sm">
                                        <i class="bi bi-check-circle-fill"></i> Vigente
                                    </span>
                                @else
                                    <span class="badge bg-secondary text-white">Finalizado</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex gap-2 justify-content-end">
                                    <a href="{{ route('periodos.edit', $periodo->id) }}" class="btn btn-sm btn-warning text-dark fw-bold shadow-sm">
                                        <i class="bi bi-pencil-square"></i> Editar
                                    </a>
                                    
                                    <form action="{{ route('periodos.destroy', $periodo->id) }}" method="post" onsubmit="return confirm('¿Eliminar este período? Cuidado: Se borrarán cursos y notas asociadas.');">
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
                                <i class="bi bi-calendar-x display-6 d-block mb-2"></i>
                                No se encontraron períodos registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white border-0 py-3">
        {{ $periodos->links() }}
    </div>
</div>
@endsection