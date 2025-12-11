@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Control de Asistencia</h1>
    <a href="{{ route('docente.dashboard') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i> Volver</a>
</div>

<div class="card mb-4 shadow-sm border-0">
    <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <h4 class="mb-0 text-primary fw-bold">{{ $curso->materia->name }}</h4>
            <span class="badge bg-light text-dark border">{{ $curso->grado->name }} "{{ $curso->seccion->name }}"</span>
        </div>
        
        <form action="{{ route('docente.cursos.asistencia', $curso->id) }}" method="GET" class="d-flex align-items-center bg-light p-2 rounded border">
            <label for="fecha" class="me-2 fw-bold text-muted small text-uppercase">Fecha de Registro:</label>
            <input type="date" id="fecha" name="fecha" class="form-control form-control-sm fw-bold" value="{{ $fecha }}" onchange="this.form.submit()">
        </form>
    </div>
</div>

<form action="{{ route('docente.cursos.asistencia.store', $curso->id) }}" method="POST">
    @csrf
    <input type="hidden" name="fecha" value="{{ $fecha }}">

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th class="ps-4">Estudiante</th>
                            <th class="text-center" width="10%"><span class="badge bg-success">P</span> Presente</th>
                            <th class="text-center" width="10%"><span class="badge bg-danger">A</span> Ausente</th>
                            <th class="text-center" width="10%"><span class="badge bg-warning text-dark">T</span> Tardanza</th>
                            <th class="text-center" width="10%"><span class="badge bg-info text-dark">J</span> Justif.</th>
                            <th width="25%">Observación</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($curso->alumnos as $alumno)
                            @php
                                $estado = $asistencias[$alumno->id]->estado ?? 'presente';
                                $observacion = $asistencias[$alumno->id]->observacion ?? '';
                            @endphp
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-bold">{{ $alumno->last_name }}, {{ $alumno->first_name }}</span>
                                    <small class="d-block text-muted">{{ $alumno->student_id_code }}</small>
                                </td>
                                
                                <td class="text-center">
                                    <input type="radio" class="form-check-input border-2 border-success" name="asistencia[{{ $alumno->id }}]" value="presente" {{ $estado == 'presente' ? 'checked' : '' }} style="transform: scale(1.3);">
                                </td>
                                <td class="text-center">
                                    <input type="radio" class="form-check-input border-2 border-danger" name="asistencia[{{ $alumno->id }}]" value="ausente" {{ $estado == 'ausente' ? 'checked' : '' }} style="transform: scale(1.3);">
                                </td>
                                <td class="text-center">
                                    <input type="radio" class="form-check-input border-2 border-warning" name="asistencia[{{ $alumno->id }}]" value="tardanza" {{ $estado == 'tardanza' ? 'checked' : '' }} style="transform: scale(1.3);">
                                </td>
                                <td class="text-center">
                                    <input type="radio" class="form-check-input border-2 border-info" name="asistencia[{{ $alumno->id }}]" value="justificado" {{ $estado == 'justificado' ? 'checked' : '' }} style="transform: scale(1.3);">
                                </td>
                                
                                <td>
                                    <input type="text" class="form-control form-control-sm" name="observacion[{{ $alumno->id }}]" value="{{ $observacion }}" placeholder="---">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white p-3 text-end sticky-bottom">
            <button type="submit" class="btn btn-primary btn-lg shadow fw-bold px-5">
                <i class="bi bi-check-circle-fill me-2"></i> Guardar Asistencia
            </button>
        </div>
    </div>
</form>

@if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Configuración del Toast de SweetAlert2
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end', // Arriba a la derecha
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            // Disparar la alerta
            Toast.fire({
                icon: 'success',
                title: 'Hecho!',
                text: "{{ session('success') }}" // Mensaje que viene del controlador
            });
        });
    </script>
@endif

@endsection