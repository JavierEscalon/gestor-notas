@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
    <div>
        <h1 class="h2 text-primary fw-bold">Mis Calificaciones</h1>
        <p class="text-muted mb-0">
            Hola, <strong>{{ explode(' ', $alumno->first_name)[0] }}</strong>. Aquí está tu rendimiento académico.
        </p>
    </div>
    <div class="text-end d-none d-md-block">
        <span class="badge bg-light text-dark border fs-6 shadow-sm">
            <i class="bi bi-card-heading"></i> {{ $alumno->student_id_code }}
        </span>
        <span class="badge bg-primary fs-6 ms-2 shadow-sm">
            {{ $alumno->grado->name }} "{{ $alumno->seccion->name }}"
        </span>
    </div>
</div>

@if($cursos->isEmpty())
    <div class="text-center py-5">
        <i class="bi bi-journal-x display-1 text-muted opacity-25"></i>
        <h4 class="text-muted mt-3">No tienes cursos inscritos en este período.</h4>
    </div>
@else

    <div class="accordion shadow-sm" id="accordionNotas">
        @foreach($cursos as $index => $curso)
            <div class="accordion-item border-0 mb-3 rounded overflow-hidden shadow-sm">
                
                <h2 class="accordion-header" id="heading{{ $curso->id }}">
                    <button class="accordion-button {{ $index == 0 ? '' : 'collapsed' }} fw-bold py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $curso->id }}" aria-expanded="{{ $index == 0 ? 'true' : 'false' }}">
                        <div class="d-flex align-items-center w-100 pe-3">
                            <div class="me-auto">
                                <span class="text-primary fs-5"><i class="bi bi-journal-bookmark-fill me-2"></i>{{ $curso->materia->name }}</span>
                                <div class="small text-muted fw-normal mt-1">
                                    <i class="bi bi-person-video"></i> {{ $curso->docente->first_name }} {{ $curso->docente->last_name }}
                                </div>
                            </div>
                            <span class="badge bg-light text-secondary border fw-normal">{{ $curso->periodo->name }}</span>
                        </div>
                    </button>
                </h2>
                
                <div id="collapse{{ $curso->id }}" class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}" data-bs-parent="#accordionNotas">
                    <div class="accordion-body bg-light">
                        <div class="row g-4">
                            
                            <div class="col-lg-8">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-header bg-white fw-bold text-dark border-bottom">
                                        <i class="bi bi-list-check text-primary"></i> Detalle de Evaluaciones
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover mb-0 align-middle">
                                                <thead class="table-light small text-uppercase text-muted">
                                                    <tr>
                                                        <th class="ps-4">Actividad</th>
                                                        <th class="text-center">Tipo</th>
                                                        <th class="text-center">Ponderación</th>
                                                        <th class="text-center">Nota</th>
                                                        <th class="text-center">Ganado</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $misNotas = $calificaciones[$curso->id] ?? collect();
                                                        $acumuladoTotal = 0;
                                                    @endphp

                                                    @forelse($misNotas as $nota)
                                                        @php 
                                                            $puntosGanados = ($nota->score * ($nota->percentage / 100));
                                                            $acumuladoTotal += $puntosGanados;
                                                        @endphp
                                                        <tr>
                                                            <td class="ps-4 fw-bold text-secondary">{{ $nota->activity_name }}</td>
                                                            <td class="text-center"><span class="badge bg-light text-dark border">{{ $nota->tipoActividad->name }}</span></td>
                                                            <td class="text-center text-muted small">{{ $nota->percentage }}%</td>
                                                            <td class="text-center">
                                                                <span class="badge {{ $nota->score < 6 ? 'bg-danger' : 'bg-success' }} fs-6">
                                                                    {{ number_format($nota->score, 2) }}
                                                                </span>
                                                            </td>
                                                            <td class="text-center fw-bold text-primary">
                                                                +{{ number_format($puntosGanados, 2) }}
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="5" class="text-center py-5 text-muted small">
                                                                <i class="bi bi-pencil-square display-6 mb-2 d-block opacity-25"></i>
                                                                Aún no hay notas registradas.
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    
                                    <div class="card-footer bg-white p-3 border-top">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-muted fw-bold text-uppercase small">Nota Acumulada del Período</span>
                                            <div class="text-end">
                                                <span class="display-6 fw-bold {{ $acumuladoTotal < 6 ? 'text-danger' : 'text-primary' }}">
                                                    {{ number_format($acumuladoTotal, 2) }}
                                                </span>
                                                <span class="text-muted fs-5">/ 10</span>
                                            </div>
                                        </div>
                                        <div class="progress mt-2" style="height: 8px;">
                                            <div class="progress-bar {{ $acumuladoTotal < 6 ? 'bg-danger' : 'bg-primary' }}" 
                                                 role="progressbar" 
                                                 style="width: {{ $acumuladoTotal * 10 }}%">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-header bg-white fw-bold text-dark border-bottom">
                                        <i class="bi bi-calendar-check text-success"></i> Mi Asistencia
                                    </div>
                                    <div class="card-body">
                                        @php
                                            // Filtramos las asistencias de este curso
                                            $misAsistencias = $curso->asistencias;
                                            
                                            $total = $misAsistencias->count();
                                            $presentes = $misAsistencias->where('estado', 'presente')->count();
                                            $tardanzas = $misAsistencias->where('estado', 'tardanza')->count();
                                            $justificadas = $misAsistencias->where('estado', 'justificado')->count();
                                            $ausentes = $misAsistencias->where('estado', 'ausente')->count();

                                            $positivas = $presentes + $tardanzas + $justificadas;
                                            $porcentaje = $total > 0 ? round(($positivas / $total) * 100) : 100;
                                        @endphp

                                        <div class="text-center py-4">
                                            <div class="position-relative d-inline-block">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center border-4 border {{ $porcentaje < 75 ? 'border-danger text-danger' : 'border-success text-success' }}" 
                                                        style="width: 100px; height: 100px; border-width: 8px !important;">
                                                    <span class="h3 fw-bold mb-0">{{ $porcentaje }}%</span>
                                                </div>
                                            </div>
                                            <p class="text-muted small mt-2 mb-0">Asistencia Global</p>
                                        </div>

                                        <ul class="list-group list-group-flush small mt-2">
                                            <li class="list-group-item d-flex justify-content-between px-0">
                                                <span><i class="bi bi-check-lg text-success me-1"></i> Asistencias</span>
                                                <strong>{{ $presentes }}</strong>
                                            </li>
                                            
                                            <li class="list-group-item d-flex justify-content-between px-0">
                                                <span><i class="bi bi-file-earmark-medical text-info me-1"></i> Justificadas</span>
                                                <strong>{{ $justificadas }}</strong>
                                            </li>

                                            <li class="list-group-item d-flex justify-content-between px-0">
                                                <span><i class="bi bi-clock-history text-warning me-1"></i> Tardanzas</span>
                                                <strong>{{ $tardanzas }}</strong>
                                            </li>

                                            <li class="list-group-item d-flex justify-content-between px-0">
                                                <span><i class="bi bi-x-lg text-danger me-1"></i> Faltas</span>
                                                <strong>{{ $ausentes }}</strong>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                        </div> </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

<style>
    .accordion-button:not(.collapsed) {
        background-color: #f0f7ff;
        color: #0d6efd;
        box-shadow: inset 0 -1px 0 rgba(0,0,0,.125);
    }
    .accordion-button:focus {
        box-shadow: none;
        border-color: rgba(0,0,0,.125);
    }
</style>
@endsection