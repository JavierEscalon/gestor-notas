<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Curso;
use App\Models\Asistencia;
use Carbon\Carbon; // (para manejar fechas)

class AsistenciaController extends Controller
{
    /**
     * muestra la hoja de asistencia de un curso
     */
    public function index(Request $request, Curso $curso)
    {   

        // SEGURIDAD: verificar que este curso pertenezca al docente logueado
        if ($curso->docente_id !== auth()->user()->docente->id) {
            abort(403, 'Acceso denegado. Este curso no te pertenece.');
        }

        // 1 determinamos la fecha (hoy o la que elija el docente)
        $fecha = $request->input('fecha', Carbon::now()->format('Y-m-d'));

        // 2 cargamos los alumnos del curso
        $curso->load('alumnos');

        // 3 buscamos las asistencias que ya existen para esa fecha
        // y las organizamos por id de alumno para facil acceso
        $asistencias = Asistencia::where('curso_id', $curso->id)
                                 ->where('fecha', $fecha)
                                 ->get()
                                 ->keyBy('alumno_id');

        return view('docente.asistencia', [
            'curso' => $curso,
            'fecha' => $fecha,
            'asistencias' => $asistencias
        ]);
    }

    /**
     * guarda o actualiza la asistencia
     */
    public function store(Request $request, Curso $curso)
    {   

        // SEGURIDAD: verificar que este curso pertenezca al docente logueado
        if ($curso->docente_id !== auth()->user()->docente->id) {
            abort(403, 'Acceso denegado. Este curso no te pertenece.');
        }

        $request->validate([
            'fecha' => 'required|date',
            'asistencia' => 'required|array', // (array de estados)
        ]);

        // recorremos el array de asistencias
        // [alumno_id => estado]
        foreach ($request->asistencia as $alumnoId => $estado) {
            
            // buscamos la observacion correspondiente (si existe)
            $observacion = $request->observacion[$alumnoId] ?? null;

            // guardamos o actualizamos
            Asistencia::updateOrCreate(
                [
                    'curso_id' => $curso->id,
                    'alumno_id' => $alumnoId,
                    'fecha' => $request->fecha,
                ],
                [
                    'estado' => $estado,
                    'observacion' => $observacion,
                ]
            );
        }

        return back()->with('success', '¡Asistencia guardada correctamente!');
    }
}