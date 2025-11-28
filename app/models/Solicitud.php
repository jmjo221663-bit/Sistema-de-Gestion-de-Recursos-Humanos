
<?php
class Solicitud {
    public static function all() {
        $db = Database::getInstance();
        $sql = "SELECT s.*, e.nombre AS nombre_empleado, e.apellidos FROM solicitudes s LEFT JOIN empleados e ON e.id = s.empleado_id ORDER BY s.created_at DESC";
        return $db->query($sql)->fetchAll();
    }
    public static function create($data) {
        $db = Database::getInstance();
        $stmt = $db->prepare("INSERT INTO solicitudes(empleado_id,tipo,motivo,fecha_inicio,fecha_fin,estado) VALUES(?,?,?,?,?,?)");
        $stmt->execute([$data['empleado_id'],$data['tipo'],$data['motivo'],$data['fecha_inicio'],$data['fecha_fin'],'pendiente']);
    }
    public static function find($id) {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM solicitudes WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    public static function updateEstado($id,$estado) {
        $db = Database::getInstance();
        $stmt = $db->prepare("UPDATE solicitudes SET estado=? WHERE id=?");
        $stmt->execute([$estado,$id]);
    }
}
