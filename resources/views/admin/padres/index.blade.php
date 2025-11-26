@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Gestión de Padres de Familia</h1>
    <a href="{{ route('admin.padres.create') }}" class="btn btn-primary">+ Nuevo Padre</a>
</div>

<div class="table-responsive">
    <table class="table table-striped table-sm align-middle">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Email (Usuario)</th>
                <th>Teléfono</th>
                <th>Hijos Asignados</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($padres as $padre)
                <tr>
                    <td>{{ $padre->first_name }} {{ $padre->last_name }}</td>
                    <td>{{ $padre->user->email }}</td>
                    <td>{{ $padre->phone }}</td>
                    <td>
                        @foreach($padre->alumnos as $hijo)
                            <span class="badge bg-secondary">{{ $hijo->first_name }}</span>
                        @endforeach
                    </td>
                    <td class="d-flex">
                        <a href="{{ route('admin.padres.hijos', $padre->id) }}" class="btn btn-sm btn-info me-1" title="Asignar Hijos">👨‍👧‍👦 Hijos</a>
                        <a href="{{ route('admin.padres.edit', $padre->id) }}" class="btn btn-sm btn-warning me-1">Editar</a>
                        <form action="{{ route('admin.padres.destroy', $padre->id) }}" method="post" onsubmit="return confirm('¿Eliminar este padre?');">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">X</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection