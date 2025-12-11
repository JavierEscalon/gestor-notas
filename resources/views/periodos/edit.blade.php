@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Editar Período: {{ $periodo->name }}</h1>
    <a href="{{ route('periodos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <form action="{{ route('periodos.update', $periodo->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="card shadow-sm border-0">
                <div class="card-header bg-warning text-dark fw-bold">
                    <i class="bi bi-pencil-square"></i> Modificar Datos
                </div>
                <div class="card-body p-4">
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">Nombre del Período <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control form-control-lg" required value="{{ old('name', $periodo->name) }}">
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Fecha de Inicio <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" class="form-control" required 
                                    value="{{ old('start_date', \Carbon\Carbon::parse($periodo->start_date)->format('Y-m-d')) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Fecha de Fin <span class="text-danger">*</span></label>
                            <input type="date" name="end_date" class="form-control" required 
                                    value="{{ old('end_date', \Carbon\Carbon::parse($periodo->end_date)->format('Y-m-d')) }}">
                        </div>
                    </div>

                    <div class="alert alert-info d-flex align-items-center" role="alert">
                        <i class="bi bi-info-circle-fill me-2 fs-4"></i>
                        <div>
                            <strong>Nota:</strong> Si deseas cambiar cuál es el período activo, hazlo desde la lista de períodos creando uno nuevo o editando el estado directamente.
                        </div>
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $periodo->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="is_active">Período Vigente</label>
                    </div>

                </div>
                <div class="card-footer bg-light border-0 py-3 text-end">
                    <button type="submit" class="btn btn-warning btn-lg shadow-sm px-4">
                        <i class="bi bi-check-lg"></i> Actualizar Período
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection