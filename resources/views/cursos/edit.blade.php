@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Editar Curso</h1>
    <a href="{{ route('cursos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
</div>

<form action="{{ route('cursos.update', $curso->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-warning text-dark fw-bold">
                    <i class="bi bi-pencil"></i> Datos Académicos
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Materia</label>
                        <select name="materia_id" class="form-select form-select-lg" required>
                            @foreach($materias as $materia)
                                <option value="{{ $materia->id }}" {{ $curso->materia_id == $materia->id ? 'selected' : '' }}>
                                    {{ $materia->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Docente Encargado</label>
                        <select name="docente_id" class="form-select" required>
                            @foreach($docentes as $docente)
                                <option value="{{ $docente->id }}" {{ $curso->docente_id == $docente->id ? 'selected' : '' }}>
                                    {{ $docente->first_name }} {{ $docente->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-secondary text-white fw-bold">
                    <i class="bi bi-geo-alt"></i> Logística
                </div>
                <div class="card-body">
                    <div class="row g-2 mb-3">
                        <div class="col-md-8">
                            <label class="form-label fw-bold">Grado</label>
                            <select name="grado_id" class="form-select" required>
                                @foreach($grados as $grado)
                                    <option value="{{ $grado->id }}" {{ $curso->grado_id == $grado->id ? 'selected' : '' }}>
                                        {{ $grado->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Sección</label>
                            <select name="seccion_id" class="form-select" required>
                                @foreach($secciones as $seccion)
                                    <option value="{{ $seccion->id }}" {{ $curso->seccion_id == $seccion->id ? 'selected' : '' }}>
                                        {{ $seccion->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Período Escolar</label>
                        <select name="periodo_id" class="form-select" required>
                            @foreach($periodos as $periodo)
                                <option value="{{ $periodo->id }}" {{ $curso->periodo_id == $periodo->id ? 'selected' : '' }}>
                                    {{ $periodo->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-check form-switch mt-3">
                        <input type="hidden" name="is_calificaciones_closed" value="0">
                        <input class="form-check-input" type="checkbox" id="is_closed" name="is_calificaciones_closed" value="1" {{ $curso->is_calificaciones_closed ? 'checked' : '' }}>
                        <label class="form-check-label text-danger fw-bold" for="is_closed">
                            <i class="bi bi-lock-fill"></i> Cerrar Calificaciones
                        </label>
                    </div>
                </div>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-warning btn-lg shadow-sm">
                    <i class="bi bi-save"></i> Actualizar Curso
                </button>
            </div>
        </div>
    </div>
</form>
@endsection