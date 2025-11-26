@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Reporte Consolidado: {{ $grado->name }} "{{ $seccion->name }}"</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.reportes.index') }}" class="btn btn-sm btn-secondary">Volver</a>
        <a href="{{ route('admin.boleta.batch', ['grado' => $grado->id, 'seccion' => $seccion->id]) }}" class="btn btn-sm btn-success ms-2" target="_blank">
            Imprimir Todas las Boletas (PDF)
        </a>
    </div>
</div>

@foreach($materias as $materia)
    <div class="card mb-5">
        <div class="card-header bg-dark text-white">
            <strong>Materia:</strong> {{ $materia->name }}
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm mb-0">
                <thead class="table-light text-center">
                    <tr>
                        <th class="text-start" style="width: 30%">Estudiante</th>
                        @foreach($periodos as $periodo)
                            <th>{{ $periodo->name }}</th>
                        @endforeach
                        <th class="bg-secondary text-white">Promedio Final</th>
                        <th>Estado</th>
                    </tr>
                </thead>    
                <tbody>
                    @foreach($alumnos as $alumno)
                        @php
                            $promedioFinal = $data[$alumno->id][$materia->id]['final'];
                        @endphp
                        <tr class="text-center">
                            <td class="text-start">
                                {{ $alumno->last_name }}, {{ $alumno->first_name }}
                                <a href="{{ route('admin.boleta.download', $alumno->id) }}" class="btn btn-sm btn-outline-danger float-end" title="Descargar Boleta" target="_blank">
                                    📄 PDF
                                </a>
                            </td>
                            
                            @foreach($periodos as $periodo)
                                <td>
                                    {{ $data[$alumno->id][$materia->id][$periodo->id] }}
                                </td>
                            @endforeach

                            <td class="fw-bold bg-light">{{ $promedioFinal }}</td>
                            
                            <td>
                                @if($promedioFinal >= 5)
                                    <span class="badge bg-success">Aprobado</span>
                                @else
                                    <span class="badge bg-danger">Reprobado</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endforeach

@endsection