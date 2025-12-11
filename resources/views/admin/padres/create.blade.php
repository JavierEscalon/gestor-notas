@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Registrar Nuevo Padre de Familia</h1>
    <a href="{{ route('admin.padres.index') }}" class="btn btn-outline-secondary">Cancelar</a>
</div>

<form action="{{ route('admin.padres.store') }}" method="POST">
    @csrf

    <div class="row">
        <div class="col-md-7">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light fw-bold text-primary">
                    <i class="bi bi-person-vcard-fill"></i> Información Personal
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nombres <span class="text-danger">*</span></label>
                            <input type="text" name="first_name" class="form-control" required value="{{ old('first_name') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Apellidos <span class="text-danger">*</span></label>
                            <input type="text" name="last_name" class="form-control" required value="{{ old('last_name') }}">
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label fw-bold">Teléfono de Contacto</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bi bi-telephone"></i></span>
                                <input type="text" name="phone" class="form-control" placeholder="####-####" value="{{ old('phone') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card shadow-sm mb-4 border-success">
                <div class="card-header bg-success text-white fw-bold">
                    <i class="bi bi-lock-fill"></i> Cuenta de Acceso
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Correo Electrónico <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Contraseña <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control" required>
                        <div class="form-text">Mínimo 8 caracteres.</div>
                    </div>
                </div>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-success btn-lg shadow fw-bold">
                    <i class="bi bi-arrow-right-circle"></i> Guardar y Asignar Hijos
                </button>
            </div>
        </div>
    </div>
</form>
@endsection