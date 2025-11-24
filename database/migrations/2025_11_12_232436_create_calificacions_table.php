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
        Schema::create('calificacions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumno_id')->constrained('alumnos'); // (FK)
            $table->foreignId('curso_id')->constrained('cursos'); // (FK)
            $table->foreignId('tipo_actividad_id')->constrained('tipo_actividads'); // (FK)
            $table->foreignId('periodo_id')->constrained('periodos_escolar'); // (FK)
            
            $table->decimal('score', 5, 2); // ej. 8.50
            $table->decimal('percentage', 5, 2); //ej. 20.00
            $table->string('activity_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calificacions');
    }
};
