@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Reportes Consolidados por Grado</h1>
</div>

<div class="row">
    @foreach ($ofertaAcademica as $item)
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm text-center h-100">
            <div class="card-body d-flex flex-column justify-content-center">
                <h5 class="card-title text-primary">{{ $item->grado->name }}</h5>
                <h6 class="card-subtitle mb-2 text-muted">Sección "{{ $item->seccion->name }}"</h6>
                <p class="card-text">Ver consolidado de notas anuales.</p>
                <a href="{{ route('admin.reportes.show', ['grado' => $item->grado_id, 'seccion' => $item->seccion_id]) }}" class="btn btn-outline-primary mt-auto">Ver Reporte</a>
            </div>
        </div>
    </div>
    @endforeach
    
    @if($ofertaAcademica->isEmpty())
        <div class="alert alert-info">No hay cursos configurados con grados y secciones aún.</div>
    @endif
</div>
@endsection