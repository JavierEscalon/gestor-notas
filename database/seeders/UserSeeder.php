<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // importamos la clase para encriptar
use App\Models\User; // importamos el modelo de usuario

class UserSeeder extends Seeder
{
    /**
     * run the database seeds.
     *
     * @return void
     */
    public function run()
    {   

        // 0 usuario SysAdmin
        User::create([
            'name' => 'Administrador del Sistema',
            'email' => 'sysadmin@gestornotas.com',
            'password' => Hash::make('password'),
            'role' => 'sysadmin', // Nuevo rol
        ]);

        // creamos nuestro primer usuario administrador
        User::create([
            'name' => 'administrador',
            'email' => 'admin@gestornotas.com',
            'password' => Hash::make('password'), // encriptamos el password
            'role' => 'admin', // le asignamos el rol de admin
        ]);

        // puedes agregar mas usuarios aqui si quieres
        // por ejemplo, un docente de prueba
        /*
        User::create([
            'name' => 'docente prueba',
            'email' => 'docente@gestornotas.com',
            'password' => Hash::make('123456'),
            'role' => 'docente',
        ]);
        */
    }
}