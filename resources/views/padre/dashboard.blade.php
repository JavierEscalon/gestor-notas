@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Seguimiento Académico de mis Hijos</h1>
</div>

<ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
    @foreach($hijos as $index => $hijo)
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $index == 0 ? 'active' : '' }}" 
                    id="tab-hijo-{{ $hijo->id }}" 
                    data-bs-toggle="tab" 
                    data-bs-target="#content-hijo-{{ $hijo->id }}" 
                    type="button" role="tab">
                {{ $hijo->first_name }}
            </button>
        </li>
    @endforeach
</ul>

<div class="tab-content" id="myTabContent">
    @foreach($hijos as $index => $hijo)
        <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}" 
             id="content-hijo-{{ $hijo->id }}" role="tabpanel">
            
            <div class="alert alert-secondary d-flex justify-content-between align-items-center">
                <span>
                    <strong>Estudiante:</strong> {{ $hijo->first_name }} {{ $hijo->last_name }} <br>
                    <strong>Carnet:</strong> {{ $hijo->student_id_code }} | <strong>Grado:</strong> {{ $hijo->grado->name }} "{{ $hijo->seccion->name }}"
                </span>
            </div>

            @foreach ($hijo->cursos as $curso)
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-light text-primary fw-bold">
                        {{ $curso->materia->name }}
                        <span class="text-muted fw-normal float-end" style="font-size: 0.85rem;">{{ $curso->periodo->name }}</span>
                    </div>
                    <div class="card-body">
                        
                        <div class="row">
                            <div class="col-md-8 border-end">
                                <h6 class="text-muted mb-3">Boleta de Calificaciones</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Actividad</th>
                                                <th class="text-center">Nota</th>
                                                <th class="text-center">Ponderación</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php 
                                                $misNotas = $notasPorHijo[$hijo->id][$curso->id] ?? collect();
                                            @endphp
                                            @forelse ($misNotas as $nota)
                                                <tr>
                                                    <td>{{ $nota->activity_name }}</td>
                                                    <td class="text-center fw-bold {{ $nota->score < 6 ? 'text-danger' : 'text-success' }}">
                                                        {{ $nota->score }}
                                                    </td>
                                                    <td class="text-center text-muted small">{{ $nota->percentage }}%
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="3" class="text-center text-muted">Sin notas registradas.</td></tr>
                                            @endforelse
                                        </tbody>
                                        <tfoot class="table-light">
                                            <tr>
                                                <td colspan="2" class="text-end"><strong>Nota Acumulada:</strong></td>
                                                <td class="text-center bg-primary text-white fw-bold">
                                                    {{ $curso->calcularPromedio($hijo->id) }}
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <h6 class="text-muted mb-3">Control de Asistencia</h6>
                                @php
                                    // Calculamos la asistencia
                                    $asistencias = $curso->asistencias()->where('alumno_id', $hijo->id)->get();
                                    
                                    $presente = $asistencias->where('estado', 'presente')->count();
                                    $ausente = $asistencias->where('estado', 'ausente')->count();
                                    $tardanza = $asistencias->where('estado', 'tardanza')->count();
                                    $justificado = $asistencias->where('estado', 'justificado')->count();
                                    
                                    $totalClases = $asistencias->count();
                                    
                                    // logica de Porcentaje
                                    // consideramos Presente, Tardanza y Justificado como "no ausencias" para el %
                                    // (solo 'ausente' baja el porcentaje)
                                    $asistenciasPositivas = $presente + $tardanza + $justificado;
                                    $porcentajeAsistencia = $totalClases > 0 ? round(($asistenciasPositivas / $totalClases) * 100) : 100;
                                @endphp

                                <ul class="list-group list-group-flush small">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Clases Totales
                                        <span class="badge bg-secondary rounded-pill">{{ $totalClases }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center text-success">
                                        Asistencias (Presente)
                                        <span class="fw-bold">{{ $presente }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center text-danger">
                                        Faltas Injustificadas
                                        <span class="fw-bold">{{ $ausente }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center text-warning">
                                        Tardanzas
                                        <span class="fw-bold">{{ $tardanza }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center text-info">
                                        Justificadas
                                        <span class="fw-bold">{{ $justificado }}</span>
                                    </li>
                                </ul>
                                
                                <div class="mt-3 text-center">
                                    <small class="text-muted">Porcentaje de Asistencia</small>
                                    <div class="progress mt-1" style="height: 20px;">
                                        <div class="progress-bar {{ $porcentajeAsistencia < 75 ? 'bg-danger' : 'bg-success' }}" 
                                             role="progressbar" 
                                             style="width: {{ $porcentajeAsistencia }}%;" 
                                             aria-valuenow="{{ $porcentajeAsistencia }}" aria-valuemin="0" aria-valuemax="100">
                                            {{ $porcentajeAsistencia }}%
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> </div>
                </div>
            @endforeach

        </div>
    @endforeach
</div>

@endsection