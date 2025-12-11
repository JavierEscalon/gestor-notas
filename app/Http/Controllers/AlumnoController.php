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
    public function index(Request $request)
    {
        // 1 preparamos la consulta base con las relaciones necesarias
        // usamos 'user', 'grado' y 'seccion' para evitar problemas de carga
        $query = Alumno::with(['grado', 'seccion', 'user']);

        // 2. filtro por buscador (nombre, apellido o carnet)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('student_id_code', 'like', "%{$search}%");
            });
        }

        // 3 filtro por grado
        if ($request->has('grado_id') && $request->grado_id != '') {
            $query->where('grado_id', $request->grado_id);
        }

        // 4 ejecutamos la consulta con paginacion
        $alumnos = $query->orderBy('last_name')->paginate(10)->withQueryString();
        
        // 5 IMPORTANTE: obtenemos la lista de grados para el filtro
        $grados = \App\Models\Grado::all(); 

        // 6 retornamos la vista pasando AMBAS variables: alumnos y grados
        return view('alumnos.index', compact('alumnos', 'grados'));
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
        //1 validamos los datos del formulario
        $request->validate([
            //datos del usuario
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,emial',
            'password' => 'required|string|min:8',
            
            //datos academicos
            'student_id_code' => 'required|unique:alumnos,strudent_id_code',
            'grado_id' => 'required|exists:grados,id',
            'seccion_id' => 'required|exists:seccions,id',

            //datos personales (expediente)
            'birth_date' => 'required|date',
            'gender' => 'required|in:M,F',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'medical_conditions' => 'nullable|string|max:255',
        ]);

        //2 creamos el registro en la tabla 'users'
        $user = User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'estudiante', // asignamos el rol de estudiante
        ]);

        //3. creamos el registro en la tabla 'alumnos'
        // y lo asociamos con el 'user_id' que acabamos de crear
        Alumno::create([
            'user_id' => $user->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'student_id_code' => $request->student_id_code,
            'grado_id' => $request->grado_id,
            'seccion_id' => $request->seccion_id,
            'birth_date' => $request->birth_date,
            'gender' => $request->gender,
            'phone' => $request->phone,
            'address' => $request->address,
            'emergency_contact_name' => $request->emergency_contact_name,
            'emergency_contact_phone' => $request->emergency_contact_phone,
            'medical_conditions' => $request->medical_conditions,
            'status' => 'activo',
        ]);

        // redirigimos al usuario a la lista de alumnos
        // con un mensaje de exito
        return redirect()->route('alumnos.index')->with('success', '¡Alumno matriculado exitosamente!');
    }

    /**
     * muestra el formulario para editar un alumno existente
     */
    public function edit(Alumno $alumno)
    {
        // Necesitamos los catálogos para llenar los selects
        $grados = \App\Models\Grado::all();
        $secciones = \App\Models\Seccion::all();

        return view('alumnos.edit', compact('alumno', 'grados', 'secciones'));
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
            'email' => 'required|string|email|max:255|unique:users,email,' . $alumno->user->id, // ignorar propio email
            
            'student_id_code' => 'required|unique:alumnos,student_id_code,' . $alumno->id, // Ignorar propio carnet
            'grado_id' => 'required|exists:grados,id',
            'seccion_id' => 'required|exists:seccions,id',

            'birth_date' => 'required|date',
            'gender' => 'required|in:M,F',
            'status' => 'required|in:activo,inactivo,retirado', // solo en editar se puede cambiar estado
            
        ]);

        // actualizar Usuario
        $alumno->user->update([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
        ]);
        
        if ($request->filled('password')) {
            $alumno->user->update(['password' => Hash::make($request->password)]);
        }

        // Actualizar alumno (todos los campos)
        $alumno->update($request->except(['email', 'password'])); // el resto va directo al modelo alumno

        return redirect()->route('alumnos.index')->with('success', 'Expediente actualizado correctamente.');
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