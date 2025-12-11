@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Registrar Nuevo Docente</h1>
    <a href="{{ route('docentes.index') }}" class="btn btn-outline-secondary">Cancelar</a>
</div>

<form action="{{ route('docentes.store') }}" method="POST">
    @csrf

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light fw-bold">
                    <i class="bi bi-person-badge"></i> Perfil Profesional
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nombres <span class="text-danger">*</span></label>
                            <input type="text" name="first_name" class="form-control" required value="{{ old('first_name') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Apellidos <span class="text-danger">*</span></label>
                            <input type="text" name="last_name" class="form-control" required value="{{ old('last_name') }}">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Especialidad <span class="text-danger">*</span></label>
                            <input type="text" name="specialty" class="form-control" placeholder="Ej. Matemáticas, Ciencias..." required value="{{ old('specialty') }}">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Teléfono (Opcional)</label>
                            <input type="text" name="phone" class="form-control" placeholder="####-####" value="{{ old('phone') }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm mb-4 border-success">
                <div class="card-header bg-success text-white fw-bold">
                    <i class="bi bi-key"></i> Cuenta de Acceso
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contraseña <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control" required>
                        <div class="form-text">Mínimo 8 caracteres.</div>
                    </div>
                </div>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-success btn-lg shadow">
                    <i class="bi bi-check-circle"></i> Guardar Docente
                </button>
            </div>
        </div>
    </div>
</form>
@endsection