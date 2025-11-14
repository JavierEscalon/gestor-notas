@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Editar Alumno: {{ $alumno->first_name }} {{ $alumno->last_name }}</h1>
</div>

<div class="row">
    <div class="col-lg-8">
        
        <form action="{{ route('alumnos.update', $alumno->id) }}" method="post">
            @csrf @method('PUT') <h5 class="mb-3">Datos del Perfil</h5>
            
            <div class="row g-3">
                <div class="col-sm-6">
                    <label for="first_name" class="form-label">Nombres</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" 
                           value="{{ old('first_name', $alumno->first_name) }}" required>
                </div>

                <div class="col-sm-6">
                    <label for="last_name" class="form-label">Apellidos</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" 
                           value="{{ old('last_name', $alumno->last_name) }}" required>
                </div>

                <div class="col-12">
                    <label for="student_id_code" class="form-label">Carnet / Código de Estudiante</label>
                    <input type="text" class="form-control" id="student_id_code" name="student_id_code" 
                           value="{{ old('student_id_code', $alumno->student_id_code) }}" required>
                </div>

                <div class="col-sm-6">
                    <label for="grado_id" class="form-label">Grado</label>
                    <select class="form-select" id="grado_id" name="grado_id" required>
                        <option value="">Seleccionar...</option>
                        @foreach ($grados as $grado)
                            <option value="{{ $grado->id }}" 
                                    {{ old('grado_id', $alumno->grado_id) == $grado->id ? 'selected' : '' }}>
                                {{ $grado->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-sm-6">
                    <label for="seccion_id" class="form-label">Sección</label>
                    <select class="form-select" id="seccion_id" name="seccion_id" required>
                        <option value="">Seleccionar...</option>
                        @foreach ($secciones as $seccion)
                            <option value="{{ $seccion->id }}" 
                                    {{ old('seccion_id', $alumno->seccion_id) == $seccion->id ? 'selected' : '' }}>
                                {{ $seccion->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <hr class="my-4">

            <h5 class="mb-3">Datos de la Cuenta (Login)</h5>
            
            <div class="row g-3">
                <div class="col-12">
                    <label for="email" class="form-label">Email (Usuario)</label>
                    <input type="email" class="form-control" id="email" name="email" 
                           value="{{ old('email', $alumno->user->email) }}" required>
                </div>
                
                <div class="col-12">
                    <label for="password" class="form-label">Contraseña (Opcional)</label>
                    <input type="password" class="form-control" id="password" name="password">
                    <small class="text-muted">Dejar en blanco para no cambiar la contraseña.</small>
                </div>
            </div>
            
            <hr class="my-4">

            <button class="w-100 btn btn-warning btn-lg" type="submit">Actualizar Alumno</button>
        </form>
    </div>
</div>

@endsection