<?php
require_once __DIR__ . '/../models/User.php';

class LoginController extends Controller {

    public function index() {
        // Si el usuario ya está logueado, lo mandamos al dashboard
        if (!empty($_SESSION['user_id'])) {
            return $this->redirect('/SGRH_PHP_MVC/public/?route=dashboard');
        }

        // Mostrar login
        $this->view('auth/login');
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect('/SGRH_PHP_MVC/public/?route=login');
        }

        $this->verifyCsrf();

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = User::findByEmail($email);

        // Verificar si existe usuario
        if (!$user || !password_verify($password, $user['password_hash'])) {
            $error = "Correo o contraseña incorrectos.";
            return $this->view('auth/login', compact('error'));
        }

        // 🔥 Verificar si debe cambiar la contraseña
        if ($user['must_change_password'] == 1) {
            $_SESSION['user_id_temp'] = $user['id'];  
            return $this->redirect('/SGRH_PHP_MVC/public/?route=cambiar_password');
        }

        // Login normal
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        return $this->redirect('/SGRH_PHP_MVC/public/?route=dashboard');
    }

    // 🔥 Método para cambiar contraseña obligatorio en primer login
    public function cambiar_password() {

        // Usuario temporal
        if (empty($_SESSION['user_id_temp'])) {
            return $this->redirect('/SGRH_PHP_MVC/public/?route=login');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $this->verifyCsrf();

            $pass1 = trim($_POST['password'] ?? '');
            $pass2 = trim($_POST['password_confirm'] ?? '');

            if ($pass1 !== $pass2) {
                $error = "Las contraseñas no coinciden.";
                return $this->view('auth/cambiar_password', compact('error'));
            }

            // Actualizar contraseña
            User::update($_SESSION['user_id_temp'], [
                'name' => '',
                'email' => '',
                'password' => $pass1,
                'role' => '',
                'active' => 1
            ]);

            // Desactivar obligación
            $db = Database::getInstance();
            $stmt = $db->prepare("UPDATE users SET must_change_password = 0 WHERE id = ?");
            $stmt->execute([$_SESSION['user_id_temp']]);

            // Convertir usuario temporal → usuario logueado
            $_SESSION['user_id'] = $_SESSION['user_id_temp'];
            unset($_SESSION['user_id_temp']);

            $_SESSION['success'] = "Contraseña actualizada correctamente.";
            return $this->redirect('/SGRH_PHP_MVC/public/?route=dashboard');
        }

        $this->view('auth/cambiar_password');
    }

    public function logout() {
        session_destroy();
        return $this->redirect('/SGRH_PHP_MVC/public/?route=login');
    }

}

