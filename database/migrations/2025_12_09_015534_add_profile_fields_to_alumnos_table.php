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
        Schema::table('alumnos', function (Blueprint $table) {
            // datos personales
            $table->date('birth_date')->nullable()->after('last_name');
            $table->enum('gender', ['M', 'F'])->nullable()->after('birth_date');
            $table->string('address', 255)->nullable()->after('gender');
            $table->string('phone', 20)->nullable()->after('address');
            
            // datos medicos / emergencia
            $table->string('emergency_contact_name')->nullable()->after('phone');
            $table->string('emergency_contact_phone')->nullable()->after('emergency_contact_name');
            $table->string('medical_conditions')->nullable()->after('emergency_contact_phone'); // Alergias, enfermedades
            
            // estado de matricula
            $table->enum('status', ['activo', 'inactivo', 'retirado'])->default('activo')->after('student_id_code');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('alumnos', function (Blueprint $table) {
            $table->dropColumn([
                'birth_date', 'gender', 'address', 'phone',
                'emergency_contact_name', 'emergency_contact_phone', 
                'medical_conditions', 'status'
            ]);
        });
    }
};
