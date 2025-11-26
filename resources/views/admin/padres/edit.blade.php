@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Editar Padre: {{ $padre->first_name }} {{ $padre->last_name }}</h1>
</div>

<div class="row">
    <div class="col-md-8">
        <form action="{{ route('admin.padres.update', $padre->id) }}" method="POST">
            @csrf @method('PUT')
            
            <h5 class="mb-3">Datos Personales</h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <label>Nombres</label>
                    <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $padre->first_name) }}" required>
                </div>
                <div class="col-md-6">
                    <label>Apellidos</label>
                    <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $padre->last_name) }}" required>
                </div>
                <div class="col-md-12">
                    <label>Teléfono</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $padre->phone) }}">
                </div>
            </div>

            <h5 class="mb-3 mt-4">Cuenta de Usuario</h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <label>Email (Login)</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $padre->user->email) }}" required>
                </div>
                <div class="col-md-6">
                    <label>Contraseña (Opcional)</label>
                    <input type="password" name="password" class="form-control" placeholder="Dejar vacío para no cambiar">
                </div>
            </div>

            <hr class="my-4">
            <button type="submit" class="btn btn-warning w-100">Actualizar Datos</button>
        </form>
    </div>
</div>
@endsection