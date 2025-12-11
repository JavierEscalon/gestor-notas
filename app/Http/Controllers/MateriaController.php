<?php

namespace App\Http\Controllers;

use App\Models\Materia;
use Illuminate\Http\Request;

class MateriaController extends Controller
{
    /**
     * muestra la lista de materias. (read)
     */
    public function index(Request $request)
    {
        $query = Materia::query();

        // Filtro de búsqueda por nombre
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', "%{$request->search}%")
                     ->orWhere('description', 'like', "%{$request->search}%");
        }

        $materias = $query->orderBy('name')->paginate(10)->withQueryString();

        return view('materias.index', compact('materias'));
    }

    /**
     * muestra el formulario para crear una nueva materia. (create)
     */
    public function create()
    {
        return view('materias.create');
    }

    /**
     * guarda la nueva materia en la base de datos. (store)
     */
    public function store(Request $request)
    {
        // validamos los datos
        $request->validate([
            'name' => 'required|string|max:255|unique:materias', // (el nombre debe ser unico)
            'description' => 'nullable|string',
        ]);

        // creamos la materia con los datos validados
        Materia::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        // redirigimos a la lista con un mensaje de exito
        return redirect()->route('materias.index')->with('success', '¡Materia creada exitosamente!');
    }

    /**
     * Display the specified resource.
     * (no la usaremos por ahora)
     */
    public function show(Materia $materia)
    {
        //
    }

    /**
     * muestra el formulario para editar la materia.
     */
    public function edit(Materia $materia)
    {
        // laravel encuentra la materia automaticamente por el id
        return view('materias.edit', ['materia' => $materia]);
    }

    /**
     * actualiza la materia en la base de datos.
     */
    public function update(Request $request, Materia $materia)
    {
        // validamos los datos
        $request->validate([
            // (el nombre debe ser unico, ignorando el id actual)
            'name' => 'required|string|max:255|unique:materias,name,' . $materia->id,
            'description' => 'nullable|string',
        ]);

        // actualizamos la materia
        $materia->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        // redirigimos a la lista con un mensaje
        return redirect()->route('materias.index')->with('success', '¡Materia actualizada exitosamente!');
    }

    /**
     * elimina logicamente la materia
     */
    public function destroy(Materia $materia)
    {
        // eliminamos (soft delete) la materia
        $materia->delete();

        // redirigimos a la lista con un mensaje
        return redirect()->route('materias.index')->with('success', '¡Materia eliminada exitosamente!');
    }
}
