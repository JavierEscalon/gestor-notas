@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Mis Cursos Asignados</h1>
</div>

<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th scope="col">Materia</th>
                <th scope="col">Grado</th>
                <th scope="col">Sección</th>
                <th scope="col">Período</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($cursos as $curso)
                <tr>
                    <td>{{ $curso->materia->name }}</td>
                    <td>{{ $curso->grado->name }}</td>
                    <td>{{ $curso->seccion->name }}</td>
                    <td>{{ $curso->periodo->name }}</td>
                    <td>
                        <a href="{{ route('docente.cursos.calificaciones', $curso->id) }}" class="btn btn-sm btn-primary">Registrar Calificaciones</a>
                        <a href="{{ route('docente.cursos.asistencia', $curso->id) }}" class="btn btn-sm btn-success">Asistencia</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No tienes cursos asignados actualmente.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection