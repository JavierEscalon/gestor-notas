@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Panel de Control Académico</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="location.reload()">🔄 Actualizar Datos</button>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Total Alumnos</h6>
                        <h2 class="fw-bold my-2">{{ $totalAlumnos }}</h2>
                    </div>
                    <div style="font-size: 2rem;">🎓</div>
                </div>
                <small class="card-text">Inscritos en el sistema</small>
            </div>
            <div class="card-footer bg-primary border-0">
                <a href="{{ route('alumnos.index') }}" class="text-white text-decoration-none small">Ver listado &rarr;</a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-white bg-success shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Docentes</h6>
                        <h2 class="fw-bold my-2">{{ $totalDocentes }}</h2>
                    </div>
                    <div style="font-size: 2rem;">👨‍🏫</div>
                </div>
                <small class="card-text">Personal activo</small>
            </div>
            <div class="card-footer bg-success border-0">
                <a href="{{ route('docentes.index') }}" class="text-white text-decoration-none small">Gestionar &rarr;</a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-white bg-warning text-dark shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Cursos Abiertos</h6>
                        <h2 class="fw-bold my-2">{{ $totalCursos }}</h2>
                    </div>
                    <div style="font-size: 2rem;">📚</div>
                </div>
                <small class="card-text">Materias impartiéndose</small>
            </div>
            <div class="card-footer bg-warning border-0">
                <a href="{{ route('cursos.index') }}" class="text-dark text-decoration-none small">Ver cursos &rarr;</a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-white bg-info shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Notas Registradas</h6>
                        <h2 class="fw-bold my-2">{{ $totalNotas }}</h2>
                    </div>
                    <div style="font-size: 2rem;">📝</div>
                </div>
                <small class="card-text">Calificaciones totales</small>
            </div>
            <div class="card-footer bg-info border-0">
                <a href="{{ route('admin.reportes.index') }}" class="text-white text-decoration-none small">Ver Reportes &rarr;</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white fw-bold">
                📢 Actividad Reciente del Sistema
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Hora</th>
                                <th>Docente</th>
                                <th>Acción</th>
                                <th>Detalle</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ultimasNotas as $nota)
                                <tr>
                                    <td class="text-muted small" style="width: 15%;">
                                        {{ $nota->created_at->diffForHumans() }}
                                    </td>
                                    <td style="width: 25%;">
                                        <span class="fw-bold">{{ $nota->curso->docente->first_name }} {{ $nota->curso->docente->last_name }}</span>
                                    </td>
                                    <td style="width: 30%;">
                                        <span class="badge bg-light text-dark border">Registró nota</span>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            Calificó a <strong>{{ $nota->alumno->first_name }}</strong> en 
                                            <em>{{ $nota->curso->materia->name }}</em>
                                        </small>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        No hay actividad reciente registrada.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white text-center">
                <a href="{{ route('admin.reportes.index') }}" class="btn btn-sm btn-outline-primary">Ver Reportes Completos</a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white fw-bold">
                ⚡ Accesos Rápidos
            </div>
            <div class="list-group list-group-flush">
                <a href="{{ route('alumnos.create') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                    Matricular Nuevo Alumno
                    <span class="badge bg-primary rounded-pill">+</span>
                </a>
                <a href="{{ route('docentes.create') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                    Registrar Nuevo Docente
                    <span class="badge bg-success rounded-pill">+</span>
                </a>
                <a href="{{ route('cursos.create') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                    Abrir Nuevo Curso
                    <span class="badge bg-warning text-dark rounded-pill">+</span>
                </a>
                <a href="{{ route('admin.padres.create') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                    Registrar Padre de Familia
                    <span class="badge bg-info rounded-pill">+</span>
                </a>
            </div>
        </div>

        <div class="card shadow-sm bg-light border-success">
            <div class="card-body text-center">
                <h5 class="card-title text-success">Sistema Operativo</h5>
                <p class="card-text small text-muted">Todas las funciones están activas.</p>
                <button class="btn btn-success btn-sm disabled">En Línea ✅</button>
            </div>
        </div>
    </div>
</div>

@endsection