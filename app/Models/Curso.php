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

    /**
     * relacin: un curso tiene muchas calificaciones registradas
     */
    public function calificaciones()
    {
        return $this->hasMany(Calificacion::class);
    }

    /**
     * funcion personalizada para calcular el promedio de un alumno en este curso
     * retorna el puntaje acumulado basado en porcentajes
     */
    public function calcularPromedio($alumno_id)
    {
        // 1 buscamos las notas de este alumno en este curso
        $notas = $this->calificaciones()
                      ->where('alumno_id', $alumno_id)
                      ->get();

        $promedioAcumulado = 0;

        // 2 recorremos cada nota y aplicamos la formula
        foreach ($notas as $nota) {
            // tomamos el porcentaje directamente de la nota (tabla calificacions)
            $porcentaje = $nota->percentage; 

            // calculamos los puntos ganados: nota * (porcentaje / 100)
            // Ej: 10 * 0.35 = 3.5 puntos
            if ($porcentaje > 0) {
                $puntos = $nota->score * ($porcentaje / 100);
                $promedioAcumulado += $puntos;
            }
        }

        return number_format($promedioAcumulado, 2);
    }


    /**
     * un curso tiene muchas asistencias registradas
     */
    public function asistencias()
    {
        return $this->hasMany(Asistencia::class);
    }
    
    
}
