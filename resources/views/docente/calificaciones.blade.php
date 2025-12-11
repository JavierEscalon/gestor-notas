@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Gestión de Calificaciones</h1>
    <a href="{{ route('docente.dashboard') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>

<div class="card mb-4 shadow-sm border-primary border-start border-4">
    <div class="card-body">
        <h4 class="text-primary fw-bold mb-1">{{ $curso->materia->name }}</h4>
        <p class="mb-0 text-muted">
            <strong>{{ $curso->grado->name }} "{{ $curso->seccion->name }}"</strong> | {{ $curso->periodo->name }}
        </p>
    </div>
</div>

<div class="card mb-4 shadow-sm border-0">
    <div class="card-header bg-light fw-bold">
        <i class="bi bi-list-check"></i> Actividades Evaluadas
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-secondary">
                    <tr>
                        <th class="ps-4">Actividad</th>
                        <th>Tipo</th>
                        <th class="text-center">Ponderación</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalPonderacion = 0; @endphp

                    @forelse ($actividadesRegistradas as $actividad)
                        @php $totalPonderacion += $actividad->percentage; @endphp
                        <tr>
                            <td class="ps-4 fw-bold">{{ $actividad->activity_name }}</td>
                            <td><span class="badge bg-secondary">{{ $actividad->tipoActividad->name }}</span></td>
                            <td class="text-center">
                                <span class="badge bg-info text-dark border border-info">{{ $actividad->percentage }}%</span>
                            </td>
                            <td class="text-end pe-4">
                                @if(!$curso->is_calificaciones_closed)
                                    <div class="d-flex gap-2 justify-content-end">
                                        <a href="{{ route('docente.calificaciones.edit', ['curso' => $curso->id, 'activity_name' => $actividad->activity_name]) }}" 
                                           class="btn btn-sm btn-warning text-dark fw-bold shadow-sm">
                                            <i class="bi bi-pencil-square"></i> Editar
                                        </a>
                                        
                                        <form action="{{ route('docente.calificaciones.destroy', ['curso' => $curso->id, 'activity_name' => $actividad->activity_name]) }}" 
                                              method="post" 
                                              class="form-eliminar-actividad">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger text-white fw-bold shadow-sm">
                                                <i class="bi bi-trash-fill"></i> Eliminar
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <small class="text-muted"><i class="bi bi-lock-fill"></i> Cerrado</small>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center py-4 text-muted">No hay actividades registradas aún.</td></tr>
                    @endforelse
                </tbody>
                
                @if($actividadesRegistradas->count() > 0)
                    <tfoot class="table-light border-top">
                        <tr>
                            <td colspan="2" class="text-end fw-bold text-muted">Total Acumulado:</td>
                            <td class="text-center fw-bold fs-6 {{ $totalPonderacion > 100 ? 'text-danger' : ($totalPonderacion == 100 ? 'text-success' : 'text-primary') }}">
                                {{ $totalPonderacion }}%
                            </td>
                            <td class="text-start ps-3 small text-muted">
                                @if($totalPonderacion < 100)
                                    (Restante: <strong>{{ 100 - $totalPonderacion }}%</strong>)
                                @elseif($totalPonderacion == 100)
                                    <span class="text-success"><i class="bi bi-check-circle-fill"></i> Completo</span>
                                @else
                                    <span class="text-danger"><i class="bi bi-exclamation-triangle-fill"></i> Excede el 100%</span>
                                @endif
                            </td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>

@if(!$curso->is_calificaciones_closed)
<div class="card shadow-sm border-0">
    <div class="card-header bg-primary text-white fw-bold">
        <i class="bi bi-plus-circle"></i> Registrar Nueva Actividad
    </div>
    <div class="card-body">
        
        @if($totalPonderacion >= 100)
            <div class="alert alert-warning d-flex align-items-center" role="alert">
                <i class="bi bi-exclamation-circle-fill fs-4 me-2"></i>
                <div>
                    <strong>Atención:</strong> La ponderación ya ha alcanzado o superado el 100%. Verifica antes de agregar más actividades.
                </div>
            </div>
        @endif

        <form action="{{ route('docente.cursos.calificaciones.store', $curso->id) }}" method="POST">
            @csrf
            
            <div class="row g-3 mb-4">
                <div class="col-md-5">
                    <label class="form-label fw-bold">Nombre de la Actividad</label>
                    <input type="text" class="form-control" name="activity_name" placeholder="Ej. Examen Parcial" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Tipo</label>
                    <select class="form-select" name="tipo_actividad_id" required>
                        <option value="">Seleccionar...</option>
                        @foreach ($tiposActividad as $tipo)
                            <option value="{{ $tipo->id }}">{{ $tipo->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Ponderación (%)</label>
                    <div class="input-group">
                        <input type="number" class="form-control" name="percentage" min="1" max="{{ max(0, 100 - $totalPonderacion) }}" placeholder="Max: {{ max(0, 100 - $totalPonderacion) }}" required>
                        <span class="input-group-text">%</span>
                    </div>
                    @if($totalPonderacion < 100)
                        <div class="form-text text-success">Disponible: {{ 100 - $totalPonderacion }}%</div>
                    @endif
                </div>
            </div>

            <h6 class="text-muted border-bottom pb-2 mb-3">Ingreso de Notas</h6>
            
            <div class="table-responsive mb-3" style="max-height: 400px; overflow-y: auto;">
                <table class="table table-bordered align-middle">
                    <thead class="table-light sticky-top">
                        <tr>
                            <th>Estudiante</th>
                            <th style="width: 150px;">Nota (0-10)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($curso->alumnos as $alumno)
                            <tr>
                                <td>
                                    {{ $alumno->last_name }}, {{ $alumno->first_name }}
                                    <small class="d-block text-muted">{{ $alumno->student_id_code }}</small>
                                </td>
                                <td>
                                    <input type="number" class="form-control text-center fw-bold" name="scores[{{ $alumno->id }}]" min="0" max="10" step="0.1" required>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="text-center text-muted">No hay alumnos inscritos.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($curso->alumnos->count() > 0)
                <button class="btn btn-success w-100 btn-lg shadow-sm" type="submit">
                    <i class="bi bi-save"></i> Guardar Actividad y Notas
                </button>
            @endif
        </form>
    </div>
</div>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteForms = document.querySelectorAll('.form-eliminar-actividad');
        
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault(); 

                Swal.fire({
                    title: '¿Eliminar actividad?',
                    text: "Se borrarán todas las notas asociadas a esta actividad. No se puede deshacer.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        });
    });
</script>
@endsection