<?php

// Asegurar que la sesión siempre se inicie
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Core
require __DIR__ . '/../app/config/Database.php';
require __DIR__ . '/../app/core/Controller.php';
require __DIR__ . '/../app/core/Auth.php';

// Models
require __DIR__ . '/../app/models/User.php';
require __DIR__ . '/../app/models/Departamento.php';
require __DIR__ . '/../app/models/Empleado.php';
require __DIR__ . '/../app/models/Solicitud.php';
require __DIR__ . '/../app/models/Disponibilidad.php';
require __DIR__ . '/../app/models/Asistencia.php';
require __DIR__ . '/../app/models/Justificante.php';

// Controllers
require __DIR__ . '/../app/controllers/AuthController.php';
require __DIR__ . '/../app/controllers/DashboardController.php';
require __DIR__ . '/../app/controllers/UsersController.php';
require __DIR__ . '/../app/controllers/DepartamentosController.php';
require __DIR__ . '/../app/controllers/EmpleadosController.php';
require __DIR__ . '/../app/controllers/GestionController.php';
require __DIR__ . '/../app/controllers/DisponibilidadController.php';
require __DIR__ . '/../app/controllers/AsistenciaController.php';
require __DIR__ . '/../app/controllers/JustificanteController.php';
require __DIR__ . '/../app/controllers/ReportesController.php';
require __DIR__ . '/../app/controllers/LoginController.php';
require __DIR__ . '/../app/controllers/BackupController.php';


// Archivo de entorno
$envPath = __DIR__ . '/../.env.php';
if (!file_exists($envPath)) {
    copy(__DIR__ . '/../.env.example.php', $envPath);
}
$env = require $envPath;

// Obtener ruta
$route = $_GET['route'] ?? 'login';

$controller = null;
$method = null;

// ======================================================
//                    ROUTER PRINCIPAL
// ======================================================

switch ($route) {

    /* ======================================================
     *                      AUTENTICACIÓN
     * ====================================================== */

    case 'login':
        $controller = new AuthController;
        $method = 'login';
        break;

    case 'logout':
        $controller = new AuthController;
        $method = 'logout';
        break;

    case 'cambiar_password':
        $controller = new LoginController;
        $method = 'cambiar_password';
        break;

    /* ======================================================
     *                      DASHBOARD
     * ====================================================== */

    case 'dashboard':
        $controller = new DashboardController;
        $method = 'index';
        break;

    /* ======================================================
     *                      USUARIOS
     * ====================================================== */

    case 'users':
        $controller = new UsersController;
        $method = 'index';
        break;

    case 'users_create':
        $controller = new UsersController;
        $method = 'create';
        break;

    case 'users_edit':
        $controller = new UsersController;
        $method = 'edit';
        break;

    case 'users_delete':
        $controller = new UsersController;
        $method = 'delete';
        break;

    /* ======================================================
     *                  DEPARTAMENTOS
     * ====================================================== */

    case 'departamentos':
        $controller = new DepartamentosController;
        $method = 'index';
        break;

    case 'departamentos_create':
        $controller = new DepartamentosController;
        $method = 'create';
        break;

    case 'departamentos_edit':
        $controller = new DepartamentosController;
        $method = 'edit';
        break;

    case 'departamentos_delete':
        $controller = new DepartamentosController;
        $method = 'delete';
        break;

    /* ======================================================
     *                     EMPLEADOS
     * ====================================================== */

    case 'empleados':
        $controller = new EmpleadosController;
        $method = 'index';
        break;

    case 'empleados_create':
        $controller = new EmpleadosController;
        $method = 'create';
        break;

    case 'empleados_edit':
        $controller = new EmpleadosController;
        $method = 'edit';
        break;

    case 'empleados_delete':
        $controller = new EmpleadosController;
        $method = 'delete';
        break;

    /* ======================================================
     *                GESTIÓN ADMINISTRATIVA
     * ====================================================== */

    case 'solicitudes':
        $controller = new GestionController;
        $method = 'solicitudes';
        break;

    case 'solicitudes_create':
        $controller = new GestionController;
        $method = 'crearSolicitud';
        break;

    case 'solicitudes_estado':
        $controller = new GestionController;
        $method = 'cambiarEstado';
        break;

    /* ======================================================
     *                     DISPONIBILIDAD
     * ====================================================== */

    case 'disponibilidad':
        $controller = new DisponibilidadController;
        $method = 'index';
        break;

    case 'disponibilidad_crear':
        $controller = new DisponibilidadController;
        $method = 'crear';
        break;

    case 'disponibilidad_editar':
        $controller = new DisponibilidadController;
        $method = 'editar';
        break;

    case 'disponibilidad_eliminar':
        $controller = new DisponibilidadController;
        $method = 'eliminar';
        break;

    /* ======================================================
     *                     ASISTENCIAS
     * ====================================================== */

    case 'asistencia':
        $controller = new AsistenciaController;
        $method = 'index';
        break;

    case 'panel_asistencia':
        $controller = new AsistenciaController;
        $method = 'panelEmpleado';
        break;

    // Registrar entrada (Empleado)
    case 'asistencia_entrada':
        $controller = new AsistenciaController;
        $method = 'registrarEntrada';
        break;

    // Registrar salida (Empleado)
    case 'asistencia_salida':
        $controller = new AsistenciaController;
        $method = 'registrarSalida';
        break;

    // Asistencias pendientes (RH/Admin)
    case 'asistencias_pendientes':
        $controller = new AsistenciaController;
        $method = 'pendientes';
        break;

    case 'justificar_asistencia':
        $controller = new AsistenciaController;
        $method = 'justificar';
        break;

    case 'completar_asistencia':
        $controller = new AsistenciaController;
        $method = 'completar';
        break;

    case 'asistencias_inconsistencias':
        $controller = new AsistenciaController;
        $method = 'inconsistencias';
        break;

    /* ======================================================
     *                     JUSTIFICANTES
     * ====================================================== */

    case 'justificantes':
        $controller = new JustificanteController;
        $method = 'index';
        break;

    case 'panel_justificante':
        $controller = new JustificanteController;
        $method = 'panelEmpleado';
        break;

    case 'justificante_crear':
        $controller = new JustificanteController;
        $method = 'crear';
        break;

    case 'justificante_ver':
        $controller = new JustificanteController;
        $method = 'ver';
        break;

    case 'justificante_estado':
        $controller = new JustificanteController;
        $method = 'cambiarEstado';
        break;

    case 'justificante_eliminar':
        $controller = new JustificanteController;
        $method = 'eliminar';
        break;

    case 'historial_justificantes':
        $controller = new JustificanteController;
        $method = 'historial';
        break;

    /* ======================================================
     *                      REPORTES
     * ====================================================== */

    case 'reportes':
        $controller = new ReportesController;
        $method = 'index';
        break;
    // --- Generar respaldo ---
    case 'backup':
        $controller = new BackupController;
        $method = 'generar';
        break;

    // --- Mostrar formulario de restauración ---
    case 'backup_form':
        $controller = new BackupController;
        $method = 'formularioRestaurar';
        break;

    // --- Restaurar BD ---
    case 'backup_restaurar':
        $controller = new BackupController;
        $method = 'restaurar';
        break;

        case 'reportes/pdf':
    require_once '../app/controllers/ReportesController.php';
    $controller = new ReportesController;
    $controller->pdf();
    break;

    case 'reportes/pdf_tablas':
    require_once '../app/controllers/ReportesController.php';
    $controller = new ReportesController;
    $controller->pdfTablas();
    break;

case 'reportes/pdf_graficas':
    require_once '../app/controllers/ReportesController.php';
    $controller = new ReportesController;
    $controller->pdfGraficas();
    break;





    /* ======================================================
     *                RUTA NO ENCONTRADA
     * ====================================================== */

    default:
        http_response_code(404);
        echo "Ruta no encontrada";
        exit;
}

// ======================================================
//  EJECUTAR CONTROLADOR Y MÉTODO
// ======================================================

if ($controller && $method) {

    // Si la URL trae ?id=XX → lo manda como parámetro
    if (isset($_GET['id'])) {
        $controller->$method($_GET['id']);
    } else {
        $controller->$method();
    }
}

?>
