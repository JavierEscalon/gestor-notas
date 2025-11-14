@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Editar Curso</h1>
</div>

<div class="row">
    <div class="col-lg-8">
        <form action="{{ route('cursos.update', $curso->id) }}" method="post">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-12">
                    <label for="materia_id" class="form-label">Materia</label>
                    <select class="form-select" id="materia_id" name="materia_id" required>
                        <option value="">Seleccionar...</option>
                        @foreach ($materias as $materia)
                            <option value="{{ $materia->id }}" {{ old('materia_id', $curso->materia_id) == $materia->id ? 'selected' : '' }}>
                                {{ $materia->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12">
                    <label for="docente_id" class="form-label">Docente</label>
                    <select class="form-select" id="docente_id" name="docente_id" required>
                        <option value="">Seleccionar...</option>
                        @foreach ($docentes as $docente)
                            <option value="{{ $docente->id }}" {{ old('docente_id', $curso->docente_id) == $docente->id ? 'selected' : '' }}>
                                {{ $docente->first_name }} {{ $docente->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="grado_id" class="form-label">Grado</label>
                    <select class="form-select" id="grado_id" name="grado_id" required>
                        <option value="">Seleccionar...</option>
                        @foreach ($grados as $grado)
                            <option value="{{ $grado->id }}" {{ old('grado_id', $curso->grado_id) == $grado->id ? 'selected' : '' }}>
                                {{ $grado->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="seccion_id" class="form-label">Sección</label>
                    <select class="form-select" id="seccion_id" name="seccion_id" required>
                        <option value="">Seleccionar...</option>
                        @foreach ($secciones as $seccion)
                            <option value="{{ $seccion->id }}" {{ old('seccion_id', $curso->seccion_id) == $seccion->id ? 'selected' : '' }}>
                                {{ $seccion->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12">
                    <label for="periodo_id" class="form-label">Período Escolar</label>
                    <select class="form-select" id="periodo_id" name="periodo_id" required>
                        <option value="">Seleccionar...</option>
                        @foreach ($periodos as $periodo)
                            <option value="{{ $periodo->id }}" {{ old('periodo_id', $curso->periodo_id) == $periodo->id ? 'selected' : '' }}>
                                {{ $periodo->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <hr class="my-4">

            <button class="w-100 btn btn-warning btn-lg" type="submit">Actualizar Curso</button>
        </form>
    </div>
</div>

@endsection