@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Registrar Nuevo Padre</h1>
</div>

<div class="row">
    <div class="col-md-8">
        <form action="{{ route('admin.padres.store') }}" method="POST">
            @csrf
            <h5 class="mb-3">Datos Personales</h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <label>Nombres</label>
                    <input type="text" name="first_name" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label>Apellidos</label>
                    <input type="text" name="last_name" class="form-control" required>
                </div>
                <div class="col-md-12">
                    <label>Teléfono</label>
                    <input type="text" name="phone" class="form-control">
                </div>
            </div>

            <h5 class="mb-3 mt-4">Cuenta de Usuario</h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <label>Email (Login)</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label>Contraseña</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
            </div>

            <hr class="my-4">
            <button type="submit" class="btn btn-primary w-100">Guardar y Asignar Hijos >></button>
        </form>
    </div>
</div>
@endsection