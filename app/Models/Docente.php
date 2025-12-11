<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Docente extends Model
{
    use HasFactory, SoftDeletes;
    /**
     * los atributos que se pueden asignar masivamente
     */
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'phone',
        'specialty',
    ];

    /**
     * un docente le "pertenece a" un usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación: Un Docente tiene muchos Cursos asignados.
     */
    public function cursos()
    {
        return $this->hasMany(Curso::class);
    }
}
