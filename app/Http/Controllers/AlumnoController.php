<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alumno;  // importamos el modelo alumno
use App\Models\User;     // importamos el modelo user
use App\Models\Grado;    // importamos el modelo grado
use App\Models\Seccion;  // importamos el modelo seccion
use Illuminate\Support\Facades\Hash; // para encriptar

class AlumnoController extends Controller
{
    /**
     * muestra la lista de alumnos (read)
     */
    public function index()
    {
        // buscamos todos los alumnos y traemos sus relaciones
        $alumnos = Alumno::with('user', 'grado', 'seccion')->get();

        // pasamos la variable $alumnos a la vista
        return view('alumnos.index', ['alumnos' => $alumnos]);
    }

    /**
     * muestra el formulario para crear un nuevo alumno (create)
     */
    public function create()
    {
        // necesitamos obtener todos los grados y secciones
        // para poder mostrarlos en los <select> del formulario
        $grados = Grado::all();
        $secciones = Seccion::all();

        return view('alumnos.create', [
            'grados' => $grados,
            'secciones' => $secciones
        ]);
    }

    /**
     * guarda el nuevo alumno en la base de datos. (store)
     */
    public function store(Request $request)
    {
        // validamos los datos del formulario
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'student_id_code' => 'required|string|max:255|unique:alumnos', // unico en la tabla alumnos
            'grado_id' => 'required|exists:grados,id', // debe existir en la tabla grados
            'seccion_id' => 'required|exists:seccions,id', // debe existir en la tabla secciones
            'email' => 'required|string|email|max:255|unique:users', // unico en la tabla users
            'password' => 'required|string|min:8',
        ]);

        // creamos el registro en la tabla 'users'
        $user = User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'estudiante', // asignamos el rol de estudiante
        ]);

        // creamos el registro en la tabla 'alumnos'
        //    y lo asociamos con el 'user_id' que acabamos de crear
        Alumno::create([
            'user_id' => $user->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'student_id_code' => $request->student_id_code,
            'grado_id' => $request->grado_id,
            'seccion_id' => $request->seccion_id,
        ]);

        // redirigimos al usuario a la lista de alumnos
        // con un mensaje de exito
        return redirect()->route('alumnos.index')->with('success', '¡Alumno creado exitosamente!');
    }

    /**
     * muestra el formulario para editar un alumno existente
     */
    public function edit(Alumno $alumno)
    {
        // 'alumno $alumno' es gracias al route model binding de laravel
        // laravel automaticamente busca al alumno por el id que
        // pasamos en la url (ej. /alumnos/1/editar)

        // al igual que en 'create', necesitamos la lista de grados y secciones
        $grados = Grado::all();
        $secciones = Seccion::all();

        // retornamos la vista edit y le pasamos los 3 datos
        return view('alumnos.edit', [
            'alumno' => $alumno,
            'grados' => $grados,
            'secciones' => $secciones
        ]);
    }

    /**
     * actualiza el alumno en la base de datos.
     */
    public function update(Request $request, Alumno $alumno)
    {
        // validamos los datos
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            // regla 'unique' especial: debe ser unico,
            // ignorando el id de este alumno
            'student_id_code' => 'required|string|max:255|unique:alumnos,student_id_code,' . $alumno->id,
            'grado_id' => 'required|exists:grados,id',
            'seccion_id' => 'required|exists:seccions,id',
            // regla 'unique' para el email del usuario asociado
            'email' => 'required|string|email|max:255|unique:users,email,' . $alumno->user->id,
            'password' => 'nullable|string|min:8', // la clave es opcional
        ]);

        // actualizamos el registro 'user'
        // buscamos al usuario que le pertenece al alumno
        $user = $alumno->user;
        $user->name = $request->first_name . ' ' . $request->last_name;
        $user->email = $request->email;

        // (solo actualizamos la clave si el campo 'password' no esta vacio)
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save(); // (guardamos los cambios del usuario)

        // actualizamos el registro 'alumno'
        $alumno->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'student_id_code' => $request->student_id_code,
            'grado_id' => $request->grado_id,
            'seccion_id' => $request->seccion_id,
        ]);

        // redirigimos a la lista con un mensaje
        return redirect()->route('alumnos.index')->with('success', '¡Alumno actualizado exitosamente!');
    }

    /**
     * elimina logicamente al alumno y su usuario
     */
    public function destroy(Alumno $alumno)
    {
        // encontramos al usuario que le pertenece a este alumno
        $user = $alumno->user;

        // eliminamos (soft delete) el registro del alumno
        $alumno->delete();

        // eliminamos (soft delete) el registro del usuario
        // esto es importante para que no pueda iniciar sesion
        if ($user) {
            $user->delete();
        }

        // redirigimos a la lista con un mensaje de exito
        return redirect()->route('alumnos.index')->with('success', '¡Alumno eliminado exitosamente!');
    }

}