<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    /**
     * creamos una instancia del controlador.
     */
    public function __construct()
    {   
        // solo los invitados pueden ver el login
        $this->middleware('guest')->except('logout');
    }

    /**
     * muestra el formulario de login.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * maneja el intento de inicio de sesion
     */
    public function login(Request $request)
    {
        // validamos los datos
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // intentamos iniciar sesion
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate(); // regeneramos la sesion

            // logica de redireccion al dashboard segun rol

            $role = Auth::user()->role;

            switch ($role) {
                case 'admin':
                    return redirect()->intended(route('dashboard'));
                case 'docente':
                    return redirect()->intended(route('docente.dashboard'));
                case 'estudiante':
                    return redirect()->intended(route('estudiante.dashboard'));
                case 'padre':
                    return redirect()->intended(route('padre.dashboard'));
                default:
                    // si no tiene rol, lo mandamos a la raiz
                    return redirect()->intended('/'); 
            }
        }

        // si fallamos
        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    /**
     * cierra la sesion del usuario.
     * esta funcion la llamamos desde el boton 'cerrar sesion'
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/'); // lo enviamos a la raiz del sitio
    }
}