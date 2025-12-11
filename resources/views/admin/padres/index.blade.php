@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Gestión de Padres de Familia</h1>
    <a href="{{ route('admin.padres.create') }}" class="btn btn-primary shadow-sm">
        <i class="bi bi-person-plus-fill"></i> Nuevo Padre
    </a>
</div>

<div class="card mb-4 shadow-sm border-0 bg-light">
    <div class="card-body p-3">
        <form action="{{ route('admin.padres.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-8">
                <label class="form-label small text-muted fw-bold">Buscar Padre</label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Nombre, Teléfono o Correo..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-dark w-100">Buscar</button>
                @if(request()->has('search'))
                    <a href="{{ route('admin.padres.index') }}" class="btn btn-outline-secondary" title="Limpiar"><i class="bi bi-x-lg"></i></a>
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
                        <th class="ps-4">Padre / Tutor</th>
                        <th>Contacto</th>
                        <th>Hijos Asignados</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($padres as $padre)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary text-white rounded-circle d-flex justify-content-center align-items-center me-3 shadow-sm" style="width: 40px; height: 40px;">
                                        <i class="bi bi-person-fill"></i>
                                    </div>
                                    <div>
                                        <span class="fw-bold d-block">{{ $padre->last_name }}, {{ $padre->first_name }}</span>
                                        <small class="text-muted">ID: {{ $padre->id }}</small>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <div class="d-flex flex-column">
                                    <span class="text-primary fw-bold"><i class="bi bi-envelope"></i> {{ $padre->user->email }}</span>
                                    <small class="text-muted"><i class="bi bi-telephone"></i> {{ $padre->phone ?? 'Sin teléfono' }}</small>
                                </div>
                            </td>

                            <td>
                                @if($padre->alumnos->isEmpty())
                                    <span class="badge bg-light text-muted border">Sin asignar</span>
                                @else
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach($padre->alumnos as $hijo)
                                            <span class="badge bg-info text-dark border border-info bg-opacity-25">
                                                <i class="bi bi-backpack2-fill"></i> {{ $hijo->first_name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </td>

                            <td class="text-center">
                                <div class="d-flex gap-2 justify-content-end">
                                    <a href="{{ route('admin.padres.hijos', $padre->id) }}" class="btn btn-sm btn-info text-white fw-bold shadow-sm" title="Gestionar Hijos">
                                        <i class="bi bi-people-fill"></i> Hijos
                                    </a>

                                    <a href="{{ route('admin.padres.edit', $padre->id) }}" class="btn btn-sm btn-warning text-dark fw-bold shadow-sm" title="Editar Datos">
                                        <i class="bi bi-pencil-square"></i> Editar
                                    </a>
                                    
                                    <form action="{{ route('admin.padres.destroy', $padre->id) }}" method="post" onsubmit="return confirm('¿Eliminar este padre de familia? Se desvincularán los alumnos asignados.');">
                                        @csrf 
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger text-white fw-bold shadow-sm" title="Eliminar Registro">
                                            <i class="bi bi-trash-fill"></i> Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="bi bi-person-x display-6 d-block mb-2"></i>
                                No se encontraron padres registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if(method_exists($padres, 'links'))
        <div class="card-footer bg-white border-0 py-3">
            {{ $padres->links() }}
        </div>
    @endif
</div>
@endsection