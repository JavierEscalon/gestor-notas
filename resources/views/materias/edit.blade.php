@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Editar Materia: {{ $materia->name }}</h1>
</div>

<div class="row">
    <div class="col-lg-8">
        <form action="{{ route('materias.update', $materia->id) }}" method="post">
            @csrf
            @method('PUT') <div class="mb-3">
                <label for="name" class="form-label">Nombre de la Materia</label>
                <input type="text" class="form-control" id="name" name="name" 
                       value="{{ old('name', $materia->name) }}" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Descripción (Opcional)</label>
                <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $materia->description) }}</textarea>
            </div>

            <hr class="my-4">

            <button class="w-100 btn btn-warning btn-lg" type="submit">Actualizar Materia</button>
        </form>
    </div>
</div>

@endsection