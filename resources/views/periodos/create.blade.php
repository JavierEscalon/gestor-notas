@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Crear Nuevo Período</h1>
</div>

<div class="row">
    <div class="col-lg-8">
        <form action="{{ route('periodos.store') }}" method="post">
            @csrf <div class="mb-3">
                <label for="name" class="form-label">Nombre del Período</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Ej. Trimestre 1 - 2025" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="start_date" class="form-label">Fecha de Inicio</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="end_date" class="form-label">Fecha de Fin</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" required>
                </div>
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1">
                <label class="form-check-label" for="is_active">
                    ¿Marcar como período activo?
                </label>
                <small class="d-block text-muted">El período activo es el que se usa por defecto para registrar notas.</small>
            </div>
            
            <hr class="my-4">

            <button class="w-100 btn btn-primary btn-lg" type="submit">Guardar Período</button>
        </form>
    </div>
</div>

@endsection