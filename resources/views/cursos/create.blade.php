@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Aperturar Nuevo Curso</h1>
    <a href="{{ route('cursos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
</div>

<form action="{{ route('cursos.store') }}" method="POST">
    @csrf

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white fw-bold">
                    <i class="bi bi-book"></i> Datos Académicos
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Materia <span class="text-danger">*</span></label>
                        <select name="materia_id" class="form-select form-select-lg" required>
                            <option value="">Seleccione Materia...</option>
                            @foreach($materias as $materia)
                                <option value="{{ $materia->id }}" {{ old('materia_id') == $materia->id ? 'selected' : '' }}>
                                    {{ $materia->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Docente Encargado <span class="text-danger">*</span></label>
                        <select name="docente_id" class="form-select" required>
                            <option value="">Seleccione Docente...</option>
                            @foreach($docentes as $docente)
                                <option value="{{ $docente->id }}" {{ old('docente_id') == $docente->id ? 'selected' : '' }}>
                                    {{ $docente->first_name }} {{ $docente->last_name }} ({{ $docente->specialty }})
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
                            <label class="form-label fw-bold">Grado <span class="text-danger">*</span></label>
                            <select name="grado_id" class="form-select" required>
                                <option value="">Seleccione...</option>
                                @foreach($grados as $grado)
                                    <option value="{{ $grado->id }}" {{ old('grado_id') == $grado->id ? 'selected' : '' }}>
                                        {{ $grado->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Sección <span class="text-danger">*</span></label>
                            <select name="seccion_id" class="form-select" required>
                                <option value="">...</option>
                                @foreach($secciones as $seccion)
                                    <option value="{{ $seccion->id }}" {{ old('seccion_id') == $seccion->id ? 'selected' : '' }}>
                                        {{ $seccion->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Período Escolar <span class="text-danger">*</span></label>
                        <select name="periodo_id" class="form-select" required>
                            <option value="">Seleccione...</option>
                            @foreach($periodos as $periodo)
                                <option value="{{ $periodo->id }}" {{ old('periodo_id') == $periodo->id ? 'selected' : '' }} class="{{ $periodo->is_active ? 'fw-bold text-success' : '' }}">
                                    {{ $periodo->name }} {{ $periodo->is_active ? '(Vigente)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                    <i class="bi bi-check-lg"></i> Crear Curso
                </button>
            </div>
        </div>
    </div>
</form>
@endsection