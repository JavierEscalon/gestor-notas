<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asistencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curso_id')->constrained('cursos');
            $table->foreignId('alumno_id')->constrained('alumnos');
            $table->date('fecha'); // fecha de la asistencia
            // Estado: 'presente', 'ausente', 'tardanza', 'justificado'
            $table->string('estado'); 
            $table->string('observacion')->nullable(); // ej. "llego 10 min tarde"
            $table->timestamps();
            // evitar duplicados: un alumno no puede tener 2 asistencias
            // en el mismo curso en la misma fecha
            $table->unique(['curso_id', 'alumno_id', 'fecha']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asistencias');
    }
};
