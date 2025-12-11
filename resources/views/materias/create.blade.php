@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Crear Nueva Materia</h1>
    <a href="{{ route('materias.index') }}" class="btn btn-outline-secondary">Cancelar</a>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <form action="{{ route('materias.store') }}" method="POST">
            @csrf

            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white fw-bold">
                    <i class="bi bi-journal-plus"></i> Datos de la Asignatura
                </div>
                <div class="card-body p-4">
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">Nombre de la Materia <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control form-control-lg" placeholder="Ej. Matemáticas, Ciencias Naturales..." required value="{{ old('name') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Descripción (Opcional)</label>
                        <textarea name="description" class="form-control" rows="4" placeholder="Breve descripción del contenido de la materia...">{{ old('description') }}</textarea>
                    </div>

                </div>
                <div class="card-footer bg-light border-0 py-3 text-end">
                    <button type="submit" class="btn btn-primary btn-lg shadow-sm px-4">
                        <i class="bi bi-check-lg"></i> Guardar Materia
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection