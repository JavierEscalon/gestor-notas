<!DOCTYPE html>
<html>
<head>
    <title>Boletas de Calificaciones</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .page-break { page-break-after: always; }
        
        /* estilos copiados de la boleta individual */
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; text-transform: uppercase; font-size: 14px; }
        .header h3 { margin: 5px 0; font-weight: normal; font-size: 12px; }
        .info-box { width: 100%; border: 1px solid #000; border-collapse: collapse; margin-bottom: 15px; }
        .info-box td { border: 1px solid #000; padding: 4px 8px; }
        .label { background-color: #e0e0e0; font-weight: bold; width: 15%; }
        .grades-table { width: 100%; border-collapse: collapse; }
        .grades-table th, .grades-table td { border: 1px solid #000; padding: 5px; text-align: center; }
        .grades-table th { background-color: #ccc; font-weight: bold; }
        .text-left { text-align: left !important; }
        .asistencia-box { margin-bottom: 15px; border: 1px solid #000; padding: 5px; font-weight: bold; background-color: #f9f9f9; }
    </style>
</head>
<body>

    @foreach($boletas as $datos)
        
        <div class="header">
            <h2>Ministerio de Educación, Ciencia y Tecnología</h2>
            <h3>Centro Escolar Católico San Juan Bosco</h3>
            <h3>BOLETA DE CALIFICACIONES - AÑO {{ date('Y') }}</h3>
        </div>

        <table class="info-box">
            <tr>
                <td class="label">Sede Educativa</td>
                <td colspan="3">Centro Escolar Católico San Juan Bosco</td>
            </tr>
            <tr>
                <td class="label">Grado</td>
                <td>{{ $datos['alumno']->grado->name }}</td>
                <td class="label">Sección</td>
                <td>"{{ $datos['alumno']->seccion->name }}"</td>
            </tr>
            <tr>
                <td class="label">Estudiante</td>
                <td colspan="3"><b>{{ $datos['alumno']->last_name }}, {{ $datos['alumno']->first_name }}</b> (NIE: {{ $datos['alumno']->student_id_code }})</td>
            </tr>
        </table>

        <div class="asistencia-box">
            Resumen de Asistencia: 
            &nbsp;&nbsp; Asistencias: {{ $datos['asistencia']['total'] }}
            &nbsp;&nbsp; | &nbsp;&nbsp; Inasistencias Justificadas: {{ $datos['asistencia']['justificadas'] }}
            &nbsp;&nbsp; | &nbsp;&nbsp; Inasistencias Sin Justificar: {{ $datos['asistencia']['injustificadas'] }}
        </div>

        <table class="grades-table">
            <thead>
                <tr>
                    <th class="text-left" style="width: 40%">Componente plan estudio</th>
                    @foreach($datos['periodos'] as $p)
                        <th style="width: 10%">{{ $p->name }}</th>
                    @endforeach
                    <th style="width: 10%">NF</th>
                    <th style="width: 15%">Resultado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($datos['materias'] as $materia)
                    @php $nf = $datos['promediosFinales'][$materia->id]; @endphp
                    <tr>
                        <td class="text-left">{{ $materia->name }}</td>
                        @foreach($datos['periodos'] as $p)
                            <td>{{ $datos['notas'][$materia->id][$p->id] }}</td>
                        @endforeach
                        <td><b>{{ $nf }}</b></td>
                        <td>@if($nf >= 5) Aprobado @else Reprobado @endif</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if(!$loop->last)
            <div class="page-break"></div>
        @endif

    @endforeach

</body>
</html>