<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Seccion; // (importamos el modelo)

class SeccionSeeder extends Seeder
{
    /**
     * run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Seccion::create(['name' => 'A']);
        Seccion::create(['name' => 'B']);
        Seccion::create(['name' => 'C']);
    }
}