<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PeriodoEscolar extends Model
{
    use HasFactory, SoftDeletes;

    // laravel es inteligente, pero el nombre de nuestra
    // tabla 'periodos_escolar' no es el plural
    // estandar de 'periodoescolar', asi que le decimos cual es
    protected $table = 'periodos_escolar';

    /**
     * los atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'is_active',
    ];

}
