<?php
class User {

    public static function all() {
        $db = Database::getInstance();
        return $db->query("SELECT id,name,email,role,active,created_at FROM users ORDER BY id DESC")->fetchAll();
    }

    public static function find($id) {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function findByEmail($email) {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public static function create($data) {
    $db = Database::getInstance();

    $stmt = $db->prepare("
        INSERT INTO users(name, email, password_hash, role, active, must_change_password)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    $hash = password_hash($data['password'], PASSWORD_BCRYPT);

    $stmt->execute([
        $data['name'],
        $data['email'],
        $hash,
        $data['role'],
        $data['active'] ? 1 : 0,
        1
    ]);

    // 🔥 REGRESAR ID DEL NUEVO USUARIO
    return $db->lastInsertId();
}


    // --- Actualizar datos del usuario ---
    public static function update($id, $data) {
        $db = Database::getInstance();

        // Con contraseña nueva
        if (!empty($data['password'])) {
            $hash = password_hash($data['password'], PASSWORD_BCRYPT);

            $stmt = $db->prepare("
                UPDATE users 
                SET name=?, email=?, password_hash=?, role=?, active=? 
                WHERE id=?
            ");

            $stmt->execute([
                $data['name'],
                $data['email'],
                $hash,
                $data['role'],
                $data['active'] ? 1 : 0,
                $id
            ]);

        } 
        // Sin contraseña nueva (actualización normal)
        else {

            $stmt = $db->prepare("
                UPDATE users
                SET name=?, email=?, role=?, active=?
                WHERE id=?
            ");

            $stmt->execute([
                $data['name'],
                $data['email'],
                $data['role'],
                $data['active'] ? 1 : 0,
                $id
            ]);
        }
    }

    public static function delete($id) {
        $db = Database::getInstance();
        $stmt = $db->prepare("DELETE FROM users WHERE id=?");
        $stmt->execute([$id]);
    }
}
