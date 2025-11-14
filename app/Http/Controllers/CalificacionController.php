<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Curso; // necesitamos el curso
use App\Models\TipoActividad; // para el <select> de actividades
use App\Models\Calificacion;

class CalificacionController extends Controller
{
    /**
     * Muestra el formulario para registrar calificaciones de un curso.
     */
    public function show(Curso $curso)
    {
        // Cargamos el curso con los alumnos ya inscritos
        $curso->load('alumnos');

        // Buscamos los tipos de actividad (ej. Tarea, Examen)
        $tiposActividad = TipoActividad::all();

        // (nuevo) buscamos las calificaciones que ya existen
        //    para este curso, agrupadas por 'activity_name'
        $actividadesRegistradas = Calificacion::where('curso_id', $curso->id)
                                    ->with('tipoActividad') // (traemos el nombre del tipo)
                                    ->select('activity_name', 'tipo_actividad_id') // (solo necesitamos estos campos)
                                    ->distinct() // (para que "Examen 1" solo aparezca una vez)
                                    ->get();

        // Pasamos todos los datos a la vista
        return view('docente.calificaciones', [
            'curso' => $curso,
            'tiposActividad' => $tiposActividad,
            'actividadesRegistradas' => $actividadesRegistradas, // (pasamos las actividades)
        ]);
    }

    /**
     * guarda las nuevas calificaciones en la db
     */
    public function store(Request $request, Curso $curso)
    {
        // validamos los datos del formulario
        $request->validate([
            'activity_name' => 'required|string|max:255',
            'tipo_actividad_id' => 'required|exists:tipo_actividads,id',
            'scores' => 'required|array', // el campo 'scores' debe ser una lista
            'scores.*' => 'required|numeric|min:0|max:10', // cada nota en la lista debe ser un numero entre 0 y 10
        ]);

        // obtenemos el periodo_id del curso
        $periodoId = $curso->periodo_id;

        // el paso clave, iteramos (recorremos) la lista de notas
        // el 'name="scores[{{ $alumno->id }}]"' que pusimos en la vista
        // nos envia un array asi: [ 'id_del_alumno_1' => 'nota_1', 'id_del_alumno_2' => 'nota_2' ]

        foreach ($request->scores as $alumnoId => $score) {

            // creamos un nuevo registro en la tabla 'calificacions'
            // por cada alumno en la lista
            Calificacion::create([
                'alumno_id' => $alumnoId,
                'curso_id' => $curso->id,
                'periodo_id' => $periodoId,
                'tipo_actividad_id' => $request->tipo_actividad_id,
                'activity_name' => $request->activity_name,
                'score' => $score,
            ]);
        }

        // redirigimos al dashboard del docente con un mensaje de exito
        return redirect()->route('docente.dashboard')->with('success', '¡Calificaciones guardadas exitosamente!');
    }
    

    /**
     * elimina todas las calificaciones asociadas con un nombre de actividad
     * para un curso especifico.
     */
    public function destroyActivity(Curso $curso, $activity_name)
    {
        // buscamos todas las calificaciones que coincidan
        // con el curso y el nombre de la actividad
        Calificacion::where('curso_id', $curso->id)
                    ->where('activity_name', $activity_name)
                    ->delete(); // (las eliminamos)

        // redirigimos de vuelta a la pagina anterior
        // con un mensaje de exito
        return back()->with('success', '¡Actividad y sus notas eliminadas exitosamente!');
    }

    /**
     * muestra el formulario para editar las notas de una actividad.
     */
    public function editActivity(Curso $curso, $activity_name)
    {
        // cargamos el curso
        $curso->load('alumnos');

        // buscamos el tipo de actividad para saber cual es
        $tipoActividad = Calificacion::where('curso_id', $curso->id)
                                    ->where('activity_name', $activity_name)
                                    ->first()
                                    ->tipoActividad; // (usamos la relacion)

        // buscamos todas las calificaciones de esta actividad
        // y las convertimos en un array facil de usar
        // (ej. [ 'id_alumno' => 'nota' ])
        $calificaciones = Calificacion::where('curso_id', $curso->id)
                                    ->where('activity_name', $activity_name)
                                    ->pluck('score', 'alumno_id');

        // pasamos todos los datos a la nueva vista de edicion
        return view('docente.calificaciones_edit', [
            'curso' => $curso,
            'activity_name' => $activity_name,
            'tipoActividad' => $tipoActividad,
            'calificaciones' => $calificaciones,
        ]);
    }

    /**
     * actualiza las calificaciones de una actividad existente.
     */
    public function updateActivity(Request $request, Curso $curso, $activity_name)
    {
        // 1. validamos los datos (solo la lista de scores)
        $request->validate([
            'scores' => 'required|array',
            'scores.*' => 'required|numeric|min:0|max:10',
        ]);

        // 2. iteramos (recorremos) la lista de notas que nos llego
        foreach ($request->scores as $alumnoId => $score) {
            
            // 3. usamos 'updateorcreate' para actualizar la nota
            //    esto es muy eficiente:
            //    - busca una calificacion que coincida con
            //      curso, alumno y nombre de actividad
            //    - si la encuentra, la actualiza con el nuevo 'score'
            //    - si no la encuentra, la crea (aunque no deberia pasar)
            Calificacion::updateOrCreate(
                [
                    'curso_id' => $curso->id,
                    'alumno_id' => $alumnoId,
                    'activity_name' => $activity_name,
                ],
                [
                    'score' => $score,
                    
                    // (nos aseguramos de que los otros datos esten ahi)
                    'tipo_actividad_id' => $request->tipo_actividad_id, 
                    'periodo_id' => $curso->periodo_id,
                ]
            );
        }

        // 4. redirigimos de vuelta al "hub" de calificaciones
        //    (la pagina 'show' del curso)
        return redirect()->route('docente.cursos.calificaciones', $curso->id)
                         ->with('success', '¡Calificaciones actualizadas exitosamente!');
    }

}