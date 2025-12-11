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
        // 1. Obtenemos catálogos base
        $grado = Grado::first();
        $seccion = Seccion::first();

        if (!$grado || !$seccion) {
            $this->command->error('No hay grados/secciones. Ejecuta migrate:fresh --seed');
            return;
        }

        // 2. Creamos Docente y SysAdmin (si no existen)
        // (El UserSeeder probablemente ya crea el SysAdmin, aquí aseguramos el Docente)
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

        // 3. Creamos 10 Alumnos con EXPEDIENTE COMPLETO
        $this->command->info('Creando alumnos con expedientes completos...');
        $alumnos_ids = [];
        
        for ($i = 1; $i <= 10; $i++) {
            $user = User::create([
                'name' => "Alumno $i Apellido",
                'email' => "alumno$i@gestornotas.com",
                'password' => Hash::make('password'),
                'role' => 'estudiante',
            ]);

            // Generamos datos aleatorios para darle realismo
            $genero = (rand(0, 1) == 1) ? 'M' : 'F';
            
            $alumno = Alumno::create([
                'user_id' => $user->id,
                'grado_id' => $grado->id,
                'seccion_id' => $seccion->id,
                'first_name' => "Alumno",
                'last_name' => "$i",
                'student_id_code' => "2025-" . str_pad($i, 3, '0', STR_PAD_LEFT), // Ej: 2025-001
                
                // --- NUEVOS DATOS REALES ---
                'birth_date' => now()->subYears(rand(12, 16))->subDays(rand(1, 365)), // Entre 12 y 16 años atrás
                'gender' => $genero,
                'address' => 'Colonia San Benito, Calle #' . rand(1, 20) . ', Casa ' . rand(100, 999),
                'phone' => '7' . rand(100, 999) . '-' . rand(1000, 9999), // Formato teléfono móvil
                'emergency_contact_name' => 'Padre/Madre de Alumno ' . $i,
                'emergency_contact_phone' => '2' . rand(100, 999) . '-' . rand(1000, 9999),
                'medical_conditions' => (rand(1, 5) == 1) ? 'Alergia al polvo / Asma' : null, // 20% de prob. de tener condición
                'status' => 'activo',
            ]);
            
            $alumnos_ids[] = $alumno->id;
        }

        // 4. Creamos Materias
        $nombresMaterias = ['Matemáticas', 'Literatura', 'Ciencias'];
        $materiasGuardadas = [];
        foreach ($nombresMaterias as $nombre) {
            $materiasGuardadas[] = Materia::firstOrCreate(['name' => $nombre]);
        }

        // 5. Creamos 3 Períodos y Cursos
        $trimestres = ['Trimestre 1', 'Trimestre 2', 'Trimestre 3'];
        $this->command->info('Creando estructura académica anual...');

        foreach ($trimestres as $index => $nombrePeriodo) {
            $periodo = PeriodoEscolar::create([
                'name' => $nombrePeriodo . ' - 2025',
                'start_date' => now()->addMonths($index * 3),
                'end_date' => now()->addMonths(($index * 3) + 3),
                'is_active' => ($index == 0),
            ]);

            foreach ($materiasGuardadas as $materia) {
                $curso = Curso::create([
                    'materia_id' => $materia->id,
                    'docente_id' => $docente->id,
                    'grado_id' => $grado->id,
                    'seccion_id' => $seccion->id,
                    'periodo_id' => $periodo->id,
                    'is_calificaciones_closed' => false,
                ]);
                $curso->alumnos()->attach($alumnos_ids);
            }
        }

        $this->command->info('¡Datos de prueba generados exitosamente!');
    }
}