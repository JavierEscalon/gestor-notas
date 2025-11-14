<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscripcion extends Model
{
    use HasFactory;

    // le decimos a laravel que nuestra tabla se llama 'inscripcions'
    protected $table = 'inscripcions';
}