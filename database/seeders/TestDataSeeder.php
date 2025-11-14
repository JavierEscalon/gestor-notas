<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Docente;
use App\Models\Alumno;
use App\Models\Materia;
use App\Models\PeriodoEscolar;
use App\Models\Curso;
use App\Models\Grado;
use App\Models\Seccion;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    /**
     * run the database seeds.
     * (crea un escenario de prueba completo con 50 alumnos)
     *
     * @return void
     */
    public function run()
    {
        // --- 1. obtenemos los catalogos que ya existen ---
        $grado = Grado::first();
        $seccion = Seccion::first();

        // (si no hay grados o secciones, nos detenemos para evitar un error)
        if (!$grado || !$seccion) {
            $this->command->error('No se encontraron grados o secciones. Ejecuta los seeders de catalogos primero.');
            return;
        }

        // --- creamos un docente de prueba ---
        $userDocente = User::create([
            'name' => 'Docente Prueba',
            'email' => 'docente@gestornotas.com',
            'password' => Hash::make('password'),
            'role' => 'docente',
        ]);
        $docente = Docente::create([
            'user_id' => $userDocente->id,
            'first_name' => 'Docente',
            'last_name' => 'Prueba',
            'specialty' => 'Pruebas',
        ]);

        // --- creamos 50 alumnos de prueba ---

        $this->command->info('Creando 50 alumnos de prueba...');

        $alumnos_ids_para_inscribir = []; // (un array para guardar los ids)

        for ($i = 1; $i <= 50; $i++) {

            // (creamos el usuario para el login)
            $userAlumno = User::create([
                'name' => "Alumno $i Apellido",
                'email' => "alumno$i@gestornotas.com",
                'password' => Hash::make('password'),
                'role' => 'estudiante',
            ]);

            // (creamos el perfil del alumno)
            $alumno = Alumno::create([
                'user_id' => $userAlumno->id,
                'grado_id' => $grado->id,
                'seccion_id' => $seccion->id,
                'first_name' => "Alumno $i",
                'last_name' => "Apellido $i",
                'student_id_code' => "1000$i", // (carnet unico)
            ]);

            // (guardamos el id para el paso final)
            $alumnos_ids_para_inscribir[] = $alumno->id;
        }


        // --- creamos una materia y un periodo de prueba ---
        $materia = Materia::create([
            'name' => 'Materia de Prueba (Curso Lleno)',
        ]);
        $periodo = PeriodoEscolar::create([
            'name' => 'Trimestre de Prueba 2025',
            'start_date' => '2025-01-01',
            'end_date' => '2025-03-31',
            'is_active' => true,
        ]);

        // --- creamos el curso que une todo ---
        $curso = Curso::create([
            'materia_id' => $materia->id,
            'docente_id' => $docente->id,
            'grado_id' => $grado->id,
            'seccion_id' => $seccion->id,
            'periodo_id' => $periodo->id,
        ]);

        // --- (el paso final) inscribimos a los 50 alumnos en el curso ---
        $curso->alumnos()->attach($alumnos_ids_para_inscribir);

        $this->command->info('¡Escenario de prueba con 50 alumnos creado exitosamente!');
    }
}