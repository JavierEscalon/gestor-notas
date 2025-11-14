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
        Schema::create('cursos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('materia_id')->constrained('materias'); // (FK)
            $table->foreignId('docente_id')->constrained('docentes'); // (FK)
            $table->foreignId('grado_id')->constrained('grados'); // (FK)
            $table->foreignId('seccion_id')->constrained('seccions'); // (FK)
            $table->foreignId('periodo_id')->constrained('periodos_escolar'); // (FK)
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cursos');
    }
};
