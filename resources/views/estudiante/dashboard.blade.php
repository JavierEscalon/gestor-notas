@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Mis Calificaciones</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <span class="text-muted">
            {{ $alumno->first_name }} {{ $alumno->last_name }} ({{ $alumno->student_id_code }}) | 
            {{ $alumno->grado->name }} "{{ $alumno->seccion->name }}"
        </span>
    </div>
</div>

@forelse ($cursos as $curso)
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-primary">{{ $curso->materia->name }}</h5>
            <small class="text-muted">{{ $curso->periodo->name }}</small>
        </div>
        <div class="card-body">
            <p class="card-text mb-2">
                <strong>Docente:</strong> {{ $curso->docente->first_name }} {{ $curso->docente->last_name }}
            </p>

            <div class="table-responsive">
                <div class="table-responsive">
                <table class="table table-sm table-bordered table-hover">
                    <thead class="table-light">
                        <tr class="text-center align-middle">
                            <th class="text-start">Actividad</th>
                            <th>Calificación</th>
                            <th>Porcentaje</th>
                            <th>Puntos Ganados</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Buscamos las notas especificas de este curso --}}
                        @php 
                            $misNotas = $calificaciones[$curso->id] ?? collect(); 
                            $promedioAcumulado = 0;
                        @endphp

                        @forelse ($misNotas as $nota)
                            @php
                                $porcentaje = $nota->percentage;
                                // Calculamos los puntos ganados
                                $puntos = ($nota->score * $porcentaje) / 100;
                                $promedioAcumulado += $puntos;
                            @endphp
                            <tr class="text-center align-middle">
                                <td class="text-start">
                                    {{ $nota->activity_name }} <br>
                                    <small class="text-muted">{{ $nota->tipoActividad->name }}</small>
                                </td>
                                <td class="fw-bold {{ $nota->score < 6 ? 'text-danger' : 'text-dark' }}">
                                    {{ number_format($nota->score, 2) }}
                                </td>
                                <td>{{ number_format($porcentaje, 0) }}%</td>
                                <td class="bg-light fw-bold text-primary">
                                    {{ number_format($puntos, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">
                                    Aún no hay notas registradas en esta materia.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    
                    <tfoot class="table-light border-top-2">
                        <tr>
                            <td colspan="3" class="text-end align-middle">
                                <h6 class="mb-0">Nota Final Acumulada:</h6>
                            </td>
                            <td class="text-center align-middle bg-primary text-white">
                                <h5 class="mb-0">{{ number_format($promedioAcumulado, 2) }}</h5>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            </div>
        </div>
    </div>
@empty
    <div class="alert alert-info">
        No tienes cursos inscritos actualmente.
    </div>
@endforelse

@endsection