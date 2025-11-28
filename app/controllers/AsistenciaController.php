<?php

require_once __DIR__ . '/../models/Asistencia.php';
require_once __DIR__ . '/../models/Disponibilidad.php';

class AsistenciaController extends Controller {

    /* ====================================================
     * INDEX GENERAL
     * ==================================================== */
    public function index() {
        Auth::authorize(['admin', 'rh', 'empleado']);

        // Si es empleado → ver su propio panel
        if (Auth::user()['role'] === 'empleado') {
            return $this->panelEmpleado();
        }

        // Si es admin o RH → ver todas las asistencias
        $asistencias = Asistencia::all();
        $this->view('asistencia/index', compact('asistencias'));
    }

    /* ====================================================
     * PANEL DEL EMPLEADO
     * ==================================================== */
    public function panelEmpleado() {
        Auth::authorize(['empleado']);

        // ID REAL del empleado, NO del usuario
        $empleado_id = Auth::user()['empleado_id'];

        if (!$empleado_id) {
            die("Error: Este usuario no está vinculado con un empleado.");
        }

        $asistencias = Asistencia::getByEmpleado($empleado_id);

        return $this->view('asistencia/panel_empleado', compact('asistencias'));
    }

    /* ====================================================
     * REGISTRAR ENTRADA
     * ==================================================== */
    public function registrarEntrada() {

        Auth::authorize(['empleado']);

        $empleado_id = Auth::user()['empleado_id'];
        $fecha = date('Y-m-d');

        if (!$empleado_id) {
            die("Error: Usuario sin empleado_id asignado.");
        }

        // Obtener disponibilidad del día
        $estado = Disponibilidad::obtenerEstado($empleado_id, $fecha);

        // Caso 1: DESCANSO
        if ($estado === 'descanso') {
            echo "<script>
                    alert('Hoy tienes descanso, no puedes registrar asistencia.');
                    window.location.href='?route=asistencia';
                  </script>";
            exit;
        }

        // Caso 2: AUSENTE
        if ($estado === 'ausente') {
            Asistencia::registrarEntradaEspecial($empleado_id);
            header("Location: /SGRH_PHP_MVC/public/?route=asistencia&msg=entrada_ausente");
            exit;
        }

        // Caso 3: ESPECIAL
        if ($estado === 'especial') {
            Asistencia::registrarEntradaEspecial($empleado_id);
            header("Location: /SGRH_PHP_MVC/public/?route=asistencia&msg=entrada_especial");
            exit;
        }

        // Caso 4: DISPONIBLE o SIN REGISTRO
        Asistencia::registrarEntrada($empleado_id);
        header("Location: /SGRH_PHP_MVC/public/?route=asistencia");
        exit;
    }

    /* ====================================================
     * REGISTRAR SALIDA
     * ==================================================== */
    public function registrarSalida() {
        Auth::authorize(['empleado']);

        $empleado_id = Auth::user()['empleado_id'];

        if (!$empleado_id) {
            die("Error: Usuario sin empleado_id asignado.");
        }

        Asistencia::registrarSalida($empleado_id);

        header("Location: /SGRH_PHP_MVC/public/?route=asistencia");
        exit;
    }

    /* ====================================================
     * INCONSISTENCIAS (RH / ADMIN)
     * ==================================================== */
    public function inconsistencias() {
        Auth::authorize(['admin', 'rh']);

        $db = Database::getInstance();

        $sql = "
            SELECT 
                a.*,
                e.nombre,
                e.apellidos,
                d.estado AS disponibilidad
            FROM asistencias a
            LEFT JOIN empleados e ON e.id = a.empleado_id
            LEFT JOIN disponibilidades d 
                ON d.empleado_id = a.empleado_id 
                AND d.fecha = a.fecha
            ORDER BY a.fecha DESC
        ";

        $rows = $db->query($sql)->fetchAll();

        $inconsistencias = [];

        foreach ($rows as $r) {

            if ($r['disponibilidad'] === null) {
                $r['motivo'] = "Sin disponibilidad registrada.";
                $inconsistencias[] = $r;
                continue;
            }

            if ($r['disponibilidad'] === 'descanso' && $r['hora_entrada'] !== null) {
                $r['motivo'] = "Marcó entrada estando en descanso.";
                $inconsistencias[] = $r;
                continue;
            }

            if ($r['disponibilidad'] === 'ausente' && $r['hora_entrada'] !== null) {
                $r['motivo'] = "Asistencia registrada siendo ausente.";
                $inconsistencias[] = $r;
                continue;
            }

            if ($r['disponibilidad'] === 'especial') {
                $r['motivo'] = "Entrada en día especial (verificar autorización).";
                $inconsistencias[] = $r;
                continue;
            }
        }

        $this->view('asistencia/inconsistencias', compact('inconsistencias'));
    }

    /* ====================================================
     * ASISTENCIAS PENDIENTES (RH / ADMIN)
     * ==================================================== */
    public function pendientes() {
        Auth::authorize(['admin', 'rh']);
        $pendientes = Asistencia::getPendientes();
        return $this->view('asistencia/pendientes', compact('pendientes'));
    }

    public function justificar($id) {
        Auth::authorize(['admin', 'rh']);

        $asistencia = Asistencia::find($id);
        if (!$asistencia) {
            die("Asistencia no encontrada");
        }

        Asistencia::actualizarEstado($id, 'justificada');

        return $this->redirect('/SGRH_PHP_MVC/public/?route=asistencias_pendientes&msg=justificada');
    }

    public function completar($id) {
        Auth::authorize(['admin', 'rh']);

        $asistencia = Asistencia::find($id);
        if (!$asistencia) {
            die("Asistencia no encontrada");
        }

        Asistencia::actualizarEstado($id, 'completa');

        return $this->redirect('/SGRH_PHP_MVC/public/?route=asistencias_pendientes&msg=completada');
    }

}
