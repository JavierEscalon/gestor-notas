<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    /**
     * muestra el reporte de notas del periodo actual para un curso.
     */
    public function show(Curso $curso)
    {
        // 1 cargamos alumnos y sus notas
        $curso->load(['alumnos', 'calificaciones.tipoActividad']);

        // 2 preparamos los datos para la vista
        $reporte = [];

        foreach ($curso->alumnos as $alumno) {
            // usamos la función que ya creamos en el Modelo Curso
            $promedio = $curso->calcularPromedio($alumno->id);
            
            $estado = ($promedio >= 5.0) ? 'Aprobado' : 'Reprobado';
            
            // obtenemos el detalle de notas para mostrarlas en columnas
            // agrupamos por nombre de actividad para facilitar la vista
            $notasDetalle = $curso->calificaciones
                                  ->where('alumno_id', $alumno->id)
                                  ->mapWithKeys(function ($item) {
                                      return [$item->activity_name => $item->score];
                                  });

            $reporte[] = [
                'alumno' => $alumno,
                'notas' => $notasDetalle,
                'promedio' => $promedio,
                'estado' => $estado
            ];
        }

        // 3 obtenemos la lista única de actividades para los encabezados de la tabla
        $actividades = $curso->calificaciones->unique('activity_name')->pluck('activity_name');

        return view('docente.reporte_curso', [
            'curso' => $curso,
            'reporte' => $reporte,
            'actividades' => $actividades
        ]);
    }

    /**
     * cierra el periodo para este curso (impide más ediciones).
     */
    public function cerrarPeriodo(Curso $curso)
    {
        $curso->update(['is_calificaciones_closed' => true]);

        return back()->with('success', '¡Periodo cerrado exitosamente! Ya no se pueden modificar las calificaciones.');
    }
}