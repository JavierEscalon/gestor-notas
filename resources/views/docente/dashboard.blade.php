@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-4 border-bottom">
    <div>
        <h1 class="h2 text-primary fw-bold">Mis Cursos Asignados</h1>
        <p class="text-muted mb-0">Bienvenido al panel docente. Aquí están tus grupos activos.</p>
    </div>
</div>

@if($cursos->isEmpty())
    <div class="text-center py-5">
        <i class="bi bi-journal-x display-1 text-muted opacity-25"></i>
        <h4 class="text-muted mt-3">No tienes cursos asignados actualmente.</h4>
    </div>
@else
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @foreach($cursos as $curso)
            <div class="col">
                <div class="card h-100 shadow-sm course-card hover-card">
                    
                    <div class="card-header border-0 pt-3 {{ $curso->periodo->is_active ? 'bg-primary' : 'bg-secondary' }} text-white" 
                         style="border-radius: calc(0.375rem - 1px) calc(0.375rem - 1px) 0 0;">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-white text-dark bg-opacity-75 shadow-sm">
                                {{ $curso->grado->name }} "{{ $curso->seccion->name }}"
                            </span>
                            @if(!$curso->periodo->is_active)
                                <span class="badge bg-warning text-dark"><i class="bi bi-archive"></i> Histórico</span>
                            @endif
                        </div>
                        <h4 class="card-title fw-bold mt-2 mb-1 text-truncate" title="{{ $curso->materia->name }}">
                            {{ $curso->materia->name }}
                        </h4>
                        <small class="opacity-75"><i class="bi bi-calendar3"></i> {{ $curso->periodo->name }}</small>
                    </div>

                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center text-muted mb-3">
                            <small><i class="bi bi-people-fill"></i> Alumnos Inscritos</small>
                            @if($curso->is_calificaciones_closed)
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger">
                                    <i class="bi bi-lock-fill"></i> Cerrado
                                </span>
                            @else
                                <span class="badge bg-success bg-opacity-10 text-success border border-success">
                                    <i class="bi bi-unlock-fill"></i> Abierto
                                </span>
                            @endif
                        </div>
                        
                        <hr class="my-2 opacity-10">

                        <div class="d-grid gap-2 mt-auto">
                            @if(!$curso->is_calificaciones_closed)
                                <a href="{{ route('docente.cursos.calificaciones', $curso->id) }}" class="btn btn-primary shadow-sm fw-bold">
                                    <i class="bi bi-pencil-square"></i> Registrar Notas
                                </a>
                            @else
                                <button class="btn btn-secondary" disabled>
                                    <i class="bi bi-lock"></i> Notas Finalizadas
                                </button>
                            @endif

                            <div class="row g-2">
                                <div class="col-6">
                                    <a href="{{ route('docente.cursos.asistencia', $curso->id) }}" class="btn btn-outline-success w-100 fw-bold border-2">
                                        <i class="bi bi-calendar-check"></i> Asistencia
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="{{ route('docente.cursos.reporte', $curso->id) }}" class="btn btn-outline-warning text-dark w-100 fw-bold border-2">
                                        <i class="bi bi-file-earmark-bar-graph"></i> Reporte
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

<style>
    /* Estilo para el borde suave */
    .course-card {
        border: 1px solid #e0e0e0; /* Gris muy suave */
        transition: all 0.3s ease;
    }

    /* Efecto al pasar el mouse: Sube un poco, aumenta la sombra y el borde se oscurece sutilmente */
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.12) !important;
        border-color: #b0b0b0; /* Borde un poco más visible al hacer hover */
    }
</style>
@endsection