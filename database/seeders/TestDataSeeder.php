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
    public function run()
    {
        // 1. Obtenemos catálogos base (Grado y Sección creados por otros seeders)
        $grado = Grado::first();
        $seccion = Seccion::first();

        if (!$grado || !$seccion) {
            $this->command->error('No hay grados/secciones. Ejecuta migrate:fresh --seed');
            return;
        }

        // 2. Creamos un Docente de prueba
        $userDocente = User::create([
            'name' => 'Docente General',
            'email' => 'docente@gestornotas.com',
            'password' => Hash::make('password'),
            'role' => 'docente',
        ]);
        $docente = Docente::create([
            'user_id' => $userDocente->id,
            'first_name' => 'Docente',
            'last_name' => 'Prueba',
            'specialty' => 'General',
        ]);

        // 3. Creamos 10 Alumnos de prueba
        $this->command->info('Creando alumnos...');
        $alumnos_ids = [];
        for ($i = 1; $i <= 10; $i++) {
            $user = User::create([
                'name' => "Alumno $i Apellido",
                'email' => "alumno$i@gestornotas.com",
                'password' => Hash::make('password'),
                'role' => 'estudiante',
            ]);
            $alumno = Alumno::create([
                'user_id' => $user->id,
                'grado_id' => $grado->id,
                'seccion_id' => $seccion->id,
                'first_name' => "Alumno",
                'last_name' => "$i",
                'student_id_code' => "2025-$i",
            ]);
            $alumnos_ids[] = $alumno->id;
        }

        // 4. Creamos las 3 Materias solicitadas
        $nombresMaterias = ['Matemáticas', 'Literatura', 'Ciencias'];
        $materiasGuardadas = [];
        
        foreach ($nombresMaterias as $nombre) {
            $materiasGuardadas[] = Materia::create(['name' => $nombre]);
        }

        // 5. Creamos 3 Períodos y los cursos correspondientes
        $trimestres = ['Trimestre 1', 'Trimestre 2', 'Trimestre 3'];
        
        $this->command->info('Creando periodos y cursos...');

        foreach ($trimestres as $index => $nombrePeriodo) {
            // Crear Periodo
            $periodo = PeriodoEscolar::create([
                'name' => $nombrePeriodo . ' - 2025',
                'start_date' => now()->addMonths($index * 3), // Fechas escalonadas
                'end_date' => now()->addMonths(($index * 3) + 3),
                'is_active' => ($index == 0), // Solo el primero está activo
            ]);

            // Por cada materia, creamos un curso en este periodo
            foreach ($materiasGuardadas as $materia) {
                $curso = Curso::create([
                    'materia_id' => $materia->id,
                    'docente_id' => $docente->id, // Asignamos el mismo docente a todo para probar fácil
                    'grado_id' => $grado->id,
                    'seccion_id' => $seccion->id,
                    'periodo_id' => $periodo->id,
                    'is_calificaciones_closed' => false,
                ]);

                // Inscribimos a TODOS los alumnos en cada curso
                $curso->alumnos()->attach($alumnos_ids);
            }
        }

        $this->command->info('¡Escenario completo creado! (10 Alumnos inscritos en 3 materias durante 3 trimestres)');
    }
}