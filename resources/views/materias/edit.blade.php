@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Editar Materia: {{ $materia->name }}</h1>
    <a href="{{ route('materias.index') }}" class="btn btn-outline-secondary">Cancelar</a>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <form action="{{ route('materias.update', $materia->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="card shadow-sm border-0">
                <div class="card-header bg-warning text-dark fw-bold">
                    <i class="bi bi-pencil-square"></i> Editar Asignatura
                </div>
                <div class="card-body p-4">
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">Nombre de la Materia <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control form-control-lg" required value="{{ old('name', $materia->name) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Descripción (Opcional)</label>
                        <textarea name="description" class="form-control" rows="4">{{ old('description', $materia->description) }}</textarea>
                    </div>

                </div>
                <div class="card-footer bg-light border-0 py-3 text-end">
                    <button type="submit" class="btn btn-warning btn-lg shadow-sm px-4">
                        <i class="bi bi-save"></i> Actualizar Materia
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection