@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Gestión de Administradores Académicos</h1>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header bg-dark text-white">Registrar Nuevo Admin</div>
            <div class="card-body">
                <form action="{{ route('sysadmin.admins.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label>Nombre Completo</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Email (Login)</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Contraseña</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Crear Admin</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Usuarios con Acceso Administrativo</div>
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Fecha Registro</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($admins as $admin)
                            <tr>
                                <td>{{ $admin->name }}</td>
                                <td>{{ $admin->email }}</td>
                                <td>{{ $admin->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('sysadmin.admins.edit', $admin->id) }}" class="btn btn-sm btn-warning">Editar</a>
                                        
                                        <form action="{{ route('sysadmin.admins.destroy', $admin->id) }}" method="post" onsubmit="return confirm('¿Eliminar este administrador?');">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger">Eliminar</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection