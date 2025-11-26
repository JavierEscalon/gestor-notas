<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Alumno;
use App\Models\PeriodoEscolar;
use App\Models\Curso;
use PDF;

class BoletaController extends Controller
{
    /**
     * descarga la boleta de un solo alumno.
     */
    public function download($alumno_id)
    {
        $alumno = Alumno::with(['grado', 'seccion'])->findOrFail($alumno_id);
        
        // obtenemos los datos calculados usando la función auxiliar
        $datosBoleta = $this->obtenerDatosBoleta($alumno);

        $pdf = PDF::loadView('admin.reportes.boleta_pdf', $datosBoleta);
        $pdf->setPaper('letter', 'landscape');

        return $pdf->download('Boleta_' . $alumno->student_id_code . '.pdf');
    }

    /**
     * descarga TODAS las boletas de un grado/sección en un solo PDF.
     */
    public function downloadBatch($grado_id, $seccion_id)
    {
        // 1 buscamos todos los alumnos del grado y sección
        $alumnos = Alumno::where('grado_id', $grado_id)
                         ->where('seccion_id', $seccion_id)
                         ->orderBy('last_name') // Orden alfabético
                         ->get();

        if ($alumnos->isEmpty()) {
            return back()->with('error', 'No hay alumnos en este grado.');
        }

        // 2 generamos los datos para CADA alumno
        $boletas = [];
        foreach ($alumnos as $alumno) {
            $boletas[] = $this->obtenerDatosBoleta($alumno);
        }

        // 3 cargamos una vista especial para "Lote" (Batch)
        $pdf = PDF::loadView('admin.reportes.boletas_batch_pdf', ['boletas' => $boletas]);
        $pdf->setPaper('letter', 'landscape');

        return $pdf->download('Boletas_Grado_' . $grado_id . '_Seccion_' . $seccion_id . '.pdf');
    }

    /**
     * función privada (Helper): calcula notas y asistencia de un alumno.
     * (esta logica es la misma que tenías antes, solo que extraída para reutilizarla).
     */
    private function obtenerDatosBoleta(Alumno $alumno)
    {
        $periodos = PeriodoEscolar::orderBy('start_date')->get();
        $cursosInscritos = $alumno->cursos()->with('materia')->get();
        $materiasUnicas = $cursosInscritos->pluck('materia')->unique('id');

        $notas = [];
        $promediosFinales = [];

        foreach ($materiasUnicas as $materia) {
            $sumaMateria = 0;
            $periodosContados = 0;

            foreach ($periodos as $periodo) {
                $curso = Curso::where('materia_id', $materia->id)
                              ->where('periodo_id', $periodo->id)
                              ->where('grado_id', $alumno->grado_id)
                              ->where('seccion_id', $alumno->seccion_id)
                              ->first();

                if ($curso) {
                    $nota = floatval($curso->calcularPromedio($alumno->id));
                    $notas[$materia->id][$periodo->id] = number_format($nota, 1);

                    if ($nota > 0) {
                        $sumaMateria += $nota;
                        $periodosContados++;
                    }
                } else {
                    $notas[$materia->id][$periodo->id] = '-';
                }
            }
            
            $promedioFinal = ($periodosContados > 0) ? ($sumaMateria / $periodosContados) : 0;
            $promediosFinales[$materia->id] = number_format($promedioFinal, 0);
        }

        // asistencia
        $asistencias = $alumno->cursos->flatMap->asistencias->where('alumno_id', $alumno->id);
        
        return [
            'alumno' => $alumno,
            'periodos' => $periodos,
            'materias' => $materiasUnicas,
            'notas' => $notas,
            'promediosFinales' => $promediosFinales,
            'asistencia' => [
                'total' => $asistencias->where('estado', 'presente')->count(),
                'justificadas' => $asistencias->where('estado', 'justificado')->count(),
                'injustificadas' => $asistencias->where('estado', 'ausente')->count()
            ]
        ];
    }
}