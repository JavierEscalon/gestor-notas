<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Curso; // Necesitamos el modelo Curso
use App\Models\TipoActividad; // Para obtener la lista de tipos
use App\Models\Calificacion; // Para guardar y leer las notas

class CalificacionController extends Controller
{   
    /**
     * Muestra el formulario para registrar y ver calificaciones de un curso.
     * (GET /docente/cursos/{curso}/calificaciones)
     */
    public function show(Curso $curso)
    {

        // SEGURIDAD: verificar que este curso pertenezca al docente logueado
        if ($curso->docente_id !== auth()->user()->docente->id) {
            abort(403, 'Acceso denegado. Este curso no te pertenece.');
        }

        // 1 Cargamos el curso junto con los alumnos ya inscritos
        $curso->load('alumnos');

        // 2 Buscamos todos los tipos de actividad disponibles (ej. Tarea, Examen)
        $tiposActividad = TipoActividad::all();

        // 3 Buscamos las actividades que ya han sido registradas para este curso.
        // Agrupamos por 'activity_name' para mostrarlas en la tabla resumen.
        $actividadesRegistradas = Calificacion::where('curso_id', $curso->id)
                                    ->with('tipoActividad') // Traemos la relación para mostrar el nombre del tipo
                                    ->select('activity_name', 'tipo_actividad_id', 'percentage') // Seleccionamos nombre, tipo y porcentaje
                                    ->distinct() // Queremos solo una fila por actividad (no una por alumno)
                                    ->get();

        // 4 Pasamos todos los datos a la vista principal
        return view('docente.calificaciones', [
            'curso' => $curso,
            'tiposActividad' => $tiposActividad,
            'actividadesRegistradas' => $actividadesRegistradas,
        ]);
    }

    /**
     * Guarda una NUEVA actividad y sus calificaciones en la base de datos.
     * (POST /docente/cursos/{curso}/calificaciones)
     */
    public function store(Request $request, Curso $curso)
    {

        // SEGURIDAD: verificar que este curso pertenezca al docente logueado
        if ($curso->docente_id !== auth()->user()->docente->id) {
            abort(403, 'Acceso denegado. Este curso no te pertenece.');
        }

        // bloqueo de edicion en caso un periodo este cerrado
        if ($curso->is_calificaciones_closed) {
            return back()->withErrors(['error' => 'Este período ya está cerrado. No se pueden modificar las notas.']);
        }

        // 1 Validamos los datos del formulario
        $request->validate([
            'activity_name' => 'required|string|max:255',
            'tipo_actividad_id' => 'required|exists:tipo_actividads,id',
            'percentage' => 'required|numeric|min:1|max:100', // Validamos el porcentaje manual (1-100)
            'scores' => 'required|array', // Debe ser una lista de notas
            'scores.*' => 'required|numeric|min:0|max:10', // Cada nota debe ser 0-10
        ]);

        // --- VALIDACIÓN DE LÓGICA DE NEGOCIO (SUMA 100%) ---
        
        // Obtenemos el ID del periodo del curso
        $periodoId = $curso->periodo_id;

        // A Calculamos cuánto porcentaje ya está ocupado en este curso/periodo
        // (Sumamos el porcentaje de cada actividad única ya registrada)
        $sumaActual = Calificacion::where('curso_id', $curso->id)
                                  ->where('periodo_id', $periodoId)
                                  ->select('activity_name', 'percentage')
                                  ->distinct()
                                  ->get()
                                  ->sum('percentage');

        // B Calculamos cuánto sería la nueva suma si agregamos esta actividad
        $nuevoPorcentaje = $request->percentage;
        $total = $sumaActual + $nuevoPorcentaje;

        // C Si se pasa de 100%, devolvemos un error y detenemos el guardado
        // (Usamos 100.01 para evitar errores de redondeo flotante)
        if ($total > 100.01) {
            return back()->withErrors([
                'percentage' => "Error: La suma total superaría el 100%. Actualmente tienes $sumaActual%. Solo puedes agregar una actividad de hasta " . (100 - $sumaActual) . "%."
            ])->withInput();
        }
        // ----------------------------------------------------

        // 2 Si pasó la validación, guardamos las notas
        //    Iteramos sobre el array de notas: [alumno_id => nota]
        foreach ($request->scores as $alumnoId => $score) {
            Calificacion::create([
                'alumno_id' => $alumnoId,
                'curso_id' => $curso->id,
                'periodo_id' => $periodoId,
                'tipo_actividad_id' => $request->tipo_actividad_id,
                'activity_name' => $request->activity_name,
                'percentage' => $request->percentage, // Guardamos el porcentaje asignado
                'score' => $score,
            ]);
        }

        // 3 Redirigimos con mensaje de éxito
        return redirect()->route('docente.cursos.calificaciones', $curso->id)
                         ->with('success', '¡Calificaciones guardadas! (Acumulado Total: ' . $total . '%)');
    }

    /**
     * Muestra el formulario para EDITAR las notas de una actividad existente.
     * (GET /docente/cursos/{curso}/calificaciones/{activity_name}/edit)
     */
    public function editActivity(Curso $curso, $activity_name)
    {

        // SEGURIDAD: verificar que este curso pertenezca al docente logueado
        if ($curso->docente_id !== auth()->user()->docente->id) {
            abort(403, 'Acceso denegado. Este curso no te pertenece.');
        }

        // --- BLOQUEO DE SEGURIDAD --- 
        if ($curso->is_calificaciones_closed) {
            return redirect()->route('docente.cursos.calificaciones', $curso->id)
                             ->withErrors(['error' => 'Este período está cerrado. No puedes acceder a la edición.']);
        }

        // 1 Cargamos el curso y sus alumnos
        $curso->load('alumnos');

        // 2 Buscamos el tipo de actividad de este grupo de notas para mostrarlo
        // (Tomamos el primero que encontremos, ya que todos tienen el mismo tipo)
        $registroEjemplo = Calificacion::where('curso_id', $curso->id)
                                    ->where('activity_name', $activity_name)
                                    ->first();
                                    
        $tipoActividad = $registroEjemplo->tipoActividad; 

        // 3 Buscamos TODAS las calificaciones de esta actividad
        //    y las convertimos en un array simple: [ 'id_alumno' => 'nota' ]
        //    Esto facilita rellenar los inputs en la vista.
        $calificaciones = Calificacion::where('curso_id', $curso->id)
                                    ->where('activity_name', $activity_name)
                                    ->pluck('score', 'alumno_id');

        // 4 Pasamos todos los datos a la vista de edición
        return view('docente.calificaciones_edit', [
            'curso' => $curso,
            'activity_name' => $activity_name,
            'tipoActividad' => $tipoActividad,
            'calificaciones' => $calificaciones,
        ]);
    }

    /**
     * Actualiza las calificaciones de una actividad existente.
     * (PUT /docente/cursos/{curso}/calificaciones/{activity_name})
     */
    public function updateActivity(Request $request, Curso $curso, $activity_name)
    {

        // SEGURIDAD: verificar que este curso pertenezca al docente logueado
        if ($curso->docente_id !== auth()->user()->docente->id) {
            abort(403, 'Acceso denegado. Este curso no te pertenece.');
        }

        // bloqueo de edicion en caso un periodo este cerrado
        if ($curso->is_calificaciones_closed) {
            return back()->withErrors(['error' => 'Este período ya está cerrado. No se pueden modificar las notas.']);
        }

        // 1 Validamos solo las notas (no permitimos cambiar nombre ni porcentaje aquí por simplicidad)
        $request->validate([
            'scores' => 'required|array',
            'scores.*' => 'required|numeric|min:0|max:10',
        ]);

        // 2 Iteramos sobre las nuevas notas recibidas
        foreach ($request->scores as $alumnoId => $score) {
            
            // 3 Buscamos la nota existente y actualizamos su valor ('score')
            Calificacion::where('curso_id', $curso->id)
                        ->where('activity_name', $activity_name)
                        ->where('alumno_id', $alumnoId)
                        ->update(['score' => $score]);
        }

        // 4 Redirigimos con mensaje de éxito
        return redirect()->route('docente.cursos.calificaciones', $curso->id)
                         ->with('success', '¡Calificaciones actualizadas exitosamente!');
    }

    /**
     * Elimina todas las calificaciones asociadas a una actividad.
     * (DELETE /docente/cursos/{curso}/calificaciones/{activity_name})
     */
    public function destroyActivity(Curso $curso, $activity_name)
    {   

        // SEGURIDAD: verificar que este curso pertenezca al docente logueado
        if ($curso->docente_id !== auth()->user()->docente->id) {
            abort(403, 'Acceso denegado. Este curso no te pertenece.');
        }

        // bloqueo de edicion en caso un periodo este cerrado
        if ($curso->is_calificaciones_closed) {
            return back()->withErrors(['error' => 'Este período ya está cerrado. No se pueden modificar las notas.']);
        }

        // 1 Borramos todos los registros que coincidan con el curso y el nombre de la actividad
        Calificacion::where('curso_id', $curso->id)
                    ->where('activity_name', $activity_name)
                    ->delete();

        // 2 Redirigimos con mensaje de éxito
        return back()->with('success', '¡Actividad y sus notas eliminadas exitosamente!');
    }
}