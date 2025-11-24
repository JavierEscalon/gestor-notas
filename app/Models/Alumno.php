<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Alumno extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * los atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'user_id',
        'grado_id',
        'seccion_id',
        'first_name',
        'last_name',
        'student_id_code',
    ];

    /**
     * un alumno le "pertenece a" un usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * un alumno le "pertenece a" un grado
     */
    public function grado()
    {
        return $this->belongsTo(Grado::class);
    }

    /**
     * un alumno le "pertenece a" una seccion
     */
    public function seccion()
    {
        return $this->belongsTo(Seccion::class);
    }

    /**
     * un alumno puede pertenecer a muchos cursos
     */
    public function cursos()
    {
        // el modelo a conectar es 'curso'
        // la tabla pivote es 'inscripcions')
        return $this->belongsToMany(Curso::class, 'inscripcions');
    }

    /**
     * un alumno tiene padres/tutores.
     */
    public function padres() {
        return $this->belongsToMany(Padre::class, 'alumno_padre');
    }
}
