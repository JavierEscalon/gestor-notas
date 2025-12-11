@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h2">Gestión de Matrícula</h1>
        <p class="text-muted mb-0">Administración de alumnos para el curso seleccionado.</p>
    </div>
    <a href="{{ route('cursos.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Volver a Cursos
    </a>
</div>

<div class="card mb-4 shadow-sm border-primary border-start border-4">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-9">
                <h4 class="card-title text-primary fw-bold mb-2">
                    <i class="bi bi-journal-bookmark-fill"></i> {{ $curso->materia->name }}
                </h4>
                <div class="d-flex flex-wrap gap-4 text-secondary">
                    <span><i class="bi bi-person-badge-fill"></i> Docente: <strong>{{ $curso->docente->first_name }} {{ $curso->docente->last_name }}</strong></span>
                    <span><i class="bi bi-mortarboard-fill"></i> Grado: <strong>{{ $curso->grado->name }} "{{ $curso->seccion->name }}"</strong></span>
                    <span><i class="bi bi-calendar-event-fill"></i> Período: <strong>{{ $curso->periodo->name }}</strong></span>
                </div>
            </div>
            <div class="col-md-3 text-end">
                <div class="fs-1 fw-bold text-success">{{ $alumnosInscritos->count() }}</div>
                <small class="text-muted text-uppercase fw-bold">Alumnos Inscritos</small>
            </div>
        </div>
    </div>
</div>

@if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        });
    </script>
@endif

<div class="row">
    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-light fw-bold text-success border-bottom-0">
                <i class="bi bi-person-check-fill"></i> Listado de Inscritos
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th class="ps-3">Estudiante</th>
                                <th>Carnet</th>
                                <th class="text-end pe-3">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($alumnosInscritos as $alumno)
                                <tr>
                                    <td class="ps-3 fw-bold">{{ $alumno->last_name }}, {{ $alumno->first_name }}</td>
                                    <td class="text-muted small">{{ $alumno->student_id_code }}</td>
                                    <td class="text-end pe-3">
                                        <form action="{{ route('cursos.quitar', ['curso' => $curso->id, 'alumno' => $alumno->id]) }}" 
                                              method="post" 
                                              class="form-desinscribir">
                                            @csrf
                                            @method('DELETE')
                                            
                                            <button type="submit" class="btn btn-danger btn-sm shadow-sm" title="Quitar del curso">
                                                <i class="bi bi-trash-fill"></i> Quitar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-5 text-muted">
                                        <i class="bi bi-clipboard-x display-6 d-block mb-2 text-secondary opacity-50"></i>
                                        Aún no hay alumnos inscritos en este curso.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-primary text-white fw-bold border-bottom-0">
                <i class="bi bi-person-plus-fill"></i> Disponibles para Inscribir ({{ $alumnosDisponibles->count() }})
            </div>
            
            <div class="card-body d-flex flex-column p-0">
                <form action="{{ route('cursos.inscribir', $curso->id) }}" method="post" class="d-flex flex-column h-100">
                    @csrf
                    
                    <div class="flex-grow-1 overflow-auto" style="max-height: 420px;">
                        @if($alumnosDisponibles->isNotEmpty())
                            <div class="list-group-item bg-light border-bottom p-3 sticky-top">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAll">
                                    <label class="form-check-label fw-bold small text-uppercase text-muted cursor-pointer" for="selectAll">Seleccionar Todos</label>
                                </div>
                            </div>

                            <div class="list-group list-group-flush">
                                @foreach ($alumnosDisponibles as $alumno)
                                    <label class="list-group-item list-group-item-action d-flex align-items-center p-3 cursor-pointer">
                                        <input class="form-check-input me-3 student-checkbox" type="checkbox" name="alumnos_ids[]" value="{{ $alumno->id }}" style="transform: scale(1.2);">
                                        <div>
                                            <span class="fw-bold d-block">{{ $alumno->last_name }}, {{ $alumno->first_name }}</span>
                                            <small class="text-muted"><i class="bi bi-card-heading"></i> {{ $alumno->student_id_code }}</small>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5 px-4">
                                <div class="mb-3 text-success">
                                    <i class="bi bi-check-circle-fill display-4"></i>
                                </div>
                                <h5 class="text-muted">¡Todo listo!</h5>
                                <p class="text-muted small">Todos los estudiantes elegibles de este grado ya están inscritos.</p>
                            </div>
                        @endif
                    </div>

                    @if($alumnosDisponibles->isNotEmpty())
                        <div class="card-footer bg-white p-3 border-top">
                            <button type="submit" class="btn btn-primary w-100 btn-lg shadow-sm fw-bold">
                                <i class="bi bi-arrow-left-circle me-2"></i> Inscribir Seleccionados
                            </button>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // 1. Lógica para "Seleccionar Todos"
        const selectAllCheckbox = document.getElementById('selectAll');
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('.student-checkbox');
                checkboxes.forEach(cb => cb.checked = this.checked);
            });
        }

        // 2. INTERCEPTAR BOTÓN DE ELIMINAR (SWEETALERT2)
        const deleteForms = document.querySelectorAll('.form-desinscribir');
        
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault(); // Detener el envío automático

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "El alumno será removido de la lista de este curso.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33', // Rojo para confirmar borrar
                    cancelButtonColor: '#3085d6', // Azul para cancelar
                    confirmButtonText: 'Sí, quitar alumno',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true // Pone el cancelar a la izquierda (más seguro)
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit(); // Enviar formulario manualmente
                    }
                });
            });
        });
    });
</script>

<style>
    .cursor-pointer { cursor: pointer; }
    /* Ajuste para que la alerta se vea bien encima de todo */
    .swal2-container { z-index: 2000 !important; }
</style>
@endsection