<?php

namespace App\Http\Controllers;

use App\Models\PeriodoEscolar;
use Illuminate\Http\Request;

class PeriodoEscolarController extends Controller
{
    /**
     * muestra la lista de periodos. (read)
     */
    public function index()
    {
        $periodos = PeriodoEscolar::all();
        return view('periodos.index', ['periodos' => $periodos]);
    }

    /**
     * muestra el formulario para crear un nuevo periodo. (create)
     */
    public function create()
    {
        return view('periodos.create');
    }

    /**
     * guarda el nuevo periodo en la base de datos. (store)
     */
    public function store(Request $request)
    {
        // validamos los datos
        $request->validate([
            'name' => 'required|string|max:255|unique:periodos_escolar', // unico en la tabla
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date', // (la fecha fin no puede ser antes de la inicio)
        ]);

        // creamos el periodo
        PeriodoEscolar::create([
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            // el checkbox 'is_active' solo envia '1' si esta marcado
            // usamos 'has' para convertir '1'/'null' a 'true'/'false'
            'is_active' => $request->has('is_active') 
        ]);

        // redirigimos a la lista con un mensaje de exito
        return redirect()->route('periodos.index')->with('success', '¡Período creado exitosamente!');
    }

    /**
     * Display the specified resource.
     * (no la usaremos por ahora)
     */
    public function show(PeriodoEscolar $periodo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * (la programaremos despues)
     */
    public function edit(PeriodoEscolar $periodo)
    {
        // laravel encuentra el periodo automaticamente
        return view('periodos.edit', ['periodo' => $periodo]);
    }

    /**
     * Update the specified resource in storage.
     * (la programaremos despues)
     */
    public function update(Request $request, PeriodoEscolar $periodo)
    {
        // validamos los datos
        $request->validate([
            'name' => 'required|string|max:255|unique:periodos_escolar,name,' . $periodo->id,
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // actualizamos el periodo
        $periodo->update([
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->has('is_active')
        ]);

        // redirigimos a la lista con un mensaje
        return redirect()->route('periodos.index')->with('success', '¡Período actualizado exitosamente!');
    }

    /**
     * Remove the specified resource from storage.
     * (la programaremos despues)
     */
    public function destroy(PeriodoEscolar $periodo)
    {
        // eliminamos (soft delete) el periodo
        $periodo->delete();

        // redirigimos a la lista con un mensaje
        return redirect()->route('periodos.index')->with('success', '¡Período eliminado exitosamente!');
    }
}
