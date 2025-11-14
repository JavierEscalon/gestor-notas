@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard</h1>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card text-white bg-success mb-3">
            <div class="card-header">Alumnos activos</div>
            <div class="card-body">
                <h5 class="card-title">(ej. 150)</h5>
                <p class="card-text">Total de alumnos inscritos.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-info mb-3">
            <div class="card-header">Docentes activos</div>
            <div class="card-body">
                <h5 class="card-title">(ej. 15)</h5>
                <p class="card-text">Total de docentes registrados.</p>
            </div>
        </div>
    </div>
</div>

@endsection