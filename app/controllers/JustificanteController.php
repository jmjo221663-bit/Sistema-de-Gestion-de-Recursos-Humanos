<?php

require_once __DIR__ . '/../models/Empleado.php';
require_once __DIR__ . '/../models/Departamento.php';
require_once __DIR__ . '/../models/User.php';

class JustificanteController extends Controller {

    private $uploadDir;

    public function __construct() {

        // Ruta limpia desde la raíz del proyecto
        $root = dirname(__DIR__, 2);

        // Construir la ruta correcta usando DIRECTORY_SEPARATOR
        $this->uploadDir =
            $root . DIRECTORY_SEPARATOR .
            'public' . DIRECTORY_SEPARATOR .
            'uploads' . DIRECTORY_SEPARATOR .
            'justificantes';

        // Crear carpeta si no existe
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
    }

    // Vista general (admin / rh)
    public function index() {
        Auth::authorize(['admin', 'rh']);
        $justificantes = Justificante::todos();
        $this->view('justificante/index', compact('justificantes'));
    }

    // Lista del empleado (panel)
    public function panelEmpleado() {
        Auth::authorize(['empleado', 'rh', 'admin']);
        $u = Auth::user();

        // Si es admin o RH → redirige a vista global
        if (in_array($u['role'], ['admin', 'rh'])) {
            return $this->redirect('/SGRH_PHP_MVC/public/?route=justificantes');
        }

        // Si es empleado, solo sus justificantes
        $justificantes = Justificante::porEmpleado($u['id']);
        $this->view('justificante/panel', compact('u', 'justificantes'));
    }

    // Subir nuevo justificante (empleado)
   public function crear() {
    Auth::authorize(['empleado', 'rh', 'admin']);
    $u = Auth::user();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $this->verifyCsrf();

        // Validaciones
        $motivo = trim($_POST['motivo'] ?? '');
        $fecha_inicio = $_POST['fecha_inicio'] ?? '';
        $fecha_fin = $_POST['fecha_fin'] ?? '';

        if (!$motivo || !$fecha_inicio || !$fecha_fin) {
            $error = "Completa motivo, fecha inicio y fecha fin.";
            return $this->view('justificante/crear', compact('u', 'error'));
        }

        if ($fecha_fin < $fecha_inicio) {
            $error = "La fecha fin debe ser igual o posterior a la fecha inicio.";
            return $this->view('justificante/crear', compact('u', 'error'));
        }

        // Archivo
        if (empty($_FILES['archivo']) || $_FILES['archivo']['error'] !== UPLOAD_ERR_OK) {
            $error = "Sube un archivo PDF válido.";
            return $this->view('justificante/crear', compact('u', 'error'));
        }

        $file = $_FILES['archivo'];

        // Validar tipo y tamaño
        $maxBytes = 5 * 1024 * 1024; // 5MB
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if ($mime !== 'application/pdf') {
            $error = "Solo se permiten archivos PDF.";
            return $this->view('justificante/crear', compact('u', 'error'));
        }

        if ($file['size'] > $maxBytes) {
            $error = "El archivo excede el tamaño máximo de 5 MB.";
            return $this->view('justificante/crear', compact('u', 'error'));
        }

        // ======= OBTENER EL EMPLEADO REAL VINCULADO AL USUARIO =======
        $empleado = Empleado::buscarPorUserId($u['id']);

        if (!$empleado) {
            $error = "Tu cuenta no está vinculada a un empleado.";
            return $this->view('justificante/crear', compact('u','error'));
        }

        $empleado_id = $empleado['id'];
        // =============================================================

        // Generar nombre único
        $ext = '.pdf';
        $safeName = bin2hex(random_bytes(8)) . '_' . time() . $ext;

        // Ruta destino real
        $dest = $this->uploadDir . DIRECTORY_SEPARATOR . $safeName;

        // Guardar archivo
        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            $error = "Error al guardar el archivo en el servidor.";
            return $this->view('justificante/crear', compact('u', 'error'));
        }

        // Insertar BD
        try {
            Justificante::crear([
                'empleado_id' => $empleado_id, // <-- AHORA SÍ EL CORRECTO
                'motivo' => $motivo,
                'archivo' => 'uploads/justificantes/' . $safeName,
                'fecha_inicio' => $fecha_inicio,
                'fecha_fin' => $fecha_fin
            ]);

            return $this->redirect('/SGRH_PHP_MVC/public/?route=panel_justificante');

        } catch (PDOException $e) {
            if (file_exists($dest)) unlink($dest);
            $error = "Error BD: " . $e->getMessage();
            return $this->view('justificante/crear', compact('u', 'error'));
        }
    }

    // GET → mostrar formulario
    return $this->view('justificante/crear', compact('u'));
}

    // Ver justificante (admin / empleado)
    public function ver() {
        Auth::authorize(['admin', 'rh', 'empleado']);
        $id = intval($_GET['id'] ?? 0);

        $j = Justificante::buscar($id);
        if (!$j) {
            http_response_code(404);
            echo "Justificante no encontrado.";
            return;
        }

        // Control de acceso
        $u = Auth::user();
        if (!in_array($u['role'], ['admin', 'rh']) && $u['id'] != $j['empleado_id']) {
            http_response_code(403);
            echo "Acceso denegado.";
            return;
        }

        // Archivo real
        $fullPath = realpath(__DIR__ . '/../../public/' . $j['archivo']);
        if (!$fullPath || !file_exists($fullPath)) {
            http_response_code(404);
            echo "Archivo no encontrado.";
            return;
        }

        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . basename($fullPath) . '"');
        readfile($fullPath);
    }


public function historial() {
    Auth::authorize(['empleado']);

    $u = Auth::user();

    // obtener empleado real
    $empleado = Empleado::buscarPorUserId($u['id']);
    if (!$empleado) {
        echo "No estás vinculado a un empleado.";
        return;
    }

    $justificantes = Justificante::porEmpleado($empleado['id']);

    $this->view('justificante/historial', compact('justificantes', 'u'));
}



    // Cambiar estado
   public function cambiarEstado() {
    Auth::authorize(['admin','rh']);
    $this->verifyCsrf();

    $id = intval($_POST['id'] ?? 0);
    $estado = $_POST['estado'] ?? '';

    if (!in_array($estado, ['aprobado','rechazado'])) {
        return $this->redirect('/SGRH_PHP_MVC/public/?route=justificantes');
    }

    // Cambiar estado del justificante
            Justificante::cambiarEstado($id, $estado);

            // Si se aprueba → aplicar automáticamente a asistencias
            
    if ($estado === 'aprobado') {

    $j = Justificante::buscar($id);

    Asistencia::procesarJustificante(
        $j['empleado_id'],
        $j['fecha_inicio'],
        $j['fecha_fin']
    );
}



        // Redirigir
        return $this->redirect('/SGRH_PHP_MVC/public/?route=justificantes');
}





    // Eliminar justificante
    public function eliminar() {
        Auth::authorize(['admin', 'rh', 'empleado']);
        $this->verifyCsrf();

        $id = intval($_POST['id'] ?? 0);
        $j = Justificante::buscar($id);

        if (!$j) {
            return $this->redirect('/SGRH_PHP_MVC/public/?route=justificantes');
        }

        $u = Auth::user();
        if (!in_array($u['role'], ['admin', 'rh']) && $u['id'] != $j['empleado_id']) {
            http_response_code(403);
            echo "Acceso denegado.";
            return;
        }

        // borrar archivo físico
        $full = realpath(__DIR__ . '/../../public/' . $j['archivo']);
        if ($full && file_exists($full)) {
            unlink($full);
        }

        Justificante::eliminar($id);

        if (in_array($u['role'], ['admin', 'rh'])) {
            $this->redirect('/SGRH_PHP_MVC/public/?route=justificantes');
        } else {
            $this->redirect('/SGRH_PHP_MVC/public/?route=panel_justificante');
        }
    }
}
