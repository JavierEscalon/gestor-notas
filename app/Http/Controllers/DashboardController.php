<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // lo usaremos para saber que rol es

class DashboardController extends Controller
{
    /**
     * muestra el dashboard principal de la aplicacion.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // por ahora, solo mostramos una vista simple.
        // mas adelante, aqui pondremos logica para
        // mostrar diferentes cosas segun el rol del usuario.
        
        // $userRole = Auth::user()->role;
        // if ($userRole == 'admin') { ... }

        return view('dashboard');
    }
}