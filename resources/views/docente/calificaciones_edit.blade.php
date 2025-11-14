@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Editar Calificaciones</h1>
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

<form action="{{ route('docente.calificaciones.update', ['curso' => $curso->id, 'activity_name' => $activity_name]) }}" method="POST">
    @csrf
    @method('PUT') <div class="row g-3 mb-3">
        <div class="col-md-6">
            <label for="activity_name" class="form-label">Nombre de la Actividad (No editable)</label>
            <input type="text" class="form-control" id="activity_name_disabled" value="{{ $activity_name }}" disabled>
            <input type="hidden" name="activity_name" value="{{ $activity_name }}">
        </div>
        <div class="col-md-6">
            <label for="tipo_actividad_id" class="form-label">Tipo de Actividad (No editable)</label>
            <input type="text" class="form-control" id="tipo_actividad_id_disabled" value="{{ $tipoActividad->name }}" disabled>
            <input type="hidden" name="tipo_actividad_id" value="{{ $tipoActividad->id }}">
        </div>
    </div>

    <hr>

    <h5 class="mb-3">Actualizar Notas de los Alumnos</h5>
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
                                   value="{{ $calificaciones[$alumno->id] ?? 0 }}"
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
        <button class="w-100 btn btn-warning btn-lg" type="submit">Actualizar Calificaciones</button>
    @endif
</form>

@endsection