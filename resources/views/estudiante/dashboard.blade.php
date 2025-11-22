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
                <table class="table table-sm table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Actividad</th>
                            <th>Tipo</th>
                            <th class="text-center">Nota</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Buscamos las notas especificas de este curso --}}
                        @php 
                            $misNotas = $calificaciones[$curso->id] ?? collect(); 
                            $promedio = 0;
                            $contador = 0;
                        @endphp

                        @forelse ($misNotas as $nota)
                            <tr>
                                <td>{{ $nota->activity_name }}</td>
                                <td>{{ $nota->tipoActividad->name }} ({{ $nota->tipoActividad->default_percentage }}%)</td>
                                <td class="text-center fw-bold {{ $nota->score < 6 ? 'text-danger' : 'text-success' }}">
                                    {{ $nota->score }}
                                </td>
                            </tr>
                            @php 
                                $promedio += $nota->score; 
                                $contador++; 
                            @endphp
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">Aún no hay notas registradas en esta materia.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if($contador > 0)
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="2" class="text-end"><strong>Promedio Simple (Referencia):</strong></td>
                            <td class="text-center"><strong>{{ number_format($promedio / $contador, 2) }}</strong></td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
@empty
    <div class="alert alert-info">
        No tienes cursos inscritos actualmente.
    </div>
@endforelse

@endsection