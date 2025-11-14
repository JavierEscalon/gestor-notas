<?php

namespace App\Http\Controllers\Docente; // el namespace es 'Docente'

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // para saber quien es el usuario
use App\Models\Curso; // para buscar los cursos

class DashboardController extends Controller
{
    public function index()
    {
        // obtenemos el usuario docente que ha iniciado sesion
        $docente = Auth::user()->docente; // asumimos que la relacion existe

        // si no es un docente, es admin?, lo mandamos al dashboard admin
        if (!$docente) {
            return redirect()->route('dashboard');
        }

        // buscamos los cursos que estan asignados a este docente
        $cursos = Curso::where('docente_id', $docente->id)
                        ->with('materia', 'grado', 'seccion', 'periodo') // traemos las relaciones
                        ->get();

        // mostramos la vista del dashboard del docente
        return view('docente.dashboard', ['cursos' => $cursos]);
    }
}