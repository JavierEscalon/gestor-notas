<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth; 

class Bitacora extends Model
{
    use HasFactory;

    // nombre de la tabla
    protected $table = 'bitacoras';

    protected $fillable = ['user_id', 'action', 'description', 'created_at'];
    
    //! la tabla solo tiene 'created_at', no 'updated_at'.
    // por eso se desactivactivan los timestamps automaticos de Laravel
    public $timestamps = false; 

    // relacion: un registro de bitacora pertenece a un usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * funcion estatica para registrar un evento facilmente desde cualquier controlador
     * uso: Bitacora::registrar('LOGIN', 'el usuario inicio sesion');
     */
    public static function registrar($accion, $descripcion)
    {
        self::create([
            'user_id' => Auth::id(), // ID del usuario actual (o null si no hay sesión)
            'action' => $accion,
            'description' => $descripcion,
            'created_at' => now(), // guardamos la fecha/hora actual
        ]);
    }

    /**
     * los atributos que deben ser convertidos a tipos nativos.
     * (Esto le dice a Laravel que trate a 'created_at' como fecha Carbon)
     */
    protected $casts = [
        'created_at' => 'datetime',
    ];

}