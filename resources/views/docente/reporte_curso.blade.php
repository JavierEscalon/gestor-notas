@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Reporte de Rendimiento</h1>
    
    @if(!$curso->is_calificaciones_closed)
        <form action="{{ route('docente.cursos.cerrar', $curso->id) }}" method="POST" class="form-cerrar-periodo">
            @csrf
            <button type="submit" class="btn btn-danger shadow-sm fw-bold">
                <i class="bi bi-lock-fill"></i> Cerrar Período
            </button>
        </form>
    @else
        <span class="badge bg-danger fs-6 shadow-sm px-3 py-2">
            <i class="bi bi-lock-fill"></i> Período Finalizado
        </span>
    @endif
</div>

<div class="card mb-4 shadow-sm border-0 bg-light">
    <div class="card-body">
        <div class="row text-center text-md-start">
            <div class="col-md-4 mb-2"><strong>Materia:</strong> {{ $curso->materia->name }}</div>
            <div class="col-md-4 mb-2"><strong>Grupo:</strong> {{ $curso->grado->name }} "{{ $curso->seccion->name }}"</div>
            <div class="col-md-4 mb-2"><strong>Período:</strong> {{ $curso->periodo->name }}</div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0 align-middle text-center">
                <thead class="bg-dark text-white">
                    <tr>
                        <th class="text-start ps-3 bg-secondary">Estudiante</th>
                        @foreach($actividades as $actividad)
                            <th class="small">{{ $actividad }}</th>
                        @endforeach
                        <th class="bg-primary text-white" style="width: 100px;">Promedio</th>
                        <th style="width: 120px;">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reporte as $fila)
                        <tr>
                            <td class="text-start ps-3">
                                <strong>{{ $fila['alumno']->last_name }}, {{ $fila['alumno']->first_name }}</strong>
                                <br><small class="text-muted">{{ $fila['alumno']->student_id_code }}</small>
                            </td>
                            
                            @foreach($actividades as $actividad)
                                <td>{{ $fila['notas'][$actividad] ?? '-' }}</td>
                            @endforeach

                            <td class="fw-bold fs-5 {{ $fila['promedio'] >= 6 ? 'text-primary' : 'text-danger' }} bg-light">
                                {{ number_format($fila['promedio'], 1) }}
                            </td>

                            <td>
                                @if($fila['promedio'] >= 6)
                                    <span class="badge bg-success bg-opacity-25 text-success border border-success">Aprobado</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-25 text-danger border border-danger">Reprobado</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4 text-center">
    <a href="{{ route('docente.dashboard') }}" class="btn btn-secondary shadow-sm">
        <i class="bi bi-house-door-fill"></i> Volver al Inicio
    </a>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cerrarForms = document.querySelectorAll('.form-cerrar-periodo');
        
        cerrarForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault(); // Detener envío

                Swal.fire({
                    title: '¿Cerrar notas de este período?',
                    text: "¡Atención! Una vez cerrado, no podrás editar ni agregar más notas.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33', // Rojo
                    cancelButtonColor: '#3085d6', // Azul
                    confirmButtonText: 'Sí, cerrar definitivamente',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit(); // Enviar si confirma
                    }
                });
            });
        });
    });
</script>
@endsection