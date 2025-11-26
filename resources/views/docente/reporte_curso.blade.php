@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Reporte de Notas del Período</h1>
    
    @if(!$curso->is_calificaciones_closed)
        <form action="{{ route('docente.cursos.cerrar', $curso->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro? Una vez cerrado no podrás modificar notas.');">
            @csrf
            <button type="submit" class="btn btn-danger">
                Cerrar Modificación de Notas
            </button>
        </form>
    @else
        <span class="badge bg-danger fs-6">Período Cerrado</span>
    @endif
</div>

<div class="card mb-4">
    <div class="card-header bg-light">
        <strong>Curso:</strong> {{ $curso->materia->name }} | 
        <strong>Grado:</strong> {{ $curso->grado->name }} "{{ $curso->seccion->name }}" |
        <strong>Período:</strong> {{ $curso->periodo->name }}
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Estudiante</th>
                        @foreach($actividades as $actividad)
                            <th class="text-center">{{ $actividad }}</th>
                        @endforeach
                        <th class="text-center bg-secondary">Nota Final</th>
                        <th class="text-center">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reporte as $fila)
                        <tr>
                            <td>
                                {{ $fila['alumno']->last_name }}, {{ $fila['alumno']->first_name }} <br>
                                <small class="text-muted">{{ $fila['alumno']->student_id_code }}</small>
                            </td>
                            
                            @foreach($actividades as $actividad)
                                <td class="text-center">
                                    {{ $fila['notas'][$actividad] ?? '-' }}
                                </td>
                            @endforeach

                            <td class="text-center fw-bold {{ $fila['promedio'] >= 5 ? 'text-success' : 'text-danger' }} bg-light">
                                {{ $fila['promedio'] }}
                            </td>

                            <td class="text-center">
                                @if($fila['promedio'] >= 5)
                                    <span class="badge bg-success">Aprobado</span>
                                @else
                                    <span class="badge bg-danger">Reprobado</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('docente.dashboard') }}" class="btn btn-secondary">Volver al Dashboard</a>
</div>

@endsection