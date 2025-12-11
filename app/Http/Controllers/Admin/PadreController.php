<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Padre;
use App\Models\User;
use App\Models\Alumno;
use Illuminate\Support\Facades\Hash;

class PadreController extends Controller
{
    /**
     * muestra la lista de padres.
     */
    public function index(Request $request)
    {
        // 1 preparamos la consulta cargando relaciones
        $query = Padre::with(['user', 'alumnos']);

        // 2 aplicamos el filtro si el usuario escribio algo en el buscador
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhereHas('user', function($qUser) use ($search) {
                        $qUser->where('email', 'like', "%{$search}%");
                    });
            });
        }

        // 3 usamos paginate en lugar de get()
        $padres = $query->paginate(10)->withQueryString();

        return view('admin.padres.index', ['padres' => $padres]);
    }

    /**
     * muestra el formulario para crear.
     */
    public function create()
    {
        return view('admin.padres.create');
    }

    /**
     * guarda el nuevo padre y su usuario.
     */
    public function store(Request $request)
    {
        // 1 validar
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);

        // 2 crear Usuario (Login)
        $user = User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'padre',
        ]);

        // 3 crear Perfil Padre
        $padre = Padre::create([
            'user_id' => $user->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
        ]);

        // 4 redirigir a la vista de asignar hijos
        return redirect()->route('admin.padres.hijos', $padre->id)
                            ->with('success', 'Padre creado. Ahora selecciona sus hijos.');
    }

    /**
     * muestra el formulario para editar.
     */
    public function edit(Padre $padre)
    {
        return view('admin.padres.edit', ['padre' => $padre]);
    }

    /**
     * actualiza el padre.
     */
    public function update(Request $request, Padre $padre)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'required|email|unique:users,email,' . $padre->user_id,
        ]);

        // actualizar usuario
        $padre->user->update([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
        ]);
        
        if ($request->filled('password')) {
            $padre->user->update(['password' => Hash::make($request->password)]);
        }

        // actualizar perfil
        $padre->update($request->only(['first_name', 'last_name', 'phone']));

        return redirect()->route('admin.padres.index')->with('success', 'Padre actualizado.');
    }

    /**
     * elimina (soft delete) al padre.
     */
    public function destroy(Padre $padre)
    {
        // eliminamos usuario y padre
        if($padre->user) {
            $padre->user->delete();
        }
        $padre->delete();
        return back()->with('success', 'Padre eliminado.');
    }

    // --- GESTIÓN DE HIJOS ---

    /**
     * muestra la vista para asignar hijos al padre.
     */
    public function hijos(Padre $padre)
    {
        // obtenemos los IDs de los hijos que ESTE padre ya tiene
        $hijosIds = $padre->alumnos->pluck('id')->toArray();

        // buscamos TODOS los alumnos, pero le pedimos a Laravel que
        // cuente cuantos padres tiene cada uno (crea un campo 'padres_count')
        $alumnos = Alumno::withCount('padres')
                         ->orderBy('last_name')
                         ->get();

        return view('admin.padres.hijos', [
            'padre' => $padre,
            'alumnos' => $alumnos,
            'hijosIds' => $hijosIds
        ]);
    }

    /**
     * guarda la asignación de hijos.
     */
    public function updateHijos(Request $request, Padre $padre)
    {
        // sincronizamos la tabla pivote (agrega los nuevos, quita los desmarcados)
        $padre->alumnos()->sync($request->alumnos_ids ?? []);

        return redirect()->route('admin.padres.index')->with('success', 'Hijos asignados correctamente.');
    }
}