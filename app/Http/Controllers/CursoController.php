<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Materia;
use App\Models\Docente;
use App\Models\Grado;
use App\Models\Seccion;
use App\Models\PeriodoEscolar;
use App\Models\Alumno;
use Illuminate\Http\Request;

class CursoController extends Controller
{
    /**
     * muestra la lista de cursos.
     */
    public function index()
    {
        // (buscamos todos los cursos y sus relaciones)
        $cursos = Curso::with('materia', 'docente', 'grado', 'seccion', 'periodo')->get();
        
        return view('cursos.index', ['cursos' => $cursos]);
    }

    /**
     * muestra el formulario para crear un nuevo curso.
     */
    public function create()
    {
        // (necesitamos todos los catalogos para los <select>)
        $materias = Materia::all();
        $docentes = Docente::all();
        $grados = Grado::all();
        $secciones = Seccion::all();
        $periodos = PeriodoEscolar::all();

        // (le pasamos los 5 catalogos a la vista)
        return view('cursos.create', [
            'materias' => $materias,
            'docentes' => $docentes,
            'grados' => $grados,
            'secciones' => $secciones,
            'periodos' => $periodos,
        ]);
    }

    /**
     * guarda el nuevo curso en la base de datos.
     */
    public function store(Request $request)
    {
        // validamos los datos
        $request->validate([
            'materia_id' => 'required|exists:materias,id',
            'docente_id' => 'required|exists:docentes,id',
            'grado_id' => 'required|exists:grados,id',
            'seccion_id' => 'required|exists:seccions,id',
            'periodo_id' => 'required|exists:periodos_escolar,id',
        ]);

        // (opcional pero recomendado)
        //    validamos que este curso no exista ya
        $existe = Curso::where('materia_id', $request->materia_id)
                        ->where('docente_id', $request->docente_id)
                        ->where('grado_id', $request->grado_id)
                        ->where('seccion_id', $request->seccion_id)
                        ->where('periodo_id', $request->periodo_id)
                        ->exists();

        if ($existe) {
            // (si ya existe, regresamos con un error)
            return back()->withErrors([
                'materia_id' => 'Ya existe un curso con esta combinación exacta.'
            ])->withInput();
        }

        // creamos el curso
        Curso::create([
            'materia_id' => $request->materia_id,
            'docente_id' => $request->docente_id,
            'grado_id' => $request->grado_id,
            'seccion_id' => $request->seccion_id,
            'periodo_id' => $request->periodo_id,
        ]);

        // redirigimos a la lista con un mensaje de exito
                return redirect()->route('cursos.index')->with('success', '¡Curso creado exitosamente!');
    }

        
    /**
     * muestra el formulario para editar el curso.
     */
    public function edit(Curso $curso)
    {
        // (laravel encuentra el curso automaticamente)

        // (tambien necesitamos todos los catalogos para los <select>)
        $materias = Materia::all();
        $docentes = Docente::all();
        $grados = Grado::all();
        $secciones = Seccion::all();
        $periodos = PeriodoEscolar::all();

        return view('cursos.edit', [
            'curso' => $curso,
            'materias' => $materias,
            'docentes' => $docentes,
            'grados' => $grados,
            'secciones' => $secciones,
            'periodos' => $periodos,
        ]);
    }

        /**
         * actualiza el curso en la base de datos.
         */
        public function update(Request $request, Curso $curso)
        {
            // 1. validamos los datos
            $request->validate([
                'materia_id' => 'required|exists:materias,id',
                'docente_id' => 'required|exists:docentes,id',
                'grado_id' => 'required|exists:grados,id',
                'seccion_id' => 'required|exists:seccions,id',
                'periodo_id' => 'required|exists:periodos_escolar,id',
            ]);

            // opcional, validamos que la nueva combinacion no exista
            //    (ignorando el id del curso actual)
            $existe = Curso::where('materia_id', $request->materia_id)
                            ->where('docente_id', $request->docente_id)
                            ->where('grado_id', $request->grado_id)
                            ->where('seccion_id', $request->seccion_id)
                            ->where('periodo_id', $request->periodo_id)
                            ->where('id', '!=', $curso->id) // (ignora este curso)
                            ->exists();

            if ($existe) {
                return back()->withErrors([
                    'materia_id' => 'Ya existe un curso con esta combinación exacta.'
                ])->withInput();
            }

            // actualizamos el curso
            $curso->update($request->all());

            // redirigimos a la lista con un mensaje
            return redirect()->route('cursos.index')->with('success', '¡Curso actualizado exitosamente!');
        }

        /**
         * elimina (logicamente) el curso.
         */
        public function destroy(Curso $curso)
        {
            // eliminamos (soft delete) el curso
            $curso->delete();

            // redirigimos a la lista con un mensaje
            return redirect()->route('cursos.index')->with('success', '¡Curso eliminado exitosamente!');
        }

    /**
     * muestra la pagina de gestion de inscripcion.
     */
    public function show(Curso $curso)
    {
        // obtenemos los ids de los alumnos que ya estan
        // inscritos en este curso
        $alumnosInscritosIds = $curso->alumnos->pluck('id');

        // buscamos a los alumnos que son elegibles para este curso
        // que coinciden en grado y seccion
        // pero que no estan en la lista de 'inscritos'
        $alumnosDisponibles = Alumno::where('grado_id', $curso->grado_id)
                                    ->where('seccion_id', $curso->seccion_id)
                                    ->whereNotIn('id', $alumnosInscritosIds)
                                    ->get();

        // pasamos el curso, los inscritos y los disponibles a la vista
        return view('cursos.show', [
            'curso' => $curso,
            'alumnosInscritos' => $curso->alumnos, // pasamos la coleccion completa
            'alumnosDisponibles' => $alumnosDisponibles,
        ]);
    }

    /**
     * inscribe a los alumnos seleccionados en el curso
     */
    public function inscribirAlumnos(Request $request, Curso $curso)
    {
        // validamos que 'alumnos_ids' sea un array
        $request->validate([
            'alumnos_ids' => 'required|array',
        ]);

        // usamos el metodo 'attach' de laravel
        // para agregar las nuevas relaciones en la
        // tabla pivote 'inscripcions'
        $curso->alumnos()->attach($request->alumnos_ids);

        // redirigimos de vuelta a la pagina anterior
        // con un mensaje de exito
        return back()->with('success', '¡Alumnos inscritos exitosamente!');
    }

    /**
     * des-inscribe a un alumno del curso
     */
    public function quitarAlumno(Curso $curso, Alumno $alumno)
    {
        // usamos el metodo 'detach' de laravel
        // para borrar la relacion en la
        // tabla pivote 'inscripcions'
        $curso->alumnos()->detach($alumno->id);

        // redirigimos de vuelta con un mensaje
        return back()->with('success', '¡Alumno quitado del curso exitosamente!');
    }

    /**
     * permite al administrador reabrir un periodo cerrado.
     */
    public function reabrirPeriodo(Curso $curso)
    {
        // solo el admin debería poder hacer esto (aunque la ruta ya lo protege)
        $curso->update(['is_calificaciones_closed' => false]);

        return back()->with('success', '¡El período ha sido reabierto! El docente puede modificar notas nuevamente.');
    }

}