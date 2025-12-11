@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Editar Expediente: {{ $alumno->first_name }} {{ $alumno->last_name }}</h1>
    <a href="{{ route('alumnos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
</div>

<form action="{{ route('alumnos.update', $alumno->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-md-8">
            
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-light fw-bold">1. Información Personal</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nombres <span class="text-danger">*</span></label>
                            <input type="text" name="first_name" class="form-control" required value="{{ old('first_name', $alumno->first_name) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Apellidos <span class="text-danger">*</span></label>
                            <input type="text" name="last_name" class="form-control" required value="{{ old('last_name', $alumno->last_name) }}">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Fecha de Nacimiento <span class="text-danger">*</span></label>
                            <input type="date" name="birth_date" class="form-control" required 
                                   value="{{ old('birth_date', optional($alumno->birth_date)->format('Y-m-d')) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Género <span class="text-danger">*</span></label>
                            <select name="gender" class="form-select" required>
                                <option value="M" {{ old('gender', $alumno->gender) == 'M' ? 'selected' : '' }}>Masculino</option>
                                <option value="F" {{ old('gender', $alumno->gender) == 'F' ? 'selected' : '' }}>Femenino</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Dirección de Residencia</label>
                            <input type="text" name="address" class="form-control" value="{{ old('address', $alumno->address) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Teléfono de Contacto</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $alumno->phone) }}">
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
                            <input type="text" name="emergency_contact_name" class="form-control" value="{{ old('emergency_contact_name', $alumno->emergency_contact_name) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Teléfono de Emergencia</label>
                            <input type="text" name="emergency_contact_phone" class="form-control" value="{{ old('emergency_contact_phone', $alumno->emergency_contact_phone) }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Condiciones Médicas / Alergias</label>
                            <textarea name="medical_conditions" class="form-control" rows="2">{{ old('medical_conditions', $alumno->medical_conditions) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-md-4">
            
            <div class="card mb-4 shadow-sm border-warning">
                <div class="card-header bg-warning text-dark fw-bold">3. Datos Académicos</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Carnet / NIE <span class="text-danger">*</span></label>
                        <input type="text" name="student_id_code" class="form-control fw-bold" required value="{{ old('student_id_code', $alumno->student_id_code) }}">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Estado de Matrícula</label>
                        <select name="status" class="form-select fw-bold {{ $alumno->status == 'activo' ? 'text-success' : 'text-danger' }}">
                            <option value="activo" {{ $alumno->status == 'activo' ? 'selected' : '' }}>✅ Activo</option>
                            <option value="inactivo" {{ $alumno->status == 'inactivo' ? 'selected' : '' }}>⏸ Inactivo (Temporal)</option>
                            <option value="retirado" {{ $alumno->status == 'retirado' ? 'selected' : '' }}>🚫 Retirado (Definitivo)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Grado Actual</label>
                        <select name="grado_id" class="form-select" required>
                            @foreach($grados as $grado)
                                <option value="{{ $grado->id }}" {{ $alumno->grado_id == $grado->id ? 'selected' : '' }}>
                                    {{ $grado->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sección</label>
                        <select name="seccion_id" class="form-select" required>
                            @foreach($secciones as $seccion)
                                <option value="{{ $seccion->id }}" {{ $alumno->seccion_id == $seccion->id ? 'selected' : '' }}>
                                    {{ $seccion->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-light fw-bold">4. Cuenta de Sistema</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Correo Electrónico</label>
                        <input type="email" name="email" class="form-control" required value="{{ old('email', $alumno->user?->email) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nueva Contraseña (Opcional)</label>
                        <input type="password" name="password" class="form-control" placeholder="Dejar en blanco para mantener actual">
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-warning btn-lg shadow-sm">Actualizar Expediente</button>
            </div>

        </div>
    </div>
</form>
@endsection