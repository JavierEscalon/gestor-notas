<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipoActividad; // (importamos el modelo)

class TipoActividadSeeder extends Seeder
{
    /**
     * run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // (creamos los tipos de ejemplo)
        TipoActividad::create(['name' => 'Tarea (Cotidiano)', 'default_percentage' => 30]);
        TipoActividad::create(['name' => 'Examen (Prueba)', 'default_percentage' => 35]);
        TipoActividad::create(['name' => 'Proyecto', 'default_percentage' => 35]);
    }
}