<?php
class EmpleadosController extends Controller {

    public function index() {
        Auth::authorize(['admin','rh']);
        $empleados = Empleado::all();
        $departamentos = Departamento::all();
        $this->view('empleados/index', compact('empleados','departamentos'));
    }

 public function create() {
    Auth::authorize(['admin','rh']);
    $departamentos = Departamento::all();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $this->verifyCsrf();

        // Normalización
        $nombre = ucwords(strtolower(trim($_POST['nombre'] ?? '')));
        $apellidos = ucwords(strtolower(trim($_POST['apellidos'] ?? '')));

        $genero = $_POST['genero'] ?? '';
        $correo = filter_input(INPUT_POST, 'correo', FILTER_VALIDATE_EMAIL);
        $curp = strtoupper(trim($_POST['curp'] ?? ''));
        $puesto = trim($_POST['puesto'] ?? '');
        $departamento_id = intval($_POST['departamento_id'] ?? 0);
        $estado = $_POST['estado'] ?? 'activo';

        // Validaciones (igual que antes)
        if (!$nombre || !$apellidos || !$genero || !$correo || strlen($curp) !== 18 || !$departamento_id) {
            $error = "Revisa los campos (género, CURP 18 caracteres, correo válido, departamento).";
            return $this->view('empleados/create', compact('error','departamentos'));
        }

        // Validar correo empleado y user 
        $checkEmp = Database::getInstance()->prepare("SELECT id FROM empleados WHERE correo = ?");
        $checkEmp->execute([$correo]);
        if ($checkEmp->fetch()) {
            $error = "El correo ya está registrado como empleado.";
            return $this->view('empleados/create', compact('error','departamentos'));
        }

        $checkUsr = Database::getInstance()->prepare("SELECT id FROM users WHERE email = ?");
        $checkUsr->execute([$correo]);
        if ($checkUsr->fetch()) {
            $error = "El correo ya está usado como usuario del sistema.";
            return $this->view('empleados/create', compact('error','departamentos'));
        }

        // 1️ Crear USUARIO primero
        $passwordTemp = strtolower(explode(' ', $nombre)[0]) . rand(1000, 9999);

        $user_id = User::create([
            'name' => "$nombre $apellidos",
            'email' => $correo,
            'password' => $passwordTemp,
            'role' => 'empleado',
            'active' => 1
        ]);

        // 2️ Crear EMPLEADO con user_id asignado
        Empleado::create([
            'nombre' => $nombre,
            'apellidos' => $apellidos,
            'genero' => $genero,
            'correo' => $correo,
            'curp' => $curp,
            'puesto' => $puesto,
            'departamento_id' => $departamento_id,
            'estado' => $estado,
            'user_id' => $user_id  
        ]);

        $_SESSION['success'] = "Empleado registrado. Usuario creado:<br>
                                <strong>$correo</strong><br>
                                Contraseña temporal: <strong>$passwordTemp</strong>";

        return $this->redirect('/SGRH_PHP_MVC/public/?route=empleados');
    }

    $this->view('empleados/create', compact('departamentos'));
}


    public function edit() {
        Auth::authorize(['admin','rh']);

        $id = intval($_GET['id'] ?? 0);
        $empleado = Empleado::find($id);
        $departamentos = Departamento::all();

        if (!$empleado) { 
            http_response_code(404); 
            echo "No encontrado"; 
            return; 
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $this->verifyCsrf();

            // Normalización
            $nombre = ucwords(strtolower(trim($_POST['nombre'] ?? '')));
            $apellidos = ucwords(strtolower(trim($_POST['apellidos'] ?? '')));

            // Campos
            $genero = $_POST['genero'] ?? '';
            $correo = filter_input(INPUT_POST, 'correo', FILTER_VALIDATE_EMAIL);
            $curp = strtoupper(trim($_POST['curp'] ?? ''));
            $puesto = trim($_POST['puesto'] ?? '');
            $departamento_id = intval($_POST['departamento_id'] ?? 0);
            $estado = $_POST['estado'] ?? 'activo';

            // Validar correo en EMPLEADOS (excepto él)
            $checkEmp = Database::getInstance()->prepare(
                "SELECT id FROM empleados WHERE correo = ? AND id != ?"
            );
            $checkEmp->execute([$correo, $id]);
            if ($checkEmp->fetch()) {
                $error = "El correo ya está en uso por otro empleado.";
                return $this->view('empleados/edit', compact('empleado','departamentos'));
            }

            // Validación en users SOLO si cambió
            if ($correo !== $empleado['correo']) {
                $checkUsr = Database::getInstance()->prepare("SELECT id FROM users WHERE email = ?");
                $checkUsr->execute([$correo]);
                if ($checkUsr->fetch()) {
                    $error = "Este correo ya está registrado como usuario del sistema.";
                    return $this->view('empleados/edit', compact('empleado','departamentos'));
                }
            }

            // Actualizar EMPLEADO
            Empleado::update($id, [
                'nombre' => $nombre,
                'apellidos' => $apellidos,
                'genero' => $genero,
                'correo' => $correo,
                'curp' => $curp,
                'puesto' => $puesto,
                'departamento_id' => $departamento_id,
                'estado' => $estado,
                'user_id' => $empleado['user_id']  // No cambia
            ]);

            // Sincronizar usuario 
            if (!empty($empleado['user_id'])) {
                $user = User::find($empleado['user_id']);

                User::update($user['id'], [
                    'name' => $nombre . ' ' . $apellidos,
                    'email' => $correo,
                    'password' => '',       // no tocar contraseña
                    'role' => $user['role'],
                    'active' => $user['active']
                ]);
            }

            return $this->redirect('/SGRH_PHP_MVC/public/?route=empleados');
        }

        $this->view('empleados/edit', compact('empleado','departamentos'));
    }


    public function delete() {
        Auth::authorize(['admin','rh']);
        $this->verifyCsrf();
        $id = intval($_POST['id'] ?? 0);
        Empleado::delete($id);
        $this->redirect('/SGRH_PHP_MVC/public/?route=empleados');
    }

}
