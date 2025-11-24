<?php

namespace App\Http\Controllers\Padre;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Calificacion;

class DashboardController extends Controller
{
    public function index()
    {
        // 1 obtenemos al padre logueado
        $padre = Auth::user()->padre;

        if (!$padre) {
            return redirect('/')->with('error', 'Perfil de padre no encontrado.');
        }

        // 2 obtenemos a sus hijos con toda su informacion academica
        // Cursos, Materias, Docentes, Periodos
        $hijos = $padre->alumnos()->with(['cursos.materia', 'cursos.docente', 'cursos.periodo', 'grado', 'seccion'])->get();

        // 3 preparamos las calificaciones para cada hijo
        // estructura: $notasPorHijo[id_hijo][id_curso] = Coleccion de notas
        $notasPorHijo = [];

        foreach ($hijos as $hijo) {
            $calificaciones = Calificacion::where('alumno_id', $hijo->id)
                                          ->with('tipoActividad')
                                          ->get()
                                          ->groupBy('curso_id');

            $notasPorHijo[$hijo->id] = $calificaciones;
        }

        return view('padre.dashboard', [
            'padre' => $padre,
            'hijos' => $hijos,
            'notasPorHijo' => $notasPorHijo
        ]);
    }
}