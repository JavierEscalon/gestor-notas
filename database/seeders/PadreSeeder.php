<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Padre;
use App\Models\Alumno;
use Illuminate\Support\Facades\Hash;

class PadreSeeder extends Seeder
{
    public function run()
    {
        // 1 crear el Usuario (Login)
        $userPadre = User::create([
            'name' => 'Padre de Familia Prueba',
            'email' => 'padre@gestornotas.com',
            'password' => Hash::make('password'),
            'role' => 'padre', // este rol lo usaremos en el LoginController
        ]);

        // 2 crear el Perfil del Padre
        $padre = Padre::create([
            'user_id' => $userPadre->id,
            'first_name' => 'Juan',
            'last_name' => 'Pérez (Padre)',
            'phone' => '7777-7777',
        ]);

        // 3 asignarle hijos alumnos existentes
        // buscamos los primeros 2 alumnos de la base de datos
        $hijos = Alumno::take(2)->get();

        if ($hijos->count() > 0) {
            // usamos la relación 'alumnos()' que definimos en el modelo Padre
            // para llenar la tabla pivote 'alumno_padre'
            $padre->alumnos()->attach($hijos->pluck('id'));
            
            $this->command->info('Padre creado y asignado a ' . $hijos->count() . ' alumnos.');
        } else {
            $this->command->warn('Padre creado, pero no se encontraron alumnos para asignar.');
        }
    }
}