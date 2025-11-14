<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;     // necesitamos el modelo user
use App\Models\Docente;  // necesitamos el modelo docente
use Illuminate\Support\Facades\Hash; // para encriptar la clave

class DocenteController extends Controller
{
    /**
     * muestra la lista de docentes. (read)
     */
    public function index()
    {
        // 1. buscamos todos los docentes en la base de datos
        //    y usamos 'with' para traer tambien la info de su 'user'
        $docentes = Docente::with('user')->get();

        // 2. le pasamos la variable $docentes a la vista
        return view('docentes.index', ['docentes' => $docentes]);
    }

    /**
     * muestra el formulario para crear un nuevo docente. (create)
     */
    public function create()
    {
        return view('docentes.create');
    }


    /**
     * guarda el nuevo docente en la base de datos. (store)
     */
    public function store(Request $request)
    {
        // validamos los datos del formulario
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'specialty' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        // creamos el registro en la tabla 'users'
        $user = User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'docente', // asignamos el rol
        ]);

        // creamos el registro en la tabla 'docentes'
        // y lo asociamos con el usuario que acabamos de crear
        Docente::create([
            'user_id' => $user->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'specialty' => $request->specialty,
            'phone' => $request->phone,
        ]);

        // redirigimos al usuario de vuelta a la lista de docentes
        // con un mensaje de exito
        return redirect()->route('docentes.index')->with('success', 'docente creado exitosamente.');
    }

    /**
     * muestra el formulario para editar un docente existente.
     */
    public function edit(Docente $docente)
    {
        // laravel encuentra al docente automaticamente por el id
        // solo necesitamos pasar el docente a la vista
        return view('docentes.edit', ['docente' => $docente]);
    }

    /**
     * actualiza el docente en la base de datos.
     */
    public function update(Request $request, Docente $docente)
    {
        // validamos los datos
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'specialty' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            // validamos que el email sea unico, ignorando al usuario actual
            'email' => 'required|string|email|max:255|unique:users,email,' . $docente->user->id,
            'password' => 'nullable|string|min:8', // (la clave es opcional)
        ]);

        // actualizamos el registro 'user'
        $user = $docente->user;
        $user->name = $request->first_name . ' ' . $request->last_name;
        $user->email = $request->email;
        
        // solo actualizamos la clave si el campo no esta vacio
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save(); // guardamos los cambios del usuario

        // actualizamos el registro 'docente'
        $docente->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'specialty' => $request->specialty,
            'phone' => $request->phone,
        ]);

        // redirigimos a la lista con un mensaje
        return redirect()->route('docentes.index')->with('success', '¡Docente actualizado exitosamente!');
    }

    /**
     * elimina logicamente al docente y su usuario
     */
    public function destroy(Docente $docente)
    {
        // encontramos al usuario que le pertenece a este docente
        $user = $docente->user;

        // eliminamos (soft delete) el registro del docente
        $docente->delete();
        
        // eliminamos (soft delete) el registro del usuario
        if ($user) {
            $user->delete();
        }

        // redirigimos a la lista con un mensaje de exito
        return redirect()->route('docentes.index')->with('success', '¡Docente eliminado exitosamente!');
    }
}