<?php
class Controller {
    protected function view(string $template, array $data = []) {
        extract($data);
        $viewPath = __DIR__ . '/../views/' . $template . '.php';
        include __DIR__ . '/../views/layout/header.php';
        include __DIR__ . '/../views/layout/nav.php';
        include $viewPath;
        include __DIR__ . '/../views/layout/footer.php';
    }

    protected function redirect(string $path) {
        header("Location: {$path}");
        exit;
    }

    // Genera el token CSRF si no existe
    protected function csrfToken() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['csrf'])) {
            $_SESSION['csrf'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf'];
    }

    // Verifica el token CSRF en los formularios POST
    protected function verifyCsrf() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            // acepta tanto 'csrf' como '_csrf' para compatibilidad
            $token = $_POST['_csrf'] ?? $_POST['csrf'] ?? '';

            if (!isset($_SESSION['csrf']) || $token !== $_SESSION['csrf']) {
                http_response_code(419);
                echo "CSRF token inválido.";
                exit;
            }
        }
    }
}
