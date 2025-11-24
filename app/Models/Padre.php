<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Padre extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'first_name', 'last_name', 'phone'];

    // relacion con Usuario (Login)
    public function user() {
        return $this->belongsTo(User::class);
    }

    // relacion con Hijos (Muchos a Muchos)
    public function alumnos() {
        return $this->belongsToMany(Alumno::class, 'alumno_padre');
    }
}
