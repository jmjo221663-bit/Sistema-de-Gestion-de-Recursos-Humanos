<?php

class Disponibilidad {

    // Obtener TODAS las disponibilidades
    public static function todas() {
        $db = Database::getInstance();
        $sql = "
            SELECT d.*, e.nombre, e.apellidos
            FROM disponibilidades d
            JOIN empleados e ON e.id = d.empleado_id
            ORDER BY d.fecha DESC
        ";
        return $db->query($sql)->fetchAll();
    }

    // Obtener todas las disponibilidades de un empleado
    public static function todasPorEmpleado($empleado_id) {
        $db = Database::getInstance();
        $stmt = $db->prepare("
            SELECT *
            FROM disponibilidades
            WHERE empleado_id = ?
            ORDER BY fecha ASC
        ");
        $stmt->execute([$empleado_id]);
        return $stmt->fetchAll();
    }

    // Buscar disponibilidad por ID (PARA EDITAR)
    public static function buscar($id) {
        $db = Database::getInstance();
        $stmt = $db->prepare("
            SELECT *
            FROM disponibilidades
            WHERE id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Obtener registro exacto por fecha
    public static function obtener($empleado_id, $fecha) {
        $db = Database::getInstance();
        $stmt = $db->prepare("
            SELECT *
            FROM disponibilidades
            WHERE empleado_id = ? AND fecha = ?
        ");
        $stmt->execute([$empleado_id, $fecha]);
        return $stmt->fetch();
    }

    // Obtener solo el estado de la disponibilidad
    public static function obtenerEstado($empleado_id, $fecha) {
        $db = Database::getInstance();
        $stmt = $db->prepare("
            SELECT estado
            FROM disponibilidades
            WHERE empleado_id = ? AND fecha = ?
        ");
        $stmt->execute([$empleado_id, $fecha]);
        $res = $stmt->fetch();
        return $res['estado'] ?? null;
    }

    // Crear disponibilidad
    public static function crear($data) {
        $db = Database::getInstance();
        $stmt = $db->prepare("
            INSERT INTO disponibilidades (empleado_id, fecha, estado, comentario)
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['empleado_id'],
            $data['fecha'],
            $data['estado'],
            $data['comentario'] ?? null
        ]);
    }

    // Actualizar disponibilidad
    public static function actualizar($id, $data) {
        $db = Database::getInstance();
        $stmt = $db->prepare("
            UPDATE disponibilidades
            SET empleado_id = ?, fecha = ?, estado = ?, comentario = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['empleado_id'],
            $data['fecha'],
            $data['estado'],
            $data['comentario'] ?? null,
            $id
        ]);
    }

    // Eliminar disponibilidad
    public static function eliminar($id) {
        $db = Database::getInstance();
        $stmt = $db->prepare("DELETE FROM disponibilidades WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
