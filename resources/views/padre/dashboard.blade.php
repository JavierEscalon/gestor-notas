@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
    <div>
        <h1 class="h2 text-primary fw-bold">Seguimiento Académico</h1>
        <p class="text-muted mb-0">Selecciona un perfil para ver el detalle.</p>
    </div>
</div>

@if($hijos->isEmpty())
    <div class="alert alert-warning shadow-sm">
        <i class="bi bi-exclamation-triangle-fill"></i> No tienes estudiantes asignados. Contacta a administración.
    </div>
@else

    <ul class="nav nav-pills mb-5 d-flex justify-content-start gap-4 overflow-auto py-2 px-1" id="pills-tab" role="tablist" style="flex-wrap: nowrap;">
        @foreach($hijos as $index => $hijo)
            <li class="nav-item" role="presentation">
                <button class="nav-link btn-profile {{ $index == 0 ? 'active' : '' }} text-center p-0 bg-transparent border-0" 
                        id="pills-hijo-{{ $hijo->id }}-tab" 
                        data-bs-toggle="pill" 
                        data-bs-target="#pills-hijo-{{ $hijo->id }}" 
                        type="button" 
                        role="tab">
                    
                    <div class="avatar shadow-sm mb-2 d-flex align-items-center justify-content-center mx-auto position-relative">
                        <span class="fs-3 fw-bold">{{ substr($hijo->first_name, 0, 1) }}</span>
                    </div>
                    
                    <span class="d-block fw-bold text-dark name-text small">{{ explode(' ', $hijo->first_name)[0] }}</span>
                </button>
            </li>
        @endforeach
    </ul>

    <div class="tab-content" id="pills-tabContent">
        @foreach($hijos as $index => $hijo)
            <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}" 
                 id="pills-hijo-{{ $hijo->id }}" 
                 role="tabpanel">

                <div class="card shadow-sm border-0 mb-4 bg-primary text-white" style="background: linear-gradient(135deg, #0d6efd 0%, #0043a8 100%);">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-9">
                                <h3 class="fw-bold mb-1">{{ $hijo->first_name }} {{ $hijo->last_name }}</h3>
                                <p class="mb-0 opacity-75">
                                    <i class="bi bi-card-heading"></i> Carnet: <strong>{{ $hijo->student_id_code }}</strong> &nbsp;|&nbsp;
                                    <i class="bi bi-mortarboard"></i> Grado: <strong>{{ $hijo->grado->name }} "{{ $hijo->seccion->name }}"</strong>
                                </p>
                            </div>
                            <div class="col-md-3 text-md-end mt-3 mt-md-0">
                                <div class="badge bg-white text-primary fs-6 px-3 py-2 shadow-sm">
                                    <i class="bi bi-journal-check"></i> {{ $hijo->cursos->count() }} Materias
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <h5 class="mb-3 text-secondary ps-2 border-start border-4 border-primary">
                    &nbsp;Materias y Calificaciones
                </h5>

                <div class="accordion shadow-sm" id="accordion-{{ $hijo->id }}">
                    @foreach($hijo->cursos as $cIndex => $curso)
                        <div class="accordion-item border-0 mb-2 rounded overflow-hidden shadow-sm">
                            <h2 class="accordion-header" id="heading-{{ $hijo->id }}-{{ $curso->id }}">
                                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $hijo->id }}-{{ $curso->id }}">
                                    <div class="d-flex align-items-center w-100 me-3">
                                        <span class="me-auto text-primary">
                                            <i class="bi bi-book me-2"></i>{{ $curso->materia->name }}
                                        </span>
                                        <span class="badge bg-light text-muted border fw-normal me-2">{{ $curso->periodo->name }}</span>
                                    </div>
                                </button>
                            </h2>
                            
                            <div id="collapse-{{ $hijo->id }}-{{ $curso->id }}" class="accordion-collapse collapse" data-bs-parent="#accordion-{{ $hijo->id }}">
                                <div class="accordion-body bg-light">
                                    <div class="row g-4">
                                        
                                        <div class="col-lg-8">
                                            <div class="card border-0 shadow-sm h-100">
                                                <div class="card-header bg-white fw-bold text-dark border-bottom">
                                                    <i class="bi bi-list-ol text-primary"></i> Calificaciones
                                                </div>
                                                <div class="card-body p-0">
                                                    <div class="table-responsive">
                                                        <table class="table table-hover mb-0 align-middle">
                                                            <thead class="table-light small text-uppercase text-muted">
                                                                <tr>
                                                                    <th class="ps-3">Actividad</th>
                                                                    <th class="text-center">Nota</th>
                                                                    <th class="text-center">Vale</th>
                                                                    <th class="text-center text-primary">Puntos</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @php
                                                                    $notasDelCurso = $notasPorHijo[$hijo->id][$curso->id] ?? collect();
                                                                    $notaAcumulada = 0;
                                                                @endphp

                                                                @forelse($notasDelCurso as $nota)
                                                                    @php 
                                                                        // CORRECCIÓN MATEMÁTICA: Nota * (Porcentaje / 100)
                                                                        $puntosGanados = ($nota->score * ($nota->percentage / 100));
                                                                        $notaAcumulada += $puntosGanados;
                                                                    @endphp
                                                                    <tr>
                                                                        <td class="ps-3">
                                                                            <span class="fw-bold text-secondary">{{ $nota->activity_name }}</span>
                                                                            <br><small class="text-muted">{{ $nota->tipoActividad->name }}</small>
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <span class="badge {{ $nota->score < 6 ? 'bg-danger' : 'bg-success' }}">
                                                                                {{ $nota->score }}
                                                                            </span>
                                                                        </td>
                                                                        <td class="text-center text-muted small">{{ $nota->percentage }}%</td>
                                                                        <td class="text-center fw-bold text-primary">
                                                                            +{{ number_format($puntosGanados, 2) }}
                                                                        </td>
                                                                    </tr>
                                                                @empty
                                                                    <tr>
                                                                        <td colspan="4" class="text-center py-4 text-muted small">
                                                                            Sin notas registradas.
                                                                        </td>
                                                                    </tr>
                                                                @endforelse
                                                            </tbody>
                                                            @if($notasDelCurso->isNotEmpty())
                                                                <tfoot class="bg-white border-top">
                                                                    <tr>
                                                                        <td colspan="3" class="text-end fw-bold pe-3 text-uppercase small text-muted">Nota Acumulada:</td>
                                                                        <td class="text-center fw-bold fs-5 {{ $notaAcumulada < 6 ? 'text-danger' : 'text-success' }}">
                                                                            {{ number_format($notaAcumulada, 2) }}
                                                                        </td>
                                                                    </tr>
                                                                </tfoot>
                                                            @endif
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="card border-0 shadow-sm h-100">
                                                <div class="card-header bg-white fw-bold text-dark border-bottom">
                                                    <i class="bi bi-calendar-check text-success"></i> Asistencia
                                                </div>
                                                <div class="card-body">
                                                    @php
                                                        $misAsistencias = $curso->asistencias->where('alumno_id', $hijo->id);
                                                        
                                                        $total = $misAsistencias->count();
                                                        $presentes = $misAsistencias->where('estado', 'presente')->count();
                                                        $tardanzas = $misAsistencias->where('estado', 'tardanza')->count();
                                                        $justificadas = $misAsistencias->where('estado', 'justificado')->count();
                                                        $ausentes = $misAsistencias->where('estado', 'ausente')->count();

                                                        $positivas = $presentes + $tardanzas + $justificadas;
                                                        $porcentaje = $total > 0 ? round(($positivas / $total) * 100) : 100;
                                                    @endphp

                                                    <div class="text-center py-3">
                                                        <div class="progress mb-2" style="height: 25px; border-radius: 15px;">
                                                            <div class="progress-bar {{ $porcentaje < 75 ? 'bg-danger' : 'bg-success' }} progress-bar-striped" 
                                                                 role="progressbar" 
                                                                 style="width: {{ $porcentaje }}%">
                                                                {{ $porcentaje }}%
                                                            </div>
                                                        </div>
                                                        <small class="text-muted">Global</small>
                                                    </div>

                                                    <ul class="list-group list-group-flush small mt-2">
                                                        <li class="list-group-item d-flex justify-content-between px-0">
                                                            <span><i class="bi bi-check-lg text-success"></i> Asistencias</span>
                                                            <strong>{{ $presentes }}</strong>
                                                        </li>
                                                        <li class="list-group-item d-flex justify-content-between px-0">
                                                            <span><i class="bi bi-file-earmark-medical text-info"></i> Justificadas</span>
                                                            <strong>{{ $justificadas }}</strong>
                                                        </li>
                                                        <li class="list-group-item d-flex justify-content-between px-0">
                                                            <span><i class="bi bi-x-lg text-danger"></i> Faltas</span>
                                                            <strong>{{ $ausentes }}</strong>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        @endforeach
    </div>

@endif

<style>
    .avatar {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background-color: #f8f9fa;
        color: #6c757d;
        border: 3px solid #e9ecef;
        transition: all 0.3s;
    }
    
    .btn-profile:hover .avatar {
        transform: translateY(-5px);
        background-color: #fff;
        border-color: #dee2e6;
    }

    .btn-profile.active .avatar {
        background-color: #0d6efd;
        color: white;
        border-color: #0a58ca;
        box-shadow: 0 5px 15px rgba(13, 110, 253, 0.4);
    }

    .btn-profile.active .name-text { color: #0d6efd !important; }

    .accordion-button:not(.collapsed) {
        background-color: #f0f7ff;
        color: #0d6efd;
    }
</style>
@endsection