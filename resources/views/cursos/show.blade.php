@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Inscripción de Alumnos</h1>
</div>

<div class="card mb-4">
    <div class="card-header">
        Datos del Curso
    </div>
    <div class="card-body">
        <h5 class="card-title">{{ $curso->materia->name }}</h5>
        <p class="card-text">
            <strong>Docente:</strong> {{ $curso->docente->first_name }} {{ $curso->docente->last_name }} <br>
            <strong>Grado:</strong> {{ $curso->grado->name }} | <strong>Sección:</strong> {{ $curso->seccion->name }} <br>
            <strong>Período:</strong> {{ $curso->periodo->name }}
        </p>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success mt-3" role="alert">
        {{ session('success') }}
    </div>
@endif

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                Alumnos Inscritos ({{ $alumnosInscritos->count() }})
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Carnet</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($alumnosInscritos as $alumno)
                            <tr>
                                <td>{{ $alumno->first_name }} {{ $alumno->last_name }}</td>
                                <td>{{ $alumno->student_id_code }}</td>
                                <td>
                                    <form action="{{ route('cursos.quitar', ['curso' => $curso->id, 'alumno' => $alumno->id]) }}" method="post" onsubmit="return confirm('¿Quitar a este alumno del curso?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Quitar</button>
                                    </form>
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
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                Alumnos Disponibles para Inscribir ({{ $alumnosDisponibles->count() }})
            </div>
            <div class="card-body">
                <form action="{{ route('cursos.inscribir', $curso->id) }}" method="post">
                    @csrf
                    <div class="mb-3" style="max-height: 400px; overflow-y: auto;">
                        @forelse ($alumnosDisponibles as $alumno)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="alumnos_ids[]" value="{{ $alumno->id }}" id="alumno-{{ $alumno->id }}">
                                <label class="form-check-label" for="alumno-{{ $alumno->id }}">
                                    {{ $alumno->first_name }} {{ $alumno->last_name }} ({{ $alumno->student_id_code }})
                                </label>
                            </div>
                        @empty
                            <p>No hay alumnos disponibles para este grado y sección.</p>
                        @endforelse
                    </div>

                    <hr>
                    <button type="submit" class="btn btn-primary">Inscribir Seleccionados</button>
                </form>
            </div>
        </div>
    </div>
</div> @endsection