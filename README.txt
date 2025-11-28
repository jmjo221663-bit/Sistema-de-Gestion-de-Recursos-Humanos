
SGRH - PHP nativo MVC (Primeros 5 requisitos)

Requisitos cubiertos:
1) FN1 Iniciar sesión (roles: admin, rh, empleado)
2) FN2 Gestión de Recursos Humanos (Empleados CRUD)
3) FN3 Gestión Administrativa (Solicitudes vacaciones/permiso: crear, listar, aprobar/rechazar)
4) FN4 Gestión de Usuarios (solo admin CRUD)
5) FN5 Gestión de Departamentos (CRUD y vínculo con empleados)

Cómo correr:
1. Copia la carpeta `SGRH_PHP_MVC` a `htdocs` (XAMPP).
   Ruta esperada: C:\xampp\htdocs\SGRH_PHP_MVC

2. Crea `.env.php` desde `.env.example.php` si deseas cambiar credenciales.

3. En phpMyAdmin, importa `database/sgrh_schema.sql` (esto crea la BD y datos demo).

4. Abre en el navegador:
   http://localhost/SGRH_PHP_MVC/public/?route=login

Usuario demo:
  email: admin@demo.com
  pass : Admin123*

Notas de validación:
- Email validado (HTML5 + servidor)
- Contraseñas mínimo 8 caracteres
- CURP tamaño fijo 18
- FK y UNIQUE en BD para integridad
- CSRF en formularios, roles y protección de rutas

Rutas rápidas:
- Dashboard (autenticado): ?route=dashboard
- Usuarios (admin): ?route=users
- Empleados (admin/rh): ?route=empleados
- Departamentos (admin/rh): ?route=departamentos
- Solicitudes (admin/rh): ?route=solicitudes
