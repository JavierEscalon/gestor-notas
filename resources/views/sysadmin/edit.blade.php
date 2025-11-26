@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Editar Administrador Académico</h1>
</div>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-body">
                <form action="{{ route('sysadmin.admins.update', $admin->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label>Nombre Completo</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $admin->name) }}" required>
                    </div>
                    <div class="mb-3">
                        <label>Email (Login)</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $admin->email) }}" required>
                    </div>
                    <div class="mb-3">
                        <label>Nueva Contraseña (Opcional)</label>
                        <input type="password" name="password" class="form-control" placeholder="Dejar en blanco para mantener la actual">
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('sysadmin.dashboard') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-warning">Actualizar Datos</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection