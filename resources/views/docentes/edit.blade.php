@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Editar Docente: {{ $docente->first_name }} {{ $docente->last_name }}</h1>
</div>

<div class="row">
    <div class="col-lg-8">
        <form action="{{ route('docentes.update', $docente->id) }}" method="post">
            @csrf
            @method('PUT')

            <h5 class="mb-3">Datos del Perfil</h5>

            <div class="row g-3">
                <div class="col-sm-6">
                    <label for="first_name" class="form-label">Nombres</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" 
                           value="{{ old('first_name', $docente->first_name) }}" required>
                </div>

                <div class="col-sm-6">
                    <label for="last_name" class="form-label">Apellidos</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" 
                           value="{{ old('last_name', $docente->last_name) }}" required>
                </div>

                <div class="col-12">
                    <label for="specialty" class="form-label">Especialidad</label>
                    <input type="text" class="form-control" id="specialty" name="specialty" 
                           value="{{ old('specialty', $docente->specialty) }}">
                </div>

                <div class="col-12">
                    <label for="phone" class="form-label">Teléfono (Opcional)</label>
                    <input type="text" class="form-control" id="phone" name="phone" 
                           value="{{ old('phone', $docente->phone) }}">
                </div>
            </div>

            <hr class="my-4">

            <h5 class="mb-3">Datos de la Cuenta (Login)</h5>

            <div class="row g-3">
                <div class="col-12">
                    <label for="email" class="form-label">Email (Usuario)</label>
                    <input type="email" class="form-control" id="email" name="email" 
                           value="{{ old('email', $docente->user->email) }}" required>
                </div>

                <div class="col-12">
                    <label for="password" class="form-label">Contraseña (Opcional)</label>
                    <input type="password" class="form-control" id="password" name="password">
                    <small class="text-muted">Dejar en blanco para no cambiar la contraseña.</small>
                </div>
            </div>

            <hr class="my-4">

            <button class="w-100 btn btn-warning btn-lg" type="submit">Actualizar Docente</button>
        </form>
    </div>
</div>
@endsection