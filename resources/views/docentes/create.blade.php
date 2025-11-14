@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Crear Nuevo Docente</h1>
</div>

<div class="row">
    <div class="col-lg-8">
        <form action="{{ route('docentes.store') }}" method="post">
            @csrf <h5 class="mb-3">Datos del perfil</h5>
            
            <div class="row g-3">
                <div class="col-sm-6">
                    <label for="first_name" class="form-label">Nombres</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" required>
                </div>

                <div class="col-sm-6">
                    <label for="last_name" class="form-label">Apellidos</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" required>
                </div>

                <div class="col-12">
                    <label for="specialty" class="form-label">Especialidad</label>
                    <input type="text" class="form-control" id="specialty" name="specialty" placeholder="ej. matematicas">
                </div>
                
                <div class="col-12">
                    <label for="phone" class="form-label">Telefono (Opcional)</label>
                    <input type="text" class="form-control" id="phone" name="phone">
                </div>
            </div>
            
            <hr class="my-4">

            <h5 class="mb-3">Datos De La Cuenta (Login)</h5>
            
            <div class="row g-3">
                <div class="col-12">
                    <label for="email" class="form-label">Email (Usuario)</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                
                <div class="col-12">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
            </div>
            
            <hr class="my-4">

            <button class="w-100 btn btn-primary btn-lg" type="submit">Guardar Docente</button>
        </form>
    </div>
</div>

@endsection