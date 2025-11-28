<?php
class Empleado {

    // Obtener todos los empleados
    public static function all() {
        $db = Database::getInstance();
        $sql = "SELECT e.*, d.nombre AS departamento 
                FROM empleados e 
                LEFT JOIN departamentos d ON d.id = e.departamento_id 
                ORDER BY e.id DESC";
        return $db->query($sql)->fetchAll();
    }

    // Crear empleado
    public static function create($data) {
        $db = Database::getInstance();
        $stmt = $db->prepare("
            INSERT INTO empleados (user_id, nombre, apellidos, genero, correo, curp, puesto, departamento_id, estado)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $data['user_id'],
            $data['nombre'],
            $data['apellidos'],
            $data['genero'],
            $data['correo'],
            $data['curp'],
            $data['puesto'],
            $data['departamento_id'],
            $data['estado']
        ]);

        return $db->lastInsertId();
    }
    
    public static function contarActivos() {
    $db = Database::getInstance();
    $sql = "SELECT COUNT(*) AS total FROM empleados WHERE estado = 'activo'";
    return $db->query($sql)->fetch()['total'];
}


    // Buscar por ID
    public static function find($id) {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM empleados WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Buscar empleado por user_id (IMPORTANTE)
  public static function findByUserId($user_id) {
    $db = Database::getInstance();
    $stmt = $db->prepare("SELECT * FROM empleados WHERE user_id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetch();
}

public static function crearAutoDesdeUsuario($user)
{
    $db = Database::getInstance();

    $stmt = $db->prepare("
        INSERT INTO empleados (nombre, apellidos, correo, curp, puesto, estado, user_id)
        VALUES (?, ?, ?, ?, ?, 'activo', ?)
    ");

    // Datos mínimos generados automáticamente
    $nombreCompleto = explode(' ', $user['name']);
    $nombre = $nombreCompleto[0];
    $apellidos = isset($nombreCompleto[1]) ? $nombreCompleto[1] : '---';

    $fakeCurp = 'GEN' . rand(100000, 999999) . 'AUTO';  

    $stmt->execute([
        $nombre,
        $apellidos,
        $user['email'],
        $fakeCurp,
        'Empleado',
        $user['id']
    ]);

    return $db->lastInsertId();
}


    public static function buscarPorUserId($user_id) {
    $db = Database::getInstance();
    $stmt = $db->prepare("SELECT * FROM empleados WHERE user_id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetch();
}


    // Actualizar empleado
    public static function update($id, $data) {
        $db = Database::getInstance();
        $sql = "UPDATE empleados 
                SET user_id = ?, nombre = ?, apellidos = ?, genero = ?, correo = ?, curp = ?, puesto = ?, 
                    departamento_id = ?, estado = ?
                WHERE id = ?";

        $stmt = $db->prepare($sql);

        $stmt->execute([
            $data['user_id'],
            $data['nombre'],
            $data['apellidos'],
            $data['genero'],
            $data['correo'],
            $data['curp'],
            $data['puesto'],
            $data['departamento_id'],
            $data['estado'],
            $id
        ]);
    }

    // Eliminar
    public static function delete($id) {
        $db = Database::getInstance();
        $stmt = $db->prepare("DELETE FROM empleados WHERE id = ?");
        $stmt->execute([$id]);
    }
}
