<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Grado;
use App\Models\Seccion;
use App\Models\Materia;
use App\Models\Curso;
use App\Models\PeriodoEscolar;
use App\Models\Alumno;

class ReporteAdminController extends Controller
{
    /**
     * muestra la vista inicial con los grados y secciones disponibles
     * (punto 2: "vista inicial con todos grados")
     */
    public function index()
    {
        // obtenemos una lista unica de grados y secciones que tienen cursos creados
        // agrupamos para mostrar opciones como "Primer Grado - A", "Primer Grado - B"
        $ofertaAcademica = Curso::with(['grado', 'seccion'])
                                ->select('grado_id', 'seccion_id')
                                ->distinct()
                                ->get();

        return view('admin.reportes.index', ['ofertaAcademica' => $ofertaAcademica]);
    }

    /**
     * muestra el reporte consolidado de materias para un grado/seccion especifico
     */
    public function show($grado_id, $seccion_id)
    {
        $grado = Grado::find($grado_id);
        $seccion = Seccion::find($seccion_id);
        
        // 1 obtenemos todas las materias que se imparten en este grado/seccion
        $materias = Curso::where('grado_id', $grado_id)
                         ->where('seccion_id', $seccion_id)
                         ->with('materia')
                         ->get()
                         ->pluck('materia')
                         ->unique('id');

        // 2 obtenemos todos los alumnos de ese grado/seccion
        $alumnos = Alumno::where('grado_id', $grado_id)
                         ->where('seccion_id', $seccion_id)
                         ->orderBy('last_name')
                         ->get();

        // 3 obtenemos todos los periodos ordenados
        $periodos = PeriodoEscolar::orderBy('start_date')->get();

        // 4 construccion de la matriz de datos
        // Estructura: $data[alumno_id][materia_id][periodo_id] = Nota Final
        $data = [];

        foreach ($alumnos as $alumno) {
            foreach ($materias as $materia) {
                
                $sumaNotas = 0;
                $periodosContados = 0;

                foreach ($periodos as $periodo) {
                    // buscamos el curso especifico de esta materia en este periodo
                    $curso = Curso::where('grado_id', $grado_id)
                                  ->where('seccion_id', $seccion_id)
                                  ->where('materia_id', $materia->id)
                                  ->where('periodo_id', $periodo->id)
                                  ->first();

                    if ($curso) {
                        // usamos la función calcularPromedio que creamos en el Modelo Curso
                        // (devuelve string, lo convertimos a float para sumar)
                        $nota = floatval($curso->calcularPromedio($alumno->id));
                        
                        // guardamos la nota en la matriz
                        $data[$alumno->id][$materia->id][$periodo->id] = $nota;

                        if ($nota > 0) { // asumimos que 0 significa que no hay nota aun
                            $sumaNotas += $nota;
                            $periodosContados++;
                        }
                    } else {
                        $data[$alumno->id][$materia->id][$periodo->id] = '-';
                    }
                }

                // calculamos el promedio final acumulado
                $promedioFinal = ($periodosContados > 0) ? ($sumaNotas / $periodosContados) : 0;
                $data[$alumno->id][$materia->id]['final'] = number_format($promedioFinal, 2);
            }
        }

        return view('admin.reportes.show', [
            'grado' => $grado,
            'seccion' => $seccion,
            'materias' => $materias,
            'periodos' => $periodos,
            'alumnos' => $alumnos,
            'data' => $data
        ]);
    }
}