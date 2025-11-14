@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Gestionar Calificaciones del Curso</h1>
</div>

<div class="card mb-4">
    <div class="card-header">
        Datos del Curso
    </div>
    <div class="card-body">
        <h5 class="card-title">{{ $curso->materia->name }}</h5>
        <p class="card-text">
            <strong>Grado:</strong> {{ $curso->grado->name }} | <strong>Sección:</strong> {{ $curso->seccion->name }} <br>
            <strong>Período:</strong> {{ $curso->periodo->name }}
        </p>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        Actividades Ya Registradas
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Nombre de la Actividad</th>
                        <th>Tipo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($actividadesRegistradas as $actividad)
                        <tr>
                            <td>{{ $actividad->activity_name }}</td>
                            <td>{{ $actividad->tipoActividad->name }}</td>
                            <td>
                                <a href="{{ route('docente.calificaciones.edit', ['curso' => $curso->id, 'activity_name' => $actividad->activity_name]) }}" class="btn btn-sm btn-warning me-1">Editar</a>
                                <form action="{{ route('docente.calificaciones.destroy', ['curso' => $curso->id, 'activity_name' => $actividad->activity_name]) }}" method="post" class="d-inline" onsubmit="return confirm('¿Eliminar esta actividad y todas sus notas?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                            </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">No hay actividades registradas para este curso.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="card mb-4">
    <div class="card-header">
        Registrar Nueva Actividad y sus Calificaciones
    </div>
    <div class="card-body">
        <form action="{{ route('docente.cursos.calificaciones.store', $curso->id) }}" method="POST">
            @csrf

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label for="activity_name" class="form-label">Nombre de la Actividad (Ej. Examen 1)</label>
                    <input type="text" class="form-control" id="activity_name" name="activity_name" required>
                </div>
                <div class="col-md-6">
                    <label for="tipo_actividad_id" class="form-label">Tipo de Actividad</label>
                    <select class="form-select" id="tipo_actividad_id" name="tipo_actividad_id" required>
                        <option value="">Seleccionar...</option>
                        @foreach ($tiposActividad as $tipo)
                            <option value="{{ $tipo->id }}">{{ $tipo->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <hr>

            <h5 class="mb-3">Ingresar Notas para los Alumnos</h5>
            <div class="table-responsive">
                <table class="table table-striped table-sm">
                    <thead>
                        <tr>
                            <th scope="col" style="width: 50%;">Nombre del Alumno</th>
                            <th scope="col" style="width: 20%;">Carnet</th>
                            <th scope="col" style="width: 30%;">Calificación (Nota)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($curso->alumnos as $alumno)
                            <tr>
                                <td>{{ $alumno->first_name }} {{ $alumno->last_name }}</td>
                                <td>{{ $alumno->student_id_code }}</td>
                                <td>
                                    <input type="number" class="form-control" 
                                           name="scores[{{ $alumno->id }}]" 
                                           min="0" max="10" step="0.1" required>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">No hay alumnos inscritos en este curso.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($curso->alumnos->count() > 0)
                <hr class="my-4">
                <button class="w-100 btn btn-primary btn-lg" type="submit">Guardar Nueva Actividad y Calificaciones</button>
            @endif
        </form>
    </div>
</div>

@endsection