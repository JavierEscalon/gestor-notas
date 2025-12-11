<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calificacion extends Model
{
    use HasFactory;

    // (le decimos a laravel que nuestra tabla se llama 'calificacions')
    protected $table = 'calificacions';

    protected $fillable = [
        'alumno_id',
        'curso_id',
        'tipo_actividad_id',
        'periodo_id',
        'score',
        'activity_name',
        'percentage',
    ];

    /**
     * Relación: Una calificación pertenece a un Alumno.
     * ESTA ES LA FUNCIÓN QUE FALTABA
     */
    public function alumno()
    {
        return $this->belongsTo(Alumno::class);
    }

    /**
     * Relación: Una calificación pertenece a un Curso.
     * (Es buena práctica tenerla también)
     */
    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    /**
     * Relación: Una calificación tiene un Tipo de Actividad.
     */
    public function tipoActividad()
    {
        return $this->belongsTo(TipoActividad::class);
    }
}