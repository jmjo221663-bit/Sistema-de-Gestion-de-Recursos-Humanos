<?php

class Auth {

    public static function user() {
        return $_SESSION['user'] ?? null;
    }

    public static function check() {
        return isset($_SESSION['user']);
    }

    public static function authorize($roles = []) {
        if (!self::check()) {
            header("Location: /SGRH_PHP_MVC/public/?route=login");
            exit;
        }

        $user = self::user();

        if (!in_array($user['role'], $roles)) {
            echo "<h3>Acceso denegado</h3>";
            exit;
        }
    }

   public static function attempt($email, $password) {

    $user = User::findByEmail($email);

    if ($user && password_verify($password, $user['password_hash'])) {

        // Buscar si este usuario ya tiene un empleado asociado
        $empleado = Empleado::findByUserId($user['id']);

        // SI NO EXISTE → Se crea automáticamente
        if (!$empleado && $user['role'] === 'empleado') {

            // Crear empleado automático
            $empleado_id = Empleado::crearAutoDesdeUsuario($user);

            // Forzar obtención del nuevo empleado
            $empleado = Empleado::find($empleado_id);
        }

        // Guardar sesión
        $_SESSION['user'] = [
            'id'           => $user['id'],
            'name'         => $user['name'],
            'email'        => $user['email'],
            'role'         => $user['role'],
            'empleado_id'  => $empleado['id'] ?? null
        ];

        return true;
    }

    return false;
}


    public static function logout() {
        session_destroy();
        header("Location: /SGRH_PHP_MVC/public/?route=login");
        exit;
    }
}
