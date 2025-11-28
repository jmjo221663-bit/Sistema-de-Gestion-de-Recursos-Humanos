<?php
class DashboardController extends Controller {

    public function index() {
        Auth::authorize(['admin','rh','empleado']);

        $user = Auth::user();

        // Si es Admin o RH → mostrar panel completo
        if ($user['role'] === 'admin' || $user['role'] === 'rh') {

            // ---- ASISTENCIAS ----
            $pendientes = Asistencia::contarPendientes();
            $completas = Asistencia::contarCompletas();
            $justificadas = Asistencia::contarJustificadas();

            // ---- EMPLEADOS ----
            $empleadosActivos = Empleado::contarActivos();

            // ---- JUSTIFICANTES ----
            $justPend = Justificante::contarPorEstado('pendiente');
            $justApr  = Justificante::contarPorEstado('aprobado');
            $justRec  = Justificante::contarPorEstado('rechazado');

            return $this->view('dashboard/index', compact(
                'user',
                // asistencias
                'pendientes', 'completas', 'justificadas',
                // empleados
                'empleadosActivos',
                // justificantes
                'justPend', 'justApr', 'justRec'
            ));
        }

        // Si es empleado → panel simple
        return $this->view('dashboard/index', compact('user'));
    }
}
