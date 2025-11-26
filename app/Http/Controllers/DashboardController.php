<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alumno;
use App\Models\Docente;
use App\Models\Curso;
use App\Models\Calificacion;

class DashboardController extends Controller
{

    public function index()
    {
        // 1 contadores para las tarjetas (KPIs)
        $totalAlumnos = Alumno::count();
        $totalDocentes = Docente::count();
        $totalCursos = Curso::count();
        $totalNotas = Calificacion::count();

        // 2 actividad Reciente: las últimas 5 notas registradas en el sistema
        // esto le da vida al dashboard, el admin ve que esta pasando ahora mismo
        $ultimasNotas = Calificacion::with(['alumno', 'curso.materia', 'curso.docente'])
                                    ->orderBy('created_at', 'desc')
                                    ->take(5)
                                    ->get();

        return view('dashboard', [
            'totalAlumnos' => $totalAlumnos,
            'totalDocentes' => $totalDocentes,
            'totalCursos' => $totalCursos,
            'totalNotas' => $totalNotas,
            'ultimasNotas' => $ultimasNotas
        ]);
    }
}