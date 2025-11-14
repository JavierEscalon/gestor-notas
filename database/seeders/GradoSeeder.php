<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Grado; // importamos el modelo

class GradoSeeder extends Seeder
{
    /**
     * run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Grado::create(['name' => 'Primer Grado', 'level' => 'Primer Ciclo']);
        Grado::create(['name' => 'Segundo Grado', 'level' => 'Primer Ciclo']);
        Grado::create(['name' => 'Tercer Grado', 'level' => 'Primer Ciclo']);
        Grado::create(['name' => 'Cuarto Grado', 'level' => 'Segundo Ciclo']);
        Grado::create(['name' => 'Quinto Grado', 'level' => 'Segundo Ciclo']);
        Grado::create(['name' => 'Sexto Grado', 'level' => 'Segundo Ciclo']);
        Grado::create(['name' => 'Séptimo Grado', 'level' => 'Tercer Ciclo']);
        Grado::create(['name' => 'Octavo Grado', 'level' => 'Tercer Ciclo']);
        Grado::create(['name' => 'Noveno Grado', 'level' => 'Tercer Ciclo']);
        Grado::create(['name' => 'Primer Año Bachillerato', 'level' => 'Media']);
        Grado::create(['name' => 'Segundo Año Bachillerato', 'level' => 'Media']);
    }
}