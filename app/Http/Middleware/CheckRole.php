<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        // 1 verificamos si el usuario está logueado
        if (!auth()->check()) {
            return redirect('login');
        }

        // 2 verificamos si su rol COINCIDE con el requerido
        if (auth()->user()->role !== $role) {
            // si no coincide, abortamos con un error 403 (Prohibido)
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        return $next($request);
    }
}
