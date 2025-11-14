<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Curso extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * los atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'materia_id',
        'docente_id',
        'grado_id',
        'seccion_id',
        'periodo_id',
    ];

    // -- (relaciones) --
    // (un curso 'pertenece a' todos estos modelos)

    public function materia()
    {
        return $this->belongsTo(Materia::class);
    }

    public function docente()
    {
        return $this->belongsTo(Docente::class);
    }

    public function grado()
    {
        return $this->belongsTo(Grado::class);
    }

    public function seccion()
    {
        return $this->belongsTo(Seccion::class);
    }

    public function periodo()
    {
        // le decimos a laravel el nombre de la llave foranea
        // porque periodo_id no es el estandar de periodoescolar
        return $this->belongsTo(PeriodoEscolar::class, 'periodo_id');
    }

    /**
     * un curso puede tener muchos alumnos
     */
    public function alumnos()
    {
        // laravel indetifica que
        // el modelo a conectar es alumno
        // la tabla pivote es inscripcions
        return $this->belongsToMany(Alumno::class, 'inscripcions');
    }
    
}
