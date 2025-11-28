# Sistema Gestor de Notas - CESJB 

Plataforma integral para la gestión académica, control de calificaciones y asistencia para un centro de educacion basica.

Este proyecto digitaliza el flujo de información entre la administración, docentes, estudiantes y padres de familia, permitiendo un seguimiento en tiempo real del rendimiento académico, generación de reportes y control de asistencia.

---

## Funcionalidades Principales

### 1. Administración Académica
* **Gestión de Catálogos:** CRUD completo para Materias, Grados, Secciones y Períodos Escolares.
* **Gestión de Usuarios:** Registro y administración de Docentes, Alumnos y Padres de Familia.
* **Gestión Académica:** Creación de Cursos (Asignación de Docente + Materia + Grado + Sección).
* **Inscripciones:** Matriculación masiva de estudiantes a cursos.
* **Reportes:**
  * Vista consolidada de notas por Grado (Matriz de notas por período).
  * Generación de **PDF masivo** con las boletas de todos los alumnos de un grado.
* **Seguridad:** Capacidad de reabrir períodos cerrados por docentes.

### 2. Módulo Docente
* **Dashboard:** Vista rápida de cursos asignados.
* **Gestión de Calificaciones:**
  * Creación de actividades con **ponderación personalizada** (%).
  * Validación automática para **no exceder el 100%** de la nota final.
  * Cierre de períodos (bloqueo de edición de notas).
* **Control de Asistencia:** Registro diario (Presente, Ausente, Tardanza, Justificado).
* **Reporte de Curso:** Vista previa del rendimiento del grupo con semáforo de aprobación.

### 3. Módulo Estudiante y Padre de Familia
* **Dashboard Estudiante:** Visualización de boleta de notas con detalle de puntos ganados y porcentaje real.
* **Dashboard Padre:** Vista unificada por pestañas para monitorear a múltiples hijos desde una sola cuenta.
* **Asistencia:** Gráfico visual del porcentaje de asistencia y desglose de faltas.

### 4. Super Admin (SysAdmin)
* **Auditoría:** Bitácora de eventos del sistema (Log de transacciones).
* **Gestión de Accesos:** Control de usuarios administrativos.

---

## Instalación y Configuración Local

Sigue estos pasos para desplegar el proyecto desde cero:

1. **Clonar el repositorio**
   
   git clone [https://github.com/TuUsuario/gestor-notas.git](https://github.com/TuUsuario/gestor-notas.git)
   
   cd gestor-notas

2. **Instalar dependencias de Backend (PHP)**

    composer install

3. **Instalar dependencias de Frontend (JS/CSS)**

    npm install
    npm run build

4. **Configurar entorno**
    
    Copia el archivo .env.example y renómbralo a .env.

    Configura tus credenciales de base de datos en el archivo .env (DB_DATABASE, DB_USERNAME, etc.).

5. **Generar llave de aplicación**

    php artisan key:generate

6. **Migrar y Poblar Base de Datos**
    Este comando crea la estructura de la base de datos e inserta un escenario de prueba completo (10 alumnos, cursos, materias, períodos y usuarios).

    php artisan migrate:fresh --seed

7. **Credenciales de Acceso (Usuarios de Prueba)**

    El seeder automático genera los siguientes usuarios para probar todos los roles: (La contraseña es la misma para todos).

Rol                 Email                       Contraseña  Descripción
Super Admin         sysadmin@gestornotas.com    password    Acceso a Bitácora y Gestión de Admins.
Admin Académico     admi@gestornotas.com        password    Gestión escolar completa.
Docente             docente@gestornotas.com     password    Gestión de sus cursos asignados.
Padre de Familia    padre@gestornotas.com       password    Tiene asignados a los alumnos 1 y 2.
Estudiante          alumno1@gestornotas.com     password    (Disponible del alumno1 al alumno10).

8. **Tecnologías Utilizadas**

    Framework Backend: Laravel 10 (PHP 8.1+).

    Base de Datos: MySQL.

    Frontend: Bootstrap 5 (Blade Templates + Vite).

    Reportes: DomPDF (Generación de PDF).

    Control de Versiones: Git / GitHub.

    Desarrollado para la asignatura de Análisis y Diseño de Sistemas.