<?php
class Justificante {

    // Obtener todos los justificantes (para admin o RH)
    public static function todos() {
        $db = Database::getInstance();
        $sql = "SELECT j.*, e.nombre, e.apellidos 
                FROM justificantes j
                JOIN empleados e ON e.id = j.empleado_id
                ORDER BY j.created_at DESC";
        return $db->query($sql)->fetchAll();
    }

    // Obtener justificantes por empleado
    public static function porEmpleado($empleado_id) {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM justificantes WHERE empleado_id = ? ORDER BY created_at DESC");
        $stmt->execute([$empleado_id]);
        return $stmt->fetchAll();
    }

    // Registrar un nuevo justificante
    public static function crear($data) {
        $db = Database::getInstance();
        $stmt = $db->prepare("INSERT INTO justificantes 
            (empleado_id, motivo, archivo, fecha_inicio, fecha_fin, estado) 
            VALUES (?,?,?,?,?, 'pendiente')");
        $stmt->execute([
            $data['empleado_id'],
            $data['motivo'],
            $data['archivo'],
            $data['fecha_inicio'],
            $data['fecha_fin']
        ]);
    }


    public static function contarPorEstado($estado) {
    $db = Database::getInstance();
    $stmt = $db->prepare("SELECT COUNT(*) total FROM justificantes WHERE estado = ?");
    $stmt->execute([$estado]);
    return $stmt->fetch()['total'];
}


                public static function justificarPrueba($empleado_id, $inicio, $fin) {

    $db = Database::getInstance();

    $f = strtotime($inicio);
    $fFin = strtotime($fin);

    while ($f <= $fFin) {
        $fecha = date('Y-m-d', $f);

        // Obtener disponibilidad del día
        $estadoDisp = Disponibilidad::obtenerEstado($empleado_id, $fecha);

        // Para pruebas:
        // MOSTRAR QUÉ VA PASANDO
        echo "Fecha: $fecha | Disponibilidad: $estadoDisp <br>";

        // Si NO hay disponibilidad → no tocar
        if (!$estadoDisp) {
            echo "→ No hay disponibilidad, se ignora.<br><br>";
            $f = strtotime('+1 day', $f);
            continue;
        }

        // Si es descanso → NO se justifica
        if ($estadoDisp === 'descanso') {
            echo "→ Día de descanso, NO se justifica.<br><br>";
            $f = strtotime('+1 day', $f);
            continue;
        }

        // Si tiene asistencia → actualizar
        $stmt = $db->prepare("SELECT id FROM asistencias WHERE empleado_id = ? AND fecha = ?");
        $stmt->execute([$empleado_id, $fecha]);
        $asis = $stmt->fetch();

        if ($asis) {
            echo "→ Tiene asistencia. Se marca como justificada.<br><br>";
            $stmt = $db->prepare("UPDATE asistencias SET estado = 'justificada' WHERE id = ?");
            $stmt->execute([$asis['id']]);
        } else {
            echo "→ NO tiene asistencia. Se crea justificada.<br><br>";
            $stmt = $db->prepare("
                INSERT INTO asistencias (empleado_id, fecha, estado)
                VALUES (?, ?, 'justificada')
            ");
            $stmt->execute([$empleado_id, $fecha]);
        }

        $f = strtotime('+1 day', $f);
    }

    exit; // para VER el resultado en pantalla
}


    // Obtener un justificante específico
    public static function buscar($id) {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM justificantes WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }


        public static function pendientesCount() {
    $db = Database::getInstance();
    $sql = "SELECT COUNT(*) AS total FROM justificantes WHERE estado = 'pendiente'";
    return $db->query($sql)->fetch()['total'];
}

    // Cambiar estado (aprobar o rechazar)
    public static function cambiarEstado($id, $estado) {
        $db = Database::getInstance();
        $stmt = $db->prepare("UPDATE justificantes SET estado = ? WHERE id = ?");
        $stmt->execute([$estado, $id]);
    }

    // Eliminar justificante
    public static function eliminar($id) {
        $db = Database::getInstance();
        $stmt = $db->prepare("DELETE FROM justificantes WHERE id = ?");
        $stmt->execute([$id]);
    }
}
