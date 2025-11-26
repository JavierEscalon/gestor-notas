<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Bitacora;
use Illuminate\Support\Facades\Hash;

class SysAdminController extends Controller
{
    // 1 Dashboard y Lista de Administradores Académicos
    public function index()
    {
        // buscamos solo a los usuarios con rol 'admin' (Administradores Academicos)
        $admins = User::where('role', 'admin')->get();
        return view('sysadmin.index', ['admins' => $admins]);
    }

    // 2 ver la BitAcora (Log de Transacciones)
    public function bitacora()
    {
        // obtenemos los ultimos 100 eventos, ordenados del mas reciente al mas antiguo
        $eventos = Bitacora::with('user')->orderBy('created_at', 'desc')->take(100)->get();
        return view('sysadmin.bitacora', ['eventos' => $eventos]);
    }

    // 3 crear un nuevo Administrador Academico
    public function storeAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin', // Rol específico de Gestión Académica
        ]);

        // registramos el evento
        Bitacora::registrar('CREAR_ADMIN', 'Se creó un nuevo administrador académico: ' . $request->email);

        return back()->with('success', 'Administrador académico creado correctamente.');
    }

    // 4 eliminar un Administrador
    public function destroyAdmin(User $user)
    {
        if ($user->role !== 'admin') {
            return back()->with('error', 'Solo puedes eliminar administradores académicos.');
        }

        $email = $user->email;
        $user->delete(); // usamos SoftDeletes si esta configurado, o delete normal

        Bitacora::registrar('ELIMINAR_ADMIN', 'Se eliminó al administrador: ' . $email);

        return back()->with('success', 'Usuario eliminado.');
    }

    // 5 mostrar formulario de edicion
    public function editAdmin(User $user)
    {
        if ($user->role !== 'admin') {
            return redirect()->route('sysadmin.dashboard')->with('error', 'Solo puedes editar administradores académicos.');
        }
        return view('sysadmin.edit', ['admin' => $user]);
    }

    // 6 guardar cambios del admin
    public function updateAdmin(Request $request, User $user)
    {
        if ($user->role !== 'admin') {
            return back()->with('error', 'Acción no permitida.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id, // ignorar el email actual
            'password' => 'nullable|min:8', // opcional
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        Bitacora::registrar('EDITAR_ADMIN', 'Se editaron los datos del administrador: ' . $user->email);

        return redirect()->route('sysadmin.dashboard')->with('success', 'Administrador actualizado correctamente.');
    }

}