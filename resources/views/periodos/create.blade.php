@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Crear Nuevo Período Escolar</h1>
    <a href="{{ route('periodos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <form action="{{ route('periodos.store') }}" method="POST">
            @csrf

            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white fw-bold">
                    <i class="bi bi-calendar-plus"></i> Datos del Período
                </div>
                <div class="card-body p-4">
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">Nombre del Período <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control form-control-lg" placeholder="Ej. Trimestre 1 - 2025" required value="{{ old('name') }}">
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Fecha de Inicio <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" class="form-control" required value="{{ old('start_date') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Fecha de Fin <span class="text-danger">*</span></label>
                            <input type="date" name="end_date" class="form-control" required value="{{ old('end_date') }}">
                        </div>
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="is_active">¿Establecer como Período Vigente?</label>
                        <div class="form-text text-muted">Si activas esto, cualquier otro período activo se cerrará automáticamente.</div>
                    </div>

                </div>
                <div class="card-footer bg-light border-0 py-3 text-end">
                    <button type="submit" class="btn btn-primary btn-lg shadow-sm px-4">
                        <i class="bi bi-save"></i> Guardar Período
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection