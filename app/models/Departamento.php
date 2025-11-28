
<?php
class Departamento {
    public static function all() {
        $db = Database::getInstance();
        return $db->query("SELECT * FROM departamentos ORDER BY nombre ASC")->fetchAll();
    }
    public static function create($data) {
        $db = Database::getInstance();
        $stmt = $db->prepare("INSERT INTO departamentos(nombre, descripcion) VALUES(?,?)");
        $stmt->execute([$data['nombre'],$data['descripcion']]);
    }
    public static function find($id) {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM departamentos WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    public static function update($id,$data) {
        $db = Database::getInstance();
        $stmt = $db->prepare("UPDATE departamentos SET nombre=?, descripcion=? WHERE id=?");
        $stmt->execute([$data['nombre'],$data['descripcion'],$id]);
    }
    public static function delete($id) {
        $db = Database::getInstance();
        $stmt = $db->prepare("DELETE FROM departamentos WHERE id=?");
        $stmt->execute([$id]);
    }
}
