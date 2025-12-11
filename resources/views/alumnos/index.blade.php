@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Gestión de Expedientes Estudiantiles</h1>
    <a href="{{ route('alumnos.create') }}" class="btn btn-primary">
        <i class="bi bi-person-plus-fill"></i> Matricular Nuevo Alumno
    </a>
</div>

<div class="card mb-4 shadow-sm border-0 bg-light">
    <div class="card-body p-3">
        <form action="{{ route('alumnos.index') }}" method="GET" class="row g-3 align-items-end">
            
            <div class="col-md-5">
                <label class="form-label small text-muted fw-bold">Buscar por Nombre o Carnet</label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Ej. Juan Pérez o 2025-10..." value="{{ request('search') }}">
                </div>
            </div>

            <div class="col-md-3">
                <label class="form-label small text-muted fw-bold">Filtrar por Grado</label>
                <select name="grado_id" class="form-select">
                    <option value="">Todos los Grados</option>
                    @foreach($grados as $grado)
                        <option value="{{ $grado->id }}" {{ request('grado_id') == $grado->id ? 'selected' : '' }}>
                            {{ $grado->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-dark w-100">Aplicar Filtros</button>
                @if(request()->has('search') || request()->has('grado_id'))
                    <a href="{{ route('alumnos.index') }}" class="btn btn-outline-secondary" title="Limpiar"><i class="bi bi-x-lg"></i></a>
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
                        <th class="ps-4">Carnet</th>
                        <th>Estudiante</th>
                        <th>Contacto</th>
                        <th>Grado</th>
                        <th>Sección</th>
                        <th>Estado</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($alumnos as $alumno)
                        <tr>
                            <td class="ps-4 fw-bold text-primary">{{ $alumno->student_id_code }}</td>
                            
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold">{{ $alumno->last_name }}, {{ $alumno->first_name }}</span>
                                    <small class="text-muted">{{ $alumno->user?->email ?? 'Sin Usuario' }}</small>
                                </div>
                            </td>

                            <td>
                                <small>
                                    <i class="bi bi-telephone"></i> {{ $alumno->phone ?? '-' }} <br>
                                    <span class="text-muted">Emerg: {{ $alumno->emergency_contact_phone ?? '-' }}</span>
                                </small>
                            </td>
                            
                            <td>
                                <span class="badge bg-primary bg-opacity-10 text-primary">
                                    {{ $alumno->grado?->name ?? 'N/A' }}
                                </span>
                            </td>
                            
                            <td>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                    {{ $alumno->seccion?->name ?? 'N/A' }}
                                </span>
                            </td>

                            <td>
                                @if($alumno->status == 'activo')
                                    <span class="badge bg-success bg-opacity-10 text-success">Activo</span>
                                @elseif($alumno->status == 'retirado')
                                    <span class="badge bg-danger bg-opacity-10 text-danger">Retirado</span>
                                @else
                                    <span class="badge bg-secondary">Inactivo</span>
                                @endif
                            </td>

                            <td class="text-end pe-4">
                                <div class="d-flex gap-2 justify-content-end">
                                    <a href="{{ route('alumnos.edit', $alumno->id) }}" class="btn btn-sm btn-warning text-dark fw-bold shadow-sm">
                                        <i class="bi bi-pencil-square"></i> Editar
                                    </a>
                                    
                                    <button type="button" class="btn btn-sm btn-danger text-white fw-bold shadow-sm" onclick="confirmDelete({{ $alumno->id }})">
                                        <i class="bi bi-trash"></i> Eliminar
                                    </button>
                                </div>

                                <form id="delete-form-{{ $alumno->id }}" action="{{ route('alumnos.destroy', $alumno->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-search display-6 d-block mb-2"></i>
                                No se encontraron estudiantes con esos criterios.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white border-0 py-3">
        {{ $alumnos->links() }} 
    </div>
</div>

<script>
    function confirmDelete(id) {
        if(confirm('¿Estás seguro de eliminar este expediente? Esta acción es irreversible.')) {
            document.getElementById('delete-form-'+id).submit();
        }
    }
</script>
@endsection