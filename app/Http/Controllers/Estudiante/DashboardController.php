<?php

namespace App\Http\Controllers\Estudiante;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Calificacion;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Obtenemos al alumno logueado
        $user = Auth::user();
        $alumno = $user->alumno;

        if (!$alumno) {
            // Seguridad: Si es un usuario 'estudiante' pero no tiene perfil de alumno creado
            return redirect('/')->with('error', 'Perfil de alumno no encontrado.');
        }

        // 2. Obtenemos los cursos inscritos con toda su info (materia, docente, etc.)
        $cursos = $alumno->cursos()->with(['materia', 'docente', 'periodo', 'grado', 'seccion'])->get();

        // 3. Obtenemos TODAS las calificaciones de este alumno
        //    y las agrupamos por 'curso_id' para facilitar su uso en la vista
        $calificaciones = Calificacion::where('alumno_id', $alumno->id)
                                      ->with('tipoActividad')
                                      ->get()
                                      ->groupBy('curso_id');

        return view('estudiante.dashboard', [
            'alumno' => $alumno,
            'cursos' => $cursos,
            'calificaciones' => $calificaciones
        ]);
    }
}