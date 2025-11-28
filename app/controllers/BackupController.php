<?php
require_once __DIR__ . '/../config/Database.php';

class BackupController
{
    private PDO $conn;
    private string $backupDir;

    public function __construct()
    {
        $this->conn = Database::getInstance();
        $this->backupDir = __DIR__ . '/../../backups';

        if (!is_dir($this->backupDir)) {
            mkdir($this->backupDir, 0775, true);
        }
    }

    // ======================================================
    // GENERAR RESPALDO
    // ======================================================
    public function generar()
    {
        $filename = 'backup_' . date('Y_m_d_H_i_s') . '.sql';
        $filePath = $this->backupDir . '/' . $filename;

        $sqlDump = $this->exportDatabase();

        if (file_put_contents($filePath, $sqlDump)) {
            header('Content-Type: application/sql');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            readfile($filePath);
            exit;
        } else {
            echo "❌ Error al generar el respaldo.";
        }
    }

    // Exportar toda la BD
    private function exportDatabase(): string
    {
        $output = "-- Respaldo SGRH\n";
        $output .= "-- Fecha: " . date('Y-m-d H:i:s') . "\n\n";
        $output .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

        $tablesRes = $this->conn->query('SHOW TABLES');
        while ($row = $tablesRes->fetch(PDO::FETCH_NUM)) {
            $table = $row[0];

            // Estructura
            $createRes = $this->conn->query("SHOW CREATE TABLE `$table`");
            $createRow = $createRes->fetch(PDO::FETCH_ASSOC);

            $output .= "DROP TABLE IF EXISTS `$table`;\n";
            $output .= $createRow['Create Table'] . ";\n\n";

            // Datos
            $dataRes = $this->conn->query("SELECT * FROM `$table`");
            while ($dataRow = $dataRes->fetch(PDO::FETCH_ASSOC)) {
                $vals = array_map([$this->conn, 'quote'], array_values($dataRow));
                $output .= "INSERT INTO `$table` VALUES(" . implode(',', $vals) . ");\n";
            }

            $output .= "\n";
        }

        $output .= "SET FOREIGN_KEY_CHECKS=1;\n";
        return $output;
    }

    // ======================================================
    // FORMULARIO DE RESTAURACION
    // ======================================================
    public function formularioRestaurar()
    {
        if ($_SESSION['user']['role'] !== 'admin') {
            die("Acceso denegado");
        }

        require __DIR__ . '/../views/backup/restaurar.php';
    }

    // ======================================================
    // RESTAURAR BASE DE DATOS
    // ======================================================
    public function restaurar()
    {
        if ($_SESSION['user']['role'] !== 'admin') {
            die("Acceso denegado");
        }

        if (!isset($_FILES['sql_file']) || $_FILES['sql_file']['error'] !== UPLOAD_ERR_OK) {
            $error = "Debe seleccionar un archivo .sql válido";
            return $this->mostrarVista('restaurar', compact('error'));
        }

        $sqlFile = $_FILES['sql_file']['tmp_name'];
        $sqlContent = file_get_contents($sqlFile);

        if (!$sqlContent) {
            $error = "No se pudo leer el archivo SQL.";
            return $this->mostrarVista('restaurar', compact('error'));
        }

        try {
            $this->conn->exec("SET FOREIGN_KEY_CHECKS = 0;");

            $queries = array_filter(array_map('trim', explode(";", $sqlContent)));

            foreach ($queries as $query) {
                if ($query !== '') {
                    $this->conn->exec($query . ";");
                }
            }

            $this->conn->exec("SET FOREIGN_KEY_CHECKS = 1;");

            $success = "La base de datos fue restaurada correctamente.";
            return $this->mostrarVista('restaurar', compact('success'));

        } catch (Exception $e) {
            $error = "❌ Error al restaurar: " . $e->getMessage();
            return $this->mostrarVista('restaurar', compact('error'));
        }
    }

    // ======================================================
    // CARGAR VISTAS DEL MODULO
    // ======================================================
    private function mostrarVista($vista, $data = [])
    {
        extract($data);
        require __DIR__ . '/../views/backup/' . $vista . '.php';
    }
}
