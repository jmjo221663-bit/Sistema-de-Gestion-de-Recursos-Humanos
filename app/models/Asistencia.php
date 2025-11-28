<?php
class Asistencia {

    // Obtener un registro de asistencia por ID
    public static function find($id) {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM asistencias WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    
    // Obtener todas las asistencias con estado "pendiente"
    public static function getPendientes() {
        $db = Database::getInstance();
        $sql = "
            SELECT a.*, e.nombre, e.apellidos
            FROM asistencias a
            JOIN empleados e ON e.id = a.empleado_id
            WHERE a.estado = 'pendiente'
            ORDER BY a.fecha DESC
        ";
        return $db->query($sql)->fetchAll();
    }

    public static function all() {
    $db = Database::getInstance();
    $sql = "
        SELECT a.*, e.nombre, e.apellidos
        FROM asistencias a
        JOIN empleados e ON e.id = a.empleado_id
        ORDER BY a.fecha DESC, a.hora_entrada DESC
    ";
    return $db->query($sql)->fetchAll();
}

public static function registrarEntradaEspecial($empleado_id) {
    $db = Database::getInstance();

    $stmt = $db->prepare("
        INSERT INTO asistencias (empleado_id, fecha, hora_entrada, estado)
        VALUES (?, CURDATE(), CURTIME(), 'justificada')
    ");

    $stmt->execute([$empleado_id]);
}

public static function getByEmpleado($empleado_id) {
    $db = Database::getInstance();
    $stmt = $db->prepare("SELECT * FROM asistencias WHERE empleado_id = ? ORDER BY fecha DESC");
    $stmt->execute([$empleado_id]);
    return $stmt->fetchAll();
}



    // Obtener asistencias por empleado
    public static function porEmpleado($empleado_id) {
        $db = Database::getInstance();
        $stmt = $db->prepare("
            SELECT *
            FROM asistencias
            WHERE empleado_id = ?
            ORDER BY fecha DESC
        ");
        $stmt->execute([$empleado_id]);
        return $stmt->fetchAll();
    }

    // Actualizar estado
    public static function actualizarEstado($id, $estado) {
        $db = Database::getInstance();
        $stmt = $db->prepare("
            UPDATE asistencias
            SET estado = ?, updated_at = NOW()
            WHERE id = ?
        ");
        return $stmt->execute([$estado, $id]);
    }

    public static function justificarSegunDisponibilidad($empleado_id, $inicio, $fin) {
    // 1. obtener las fechas donde el empleado sí trabaja
    $fechas = Disponibilidad::fechasDisponibles($empleado_id, $inicio, $fin);

    if (empty($fechas)) {
        return; // no trabaja esos días
    }

    $db = Database::getInstance();

    foreach ($fechas as $fecha) {
        $stmt = $db->prepare("
            UPDATE asistencias 
            SET estado = 'justificada'
            WHERE empleado_id = ?
            AND fecha = ?
        ");
        $stmt->execute([$empleado_id, $fecha]);
    }
}


    // Registrar entrada
    public static function registrarEntrada($empleado_id) {
    $db = Database::getInstance();

    // 👉 Verificar si ya marcó asistencia hoy
    $stmt = $db->prepare("
        SELECT id 
        FROM asistencias 
        WHERE empleado_id = ? 
        AND fecha = CURDATE()
    ");
    $stmt->execute([$empleado_id]);
    $existe = $stmt->fetch();

    // 👉 Si ya existe, no truena: regresamos aviso
    if ($existe) {
        return [
            'ok' => false,
            'msg' => "Ya registraste tu entrada hoy."
        ];
    }

    // 👉 Si no existe → insertar nuevo registro
    $stmt = $db->prepare("
        INSERT INTO asistencias (empleado_id, fecha, hora_entrada, estado)
        VALUES (?, CURDATE(), CURTIME(), 'pendiente')
    ");

    $stmt->execute([$empleado_id]);

    return [
        'ok' => true,
        'msg' => "Entrada registrada correctamente."
    ];
}


                public static function procesarJustificante($empleado_id, $inicio, $fin) {

    $db = Database::getInstance();

    $fechaActual = strtotime($inicio);
    $fechaFin = strtotime($fin);

    while ($fechaActual <= $fechaFin) {

        $fecha = date('Y-m-d', $fechaActual);

        // Saber si el empleado trabaja ese día
        $estado = Disponibilidad::obtenerEstado($empleado_id, $fecha);

        // Si NO hay disponibilidad → ignorar (no trabaja)
        if (!$estado) {
            $fechaActual = strtotime('+1 day', $fechaActual);
            continue;
        }

        // REGLAS RH
        if ($estado === 'descanso' || $estado === 'especial') {
            // NO se justifica
            $fechaActual = strtotime('+1 day', $fechaActual);
            continue;
        }

        // Si llega aquí, significa que se puede justificar
        // Buscar asistencia del día
        $stmt = $db->prepare("
            SELECT id FROM asistencias 
            WHERE empleado_id = ? AND fecha = ?
        ");
        $stmt->execute([$empleado_id, $fecha]);
        $asistencia = $stmt->fetch();

        if ($asistencia) {
            // Actualizar asistencia existente
            $stmt = $db->prepare("
                UPDATE asistencias 
                SET estado = 'justificada'
                WHERE id = ?
            ");
            $stmt->execute([$asistencia['id']]);
        } else {
            // Crear asistencia faltante
            $stmt = $db->prepare("
                INSERT INTO asistencias (empleado_id, fecha, estado)
                VALUES (?, ?, 'justificada')
            ");
            $stmt->execute([$empleado_id, $fecha]);
        }

        $fechaActual = strtotime('+1 day', $fechaActual);
    }
}

    // Obtener todas las asistencias (vista general para Admin / RH)
public static function todas() {
    $db = Database::getInstance();
    $sql = "
        SELECT a.*, e.nombre, e.apellidos
        FROM asistencias a
        JOIN empleados e ON e.id = a.empleado_id
        ORDER BY a.fecha DESC
    ";
    return $db->query($sql)->fetchAll();
}

public static function contarPendientes() {
    $db = Database::getInstance();
    $sql = "SELECT COUNT(*) AS total FROM asistencias WHERE estado = 'pendiente'";
    return $db->query($sql)->fetch()['total'];
}

public static function contarCompletas() {
    $db = Database::getInstance();
    $sql = "SELECT COUNT(*) AS total FROM asistencias WHERE estado = 'completa'";
    return $db->query($sql)->fetch()['total'];
}

public static function justificarRango($empleado_id, $inicio, $fin) {
    $db = Database::getInstance();
    $stmt = $db->prepare("
        UPDATE asistencias 
        SET estado = 'justificado'
        WHERE empleado_id = ?
        AND fecha BETWEEN ? AND ?
    ");
    $stmt->execute([$empleado_id, $inicio, $fin]);
}


public static function contarJustificadas() {
    $db = Database::getInstance();
    $sql = "SELECT COUNT(*) AS total FROM asistencias WHERE estado = 'justificada'";
    return $db->query($sql)->fetch()['total'];
}


    // Registrar salida (completa)
    public static function registrarSalida($empleado_id) {
        $db = Database::getInstance();

        // Buscar registro pendiente del día
        $stmt = $db->prepare("
            SELECT id 
            FROM asistencias 
            WHERE empleado_id = ? AND fecha = CURDATE() AND estado = 'pendiente'
        ");
        $stmt->execute([$empleado_id]);
        $asistencia = $stmt->fetch();

        if (!$asistencia) return false;

        // Actualizar con hora de salida
        $stmt = $db->prepare("
            UPDATE asistencias
            SET hora_salida = CURTIME(), estado = 'completa', updated_at = NOW()
            WHERE id = ?
        ");

        return $stmt->execute([$asistencia['id']]);
    }

}
