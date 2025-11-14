# Proyecto: Gestor de Notas CESJB

Este es un proyecto de software para la gestión de calificaciones del Centro Escolar Católico San Juan Bosco, desarrollado en Laravel, MySQL y Bootstrap 5.

## Usuarios de Prueba

Para probar el sistema, la base de datos se ha poblado con los siguientes usuarios de prueba.

---

### Usuario Administrador
* **Rol:** Tiene acceso a todos los módulos de gestión (Docentes, Alumnos, Materias, Cursos, etc.).
* **Email:** `admin@gestornotas.com`
* **Contraseña:** `password`

---

### Usuario Docente (Prueba)
* **Rol:** Solo puede ver su dashboard y gestionar las calificaciones de los cursos que tiene asignados.
* **Email:** `docente@gestornotas.com`
* **Contraseña:** `password`

---

### Usuarios Estudiantes (Patrón)
* **Rol:** (La vista del estudiante aún está en desarrollo).
* **Email:** `alumno1@gestornotas.com` (y así sucesivamente hasta `alumno50@gestornotas.com`)
* **Contraseña:** `password`