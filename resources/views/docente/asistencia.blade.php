@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Control de Asistencia</h1>
</div>

<div class="card mb-4">
    <div class="card-body d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0">{{ $curso->materia->name }}</h5>
            <small class="text-muted">{{ $curso->grado->name }} "{{ $curso->seccion->name }}"</small>
        </div>
        
        <form action="{{ route('docente.cursos.asistencia', $curso->id) }}" method="GET" class="d-flex align-items-center">
            <label for="fecha" class="me-2 fw-bold">Fecha:</label>
            <input type="date" id="fecha" name="fecha" class="form-control" 
                   value="{{ $fecha }}" onchange="this.form.submit()">
        </form>
    </div>
</div>

<form action="{{ route('docente.cursos.asistencia.store', $curso->id) }}" method="POST">
    @csrf
    <input type="hidden" name="fecha" value="{{ $fecha }}">

    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Alumno</th>
                    <th class="text-center">Presente</th>
                    <th class="text-center">Ausente</th>
                    <th class="text-center">Tardanza</th>
                    <th class="text-center">Justificado</th>
                    <th>Observación</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($curso->alumnos as $alumno)
                    @php
                        // Recuperamos el estado guardado (si existe) o marcamos 'presente' por defecto
                        $estado = $asistencias[$alumno->id]->estado ?? 'presente';
                        $observacion = $asistencias[$alumno->id]->observacion ?? '';
                    @endphp
                    <tr>
                        <td>
                            {{ $alumno->first_name }} {{ $alumno->last_name }} <br>
                            <small class="text-muted">{{ $alumno->student_id_code }}</small>
                        </td>
                        
                        <td class="text-center">
                            <input type="radio" class="form-check-input" name="asistencia[{{ $alumno->id }}]" value="presente" {{ $estado == 'presente' ? 'checked' : '' }}>
                        </td>
                        <td class="text-center">
                            <input type="radio" class="form-check-input bg-danger border-danger" name="asistencia[{{ $alumno->id }}]" value="ausente" {{ $estado == 'ausente' ? 'checked' : '' }}>
                        </td>
                        <td class="text-center">
                            <input type="radio" class="form-check-input bg-warning border-warning" name="asistencia[{{ $alumno->id }}]" value="tardanza" {{ $estado == 'tardanza' ? 'checked' : '' }}>
                        </td>
                        <td class="text-center">
                            <input type="radio" class="form-check-input bg-info border-info" name="asistencia[{{ $alumno->id }}]" value="justificado" {{ $estado == 'justificado' ? 'checked' : '' }}>
                        </td>
                        
                        <td>
                            <input type="text" class="form-control form-control-sm" name="observacion[{{ $alumno->id }}]" value="{{ $observacion }}" placeholder="Comentario opcional...">
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3">
        <button type="submit" class="btn btn-primary btn-lg">Guardar Asistencia del {{ $fecha }}</button>
    </div>
</form>

@endsection