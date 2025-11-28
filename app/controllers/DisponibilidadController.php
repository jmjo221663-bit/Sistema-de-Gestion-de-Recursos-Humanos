<?php

require_once __DIR__ . '/../models/Disponibilidad.php';

class DisponibilidadController extends Controller {

    public function index() {
        Auth::authorize(['admin', 'rh', 'empleado']);

        $disponibilidades = Disponibilidad::todas();
        $this->view('disponibilidad/index', compact('disponibilidades'));
    }

    public function crear() {
        Auth::authorize(['admin','rh']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $data = [
                'empleado_id' => $_POST['empleado_id'],
                'fecha'       => $_POST['fecha'],
                'estado'      => $_POST['estado'],
                'comentario'  => $_POST['comentario'] ?? null
            ];

            Disponibilidad::crear($data);

            header("Location: /SGRH_PHP_MVC/public/?route=disponibilidad&msg=creada");
            exit;
        }

        $empleados = Empleado::all();
        $this->view('disponibilidad/nueva', compact('empleados'));
    }

    public function editar($id) {

        Auth::authorize(['admin', 'rh']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $data = [
                'empleado_id' => $_POST['empleado_id'],
                'fecha'       => $_POST['fecha'],
                'estado'      => $_POST['estado'],
                'comentario'  => $_POST['comentario'] ?? null
            ];

            Disponibilidad::actualizar($id, $data);

            header("Location: /SGRH_PHP_MVC/public/?route=disponibilidad&msg=actualizada");
            exit;
        }

        $disponibilidad = Disponibilidad::buscar($id);
        $empleados = Empleado::all();

        if (!$disponibilidad) {
            die("Disponibilidad no encontrada.");
        }

        $this->view('disponibilidad/editar', compact('disponibilidad', 'empleados'));
    }

    public function eliminar($id) {

        Auth::authorize(['admin','rh']);

        Disponibilidad::eliminar($id);

        header("Location: /SGRH_PHP_MVC/public/?route=disponibilidad&msg=eliminada");
        exit;
    }
}
