@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Matricular Nuevo Estudiante</h1>
    <a href="{{ route('alumnos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
</div>

<form action="{{ route('alumnos.store') }}" method="POST">
    @csrf

    <div class="row">
        <div class="col-md-8">
            
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-light fw-bold">1. Información Personal</div>
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
                            <label class="form-label">Fecha de Nacimiento <span class="text-danger">*</span></label>
                            <input type="date" name="birth_date" class="form-control" required value="{{ old('birth_date') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Género <span class="text-danger">*</span></label>
                            <select name="gender" class="form-select" required>
                                <option value="">Seleccione...</option>
                                <option value="M" {{ old('gender') == 'M' ? 'selected' : '' }}>Masculino</option>
                                <option value="F" {{ old('gender') == 'F' ? 'selected' : '' }}>Femenino</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Dirección de Residencia</label>
                            <input type="text" name="address" class="form-control" placeholder="Colonia, Calle, #Casa..." value="{{ old('address') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Teléfono de Contacto</label>
                            <input type="text" name="phone" class="form-control" placeholder="####-####" value="{{ old('phone') }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-light fw-bold text-danger">2. Emergencia y Salud</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Contacto de Emergencia (Nombre)</label>
                            <input type="text" name="emergency_contact_name" class="form-control" value="{{ old('emergency_contact_name') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Teléfono de Emergencia</label>
                            <input type="text" name="emergency_contact_phone" class="form-control" value="{{ old('emergency_contact_phone') }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Condiciones Médicas / Alergias</label>
                            <textarea name="medical_conditions" class="form-control" rows="2" placeholder="Ej. Asma, Alergia a la penicilina... (Dejar en blanco si no aplica)">{{ old('medical_conditions') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-md-4">
            
            <div class="card mb-4 shadow-sm border-primary">
                <div class="card-header bg-primary text-white fw-bold">3. Datos Académicos</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Carnet / NIE <span class="text-danger">*</span></label>
                        <input type="text" name="student_id_code" class="form-control fw-bold" required value="{{ old('student_id_code') }}" placeholder="2025-XXX">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Grado a Cursar <span class="text-danger">*</span></label>
                        <select name="grado_id" class="form-select" required>
                            <option value="">Seleccione...</option>
                            @foreach($grados as $grado)
                                <option value="{{ $grado->id }}">{{ $grado->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sección Asignada <span class="text-danger">*</span></label>
                        <select name="seccion_id" class="form-select" required>
                            <option value="">Seleccione...</option>
                            @foreach($secciones as $seccion)
                                <option value="{{ $seccion->id }}">{{ $seccion->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-light fw-bold">4. Cuenta de Sistema</div>
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

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-success btn-lg">💾 Finalizar Matrícula</button>
            </div>

        </div>
    </div>
</form>
@endsection