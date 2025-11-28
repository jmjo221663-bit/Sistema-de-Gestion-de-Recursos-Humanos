
<?php
class GestionController extends Controller {
    public function solicitudes() {
        Auth::authorize(['admin','rh']);
        $solicitudes = Solicitud::all();
        $this->view('gestion/solicitudes_list', compact('solicitudes'));
    }
    public function crearSolicitud() {
        Auth::authorize(['admin','rh']);
        $empleados = Empleado::all();
        if ($_SERVER['REQUEST_METHOD']==='POST') {
            $this->verifyCsrf();
            $data = [
                'empleado_id'=>intval($_POST['empleado_id'] ?? 0),
                'tipo'=>$_POST['tipo'] ?? 'vacaciones',
                'motivo'=>trim($_POST['motivo'] ?? ''),
                'fecha_inicio'=>$_POST['fecha_inicio'] ?? null,
                'fecha_fin'=>$_POST['fecha_fin'] ?? null
            ];
            if (!$data['empleado_id'] || !$data['fecha_inicio'] || !$data['fecha_fin']) {
                $error="Selecciona empleado y fechas";
                return $this->view('gestion/solicitudes_create', compact('empleados','error'));
            }
            Solicitud::create($data);
            return $this->redirect('/SGRH_PHP_MVC/public/?route=solicitudes');
        }
        $this->view('gestion/solicitudes_create', compact('empleados'));
    }
    public function cambiarEstado() {
        Auth::authorize(['admin']);
        $this->verifyCsrf();
        $id = intval($_POST['id'] ?? 0);
        $estado = $_POST['estado'] ?? 'pendiente';
        Solicitud::updateEstado($id,$estado);
        $this->redirect('/SGRH_PHP_MVC/public/?route=solicitudes');
    }
}
