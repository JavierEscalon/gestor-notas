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
use App\Http\Controllers\Admin\BoletaController;
use App\Http\Controllers\SysAdminController;

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

// 1. CORRECCIÓN: La raíz apunta al Login
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');

// Rutas de Autenticación
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
// Procesa el formulario de login cuando el usuario lo envía
Route::post('login', [LoginController::class, 'login']);
// Maneja el "Cerrar Sesión"
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// --- RUTAS DEL ADMIN ---
// Usamos 'middleware(['auth', 'role:admin'])' para proteger todas estas rutas
Route::middleware(['auth', 'role:admin'])->group(function () {

    // La ruta principal del dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // -- GESTIÓN DOCENTES --
    Route::get('/docentes', [DocenteController::class, 'index'])->name('docentes.index');
    Route::get('/docentes/crear', [DocenteController::class, 'create'])->name('docentes.create');
    Route::post('/docentes', [DocenteController::class, 'store'])->name('docentes.store');
    Route::get('/docentes/{docente}/editar', [DocenteController::class, 'edit'])->name('docentes.edit');
    Route::put('/docentes/{docente}', [DocenteController::class, 'update'])->name('docentes.update');
    Route::delete('/docentes/{docente}', [DocenteController::class, 'destroy'])->name('docentes.destroy');

    // -- GESTIÓN ALUMNOS --
    Route::get('/alumnos', [AlumnoController::class, 'index'])->name('alumnos.index');
    Route::get('/alumnos/crear', [AlumnoController::class, 'create'])->name('alumnos.create');
    Route::post('/alumnos', [AlumnoController::class, 'store'])->name('alumnos.store');
    Route::get('/alumnos/{alumno}/editar', [AlumnoController::class, 'edit'])->name('alumnos.edit');
    Route::put('/alumnos/{alumno}', [AlumnoController::class, 'update'])->name('alumnos.update');
    Route::delete('/alumnos/{alumno}', [AlumnoController::class, 'destroy'])->name('alumnos.destroy');

    // --- GESTIÓN DE PADRES (ADMIN) ---
    Route::prefix('admin')->name('admin.')->group(function () {
        // CRUD básico
        Route::resource('padres', App\Http\Controllers\Admin\PadreController::class);        
        // Rutas para asignar hijos
        Route::get('padres/{padre}/hijos', [App\Http\Controllers\Admin\PadreController::class, 'hijos'])->name('padres.hijos');
        Route::post('padres/{padre}/hijos', [App\Http\Controllers\Admin\PadreController::class, 'updateHijos'])->name('padres.hijos.store');
    });

    // -- OTROS RECURSOS --
    // Ruta para la gestión de materias
    Route::resource('materias', MateriaController::class);
    // Ruta para la gestión de periodo escolar
    Route::resource('periodos', PeriodoEscolarController::class);
    // Ruta para la gestión de cursos
    Route::resource('cursos', CursoController::class);
    
    // Reabrir periodo
    Route::post('/cursos/{curso}/reabrir', [CursoController::class, 'reabrirPeriodo'])->name('cursos.reabrir');

    // -- BOLETAS --
    // Individual
    Route::get('/admin/boleta/{alumno}', [BoletaController::class, 'download'])->name('admin.boleta.download');
    // Masiva (batch)
    Route::get('/admin/boleta/batch/grado/{grado}/seccion/{seccion}', [BoletaController::class, 'downloadBatch'])->name('admin.boleta.batch');

    // Ruta para la gestión de inscripcion
    Route::post('/cursos/{curso}/inscribir', [CursoController::class, 'inscribirAlumnos'])->name('cursos.inscribir');
    Route::delete('/cursos/{curso}/quitar/{alumno}', [CursoController::class, 'quitarAlumno'])->name('cursos.quitar');

    // --- RUTAS DE REPORTES ADMINISTRATIVOS ---
    Route::prefix('admin/reportes')->name('admin.reportes.')->group(function () {
        // Vista inicial, selección de Grado
        Route::get('/', [App\Http\Controllers\Admin\ReporteAdminController::class, 'index'])->name('index');
        
        // Vista detallada, matriz de notas
        Route::get('/grado/{grado}/seccion/{seccion}', [App\Http\Controllers\Admin\ReporteAdminController::class, 'show'])->name('show');
    });

}); // Fin del grupo Admin


// --- RUTAS DEL DOCENTE ---
Route::middleware(['auth', 'role:docente'])->prefix('docente')->name('docente.')->group(function () {
    // La ruta del dashboard del docente
    Route::get('/dashboard', [DocenteDashboardController::class, 'index'])->name('dashboard');
    // La ruta para ver el formulario de calificaciones de un curso
    Route::get('/cursos/{curso}/calificaciones', [CalificacionController::class, 'show'])->name('cursos.calificaciones');
    // Ruta para guardar las notas
    Route::post('/cursos/{curso}/calificaciones', [CalificacionController::class, 'store'])->name('cursos.calificaciones.store');
    // Ruta para borrar actividades
    Route::delete('/cursos/{curso}/calificaciones/{activity_name}', [CalificacionController::class, 'destroyActivity'])->name('calificaciones.destroy');
    // (Rutas que agregamos para editar)
    Route::get('/cursos/{curso}/calificaciones/{activity_name}/edit', [CalificacionController::class, 'editActivity'])->name('calificaciones.edit');
    Route::put('/cursos/{curso}/calificaciones/{activity_name}', [CalificacionController::class, 'updateActivity'])->name('calificaciones.update');
    // Rutas para control de asistencia
    Route::get('/cursos/{curso}/asistencia', [App\Http\Controllers\AsistenciaController::class, 'index'])->name('cursos.asistencia');
    Route::post('/cursos/{curso}/asistencia', [App\Http\Controllers\AsistenciaController::class, 'store'])->name('cursos.asistencia.store');
    // Rutas para el reporte
    Route::get('/cursos/{curso}/reporte', [App\Http\Controllers\Docente\ReporteController::class, 'show'])->name('cursos.reporte');
    Route::post('/cursos/{curso}/cerrar', [App\Http\Controllers\Docente\ReporteController::class, 'cerrarPeriodo'])->name('cursos.cerrar');
});


// --- RUTAS DEL ESTUDIANTE ---
Route::middleware(['auth', 'role:estudiante'])->prefix('estudiante')->name('estudiante.')->group(function () {
    // La ruta del dashboard del alumno
    Route::get('/dashboard', [EstudianteDashboardController::class, 'index'])->name('dashboard');
});


// --- RUTAS DEL PADRE DE FAMILIA ---
Route::middleware(['auth', 'role:padre'])->prefix('padre')->name('padre.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Padre\DashboardController::class, 'index'])->name('dashboard');
});

// ============================================================
// GRUPO: SYSADMIN (ADMINISTRADOR DEL SISTEMA)
// ============================================================
Route::middleware(['auth', 'role:sysadmin'])->prefix('sysadmin')->name('sysadmin.')->group(function () {
    
    // dashboard (gestion de admins academicos)
    Route::get('/dashboard', [SysAdminController::class, 'index'])->name('dashboard');
    
    // crear y eliminar admins academicos
    Route::post('/admins', [SysAdminController::class, 'storeAdmin'])->name('admins.store');
    Route::delete('/admins/{user}', [SysAdminController::class, 'destroyAdmin'])->name('admins.destroy');

    // editar y actualizar
    Route::get('/admins/{user}/edit', [SysAdminController::class, 'editAdmin'])->name('admins.edit');
    Route::put('/admins/{user}', [SysAdminController::class, 'updateAdmin'])->name('admins.update');

    // ver bitacora
    Route::get('/bitacora', [SysAdminController::class, 'bitacora'])->name('bitacora');
});

