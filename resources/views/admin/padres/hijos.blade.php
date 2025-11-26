@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Asignar Hijos a: {{ $padre->first_name }} {{ $padre->last_name }}</h1>
</div>

<form action="{{ route('admin.padres.hijos.store', $padre->id) }}" method="POST">
    @csrf
    
    <div class="card">
        <div class="card-header bg-light">
            <input type="text" id="buscador" class="form-control" placeholder="🔍 Buscar estudiante por nombre o carnet...">
        </div>
        <div class="card-body" style="max-height: 500px; overflow-y: auto;">
            <div class="row" id="lista-alumnos">
                @foreach($alumnos as $alumno)
                    <div class="col-md-6 alumno-item">
                        <div class="form-check border p-2 mb-2 rounded {{ in_array($alumno->id, $hijosIds) ? 'bg-light border-primary shadow-sm' : '' }}">
                            
                            <input class="form-check-input ms-1" type="checkbox" 
                                   name="alumnos_ids[]" 
                                   value="{{ $alumno->id }}" 
                                   id="alum-{{ $alumno->id }}"
                                   {{ in_array($alumno->id, $hijosIds) ? 'checked' : '' }}>
                            
                            <label class="form-check-label ms-2 w-75" for="alum-{{ $alumno->id }}">
                                <strong>{{ $alumno->last_name }}, {{ $alumno->first_name }}</strong>
                                
                                @if($alumno->padres_count > 0)
                                    <span class="badge bg-warning text-dark ms-2" style="font-size: 0.7em;" title="Este alumno ya tiene cuentas de padre asociadas">
                                        Tiene {{ $alumno->padres_count }} padre(s)
                                    </span>
                                @endif

                                <br>
                                <small class="text-muted">Carnet: {{ $alumno->student_id_code }}</small>
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="card-footer text-end">
            <button type="submit" class="btn btn-success">Guardar Asignaciones</button>
        </div>
    </div>
</form>

<script>
    // Script simple para filtrar alumnos en tiempo real
    document.getElementById('buscador').addEventListener('keyup', function() {
        let texto = this.value.toLowerCase();
        let items = document.querySelectorAll('.alumno-item');
        
        items.forEach(function(item) {
            let contenido = item.textContent.toLowerCase();
            if (contenido.includes(texto)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });
</script>
@endsection