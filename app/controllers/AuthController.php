<?php
class AuthController extends Controller {

    public function login() {
        // Asegurar que haya sesión
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Generar token CSRF si no existe
        if (empty($_SESSION['csrf'])) {
            $_SESSION['csrf'] = bin2hex(random_bytes(32));
        }

        // Si el método es POST, validar credenciales
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verifyCsrf();

            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $password = $_POST['password'] ?? '';

            if (!$email || !$password) {
                $error = "Datos inválidos";
                return $this->view('auth/login', compact('error'));
            }

            if (Auth::attempt($email, $password)) {
                return $this->redirect('/SGRH_PHP_MVC/public/?route=dashboard');
            }

            $error = "Usuario o contraseña incorrectos";
            return $this->view('auth/login', compact('error'));
        }

        // Mostrar vista de login
        return $this->view('auth/login');
    }

    public function logout() {
        Auth::logout();
        $this->redirect('/SGRH_PHP_MVC/public/?route=login');
    }
}