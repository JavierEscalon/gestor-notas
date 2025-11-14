<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Materia extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * los atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'name',
        'description',
    ];
}
