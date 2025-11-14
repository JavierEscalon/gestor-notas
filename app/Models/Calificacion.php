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
    ];

    public function tipoActividad()
    {
        return $this->belongsTo(TipoActividad::class);
    }
}