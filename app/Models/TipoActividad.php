<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoActividad extends Model
{
    use HasFactory, SoftDeletes;

    // le decimos a laravel el nombre de la tabla
    protected $table = 'tipo_actividads';

    protected $fillable = [
        'name',
        'default_percentage',
    ];
}