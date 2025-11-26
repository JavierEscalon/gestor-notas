@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Bitácora de Eventos del Sistema</h1>
    <span class="text-muted">Últimos 100 registros</span>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-sm table-hover mb-0" style="font-size: 0.9rem;">
            <thead class="table-dark">
                <tr>
                    <th>Fecha/Hora</th>
                    <th>Usuario</th>
                    <th>Rol</th>
                    <th>Acción</th>
                    <th>Descripción</th>
                </tr>
            </thead>
            <tbody>
                @foreach($eventos as $evento)
                    <tr>
                        <td>{{ $evento->created_at->format('d/m/Y H:i:s') }}</td>
                        <td>
                            @if($evento->user)
                                <strong>{{ $evento->user->name }}</strong> <br>
                                <small class="text-muted">{{ $evento->user->email }}</small>
                            @else
                                <span class="text-muted">Sistema / Desconocido</span>
                            @endif
                        </td>
                        <td>
                            @if($evento->user)
                                <span class="badge bg-secondary">{{ $evento->user->role }}</span>
                            @endif
                        </td>
                        <td><span class="badge bg-info text-dark">{{ $evento->action }}</span></td>
                        <td>{{ $evento->description }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection