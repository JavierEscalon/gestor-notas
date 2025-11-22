<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\MateriaController;
use App\Http\Controllers\PeriodoEscolarController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\CalificacionController;
use App\Http\Controllers\Docente\DashboardController as DocenteDashboardController;
use App\Http\Controllers\Estudiante\DashboardController as EstudianteDashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// --- Rutas de Autenticación ---

// Muestra la página de login (nuestra vista login.blade.php)
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');

// Procesa el formulario de login cuando el usuario lo envía
Route::post('login', [LoginController::class, 'login']);

// Maneja el "Cerrar Sesión"
Route::post('logout', [LoginController::class, 'logout'])->name('logout');


// --- rutas protegidas por autenticacion ---

// usamos 'middleware('auth')' para que solo usuarios logueados
// puedan acceder a estas rutas.
route::middleware(['auth'])->group(function () {

    // la ruta principal del dashboard
    route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


    // rutas para la gestion de docentes
    route::get('/docentes', [DocenteController::class, 'index'])->name('docentes.index');
    route::get('/docentes/crear', [DocenteController::class, 'create'])->name('docentes.create');
    route::post('/docentes', [DocenteController::class, 'store'])->name('docentes.store');
    route::get('/docentes/{docente}/editar', [DocenteController::class, 'edit'])->name('docentes.edit');
    route::put('/docentes/{docente}', [DocenteController::class, 'update'])->name('docentes.update');
    route::delete('/docentes/{docente}', [DocenteController::class, 'destroy'])->name('docentes.destroy');

    // rutas para la gestion de alumnos
    route::get('/alumnos', [AlumnoController::class, 'index'])->name('alumnos.index');
    route::get('/alumnos/crear', [AlumnoController::class, 'create'])->name('alumnos.create');
    route::post('/alumnos', [AlumnoController::class, 'store'])->name('alumnos.store');
    route::get('/alumnos/{alumno}/editar', [AlumnoController::class, 'edit'])->name('alumnos.edit');
    route::put('/alumnos/{alumno}', [AlumnoController::class, 'update'])->name('alumnos.update');
    Route::delete('/alumnos/{alumno}', [AlumnoController::class, 'destroy'])->name('alumnos.destroy');

    // ruta para la gestion de materias
    route::resource('materias', MateriaController::class);

    // ruta para la gestion de periodo escolar
    Route::resource('periodos', PeriodoEscolarController::class);

    // ruta para la gestion de cursos
    Route::resource('cursos', CursoController::class);
    
    // ruta para la gestion de inscripcion
    Route::post('/cursos/{curso}/inscribir', [CursoController::class, 'inscribirAlumnos'])->name('cursos.inscribir');
    Route::delete('/cursos/{curso}/quitar/{alumno}', [CursoController::class, 'quitarAlumno'])->name('cursos.quitar');

// / ruta para la gestion de docente
Route::middleware(['auth'])->prefix('docente')->name('docente.')->group(function () {

    // la ruta del dashboard del docente
    Route::get('/dashboard', [DocenteDashboardController::class, 'index'])->name('dashboard');

    // la ruta para ver el formulario de calificaciones de un curso
    Route::get('/cursos/{curso}/calificaciones', [CalificacionController::class, 'show'])->name('cursos.calificaciones');

    // ruta para guardar las notas
    Route::post('/cursos/{curso}/calificaciones', [CalificacionController::class, 'store'])->name('cursos.calificaciones.store');

    // ruta para borrar actividades
    Route::delete('/cursos/{curso}/calificaciones/{activity_name}', [CalificacionController::class, 'destroyActivity'])->name('calificaciones.destroy');

    // (rutas que agregamos para editar)
    Route::get('/cursos/{curso}/calificaciones/{activity_name}/edit', [CalificacionController::class, 'editActivity'])->name('calificaciones.edit');
    Route::put('/cursos/{curso}/calificaciones/{activity_name}', [CalificacionController::class, 'updateActivity'])->name('calificaciones.update');

});


// --- RUTAS DEL ESTUDIANTE ---
Route::middleware(['auth'])->prefix('estudiante')->name('estudiante.')->group(function () {

    // la ruta del dashboard del alumno
    Route::get('/dashboard', [App\Http\Controllers\Estudiante\DashboardController::class, 'index'])->name('dashboard');
});


});