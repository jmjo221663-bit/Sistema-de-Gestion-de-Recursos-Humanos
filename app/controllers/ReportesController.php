<?php
require_once __DIR__ . '/../models/Asistencia.php';
require_once __DIR__ . '/../models/Disponibilidad.php';

$root = dirname(__DIR__, 2);
require_once $root . '/app/libs/fpdf.php';

class ReportesController extends Controller {

    public function index() {
        Auth::authorize(['admin', 'rh']);

        $db = Database::getInstance();

        // Listas para filtros
        $empleados = $db->query("SELECT id, nombre, apellidos FROM empleados ORDER BY nombre")->fetchAll();
        $departamentos = $db->query("SELECT id, nombre FROM departamentos ORDER BY nombre")->fetchAll();

        // Si no se envió POST → solo mostrar filtros
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->view('reportes/index', compact('empleados','departamentos'));
        }

        // Obtener filtros
        $empleado_id = $_POST['empleado_id'] ?? '';
        $departamento_id = $_POST['departamento_id'] ?? '';
        $fecha_inicio = $_POST['fecha_inicio'] ?? '';
        $fecha_fin = $_POST['fecha_fin'] ?? '';

        // Validación básica
        if (!$fecha_inicio || !$fecha_fin) {
            $error = "Debes seleccionar un rango de fechas.";
            return $this->view('reportes/index', compact('empleados','departamentos','error'));
        }

        // ======================================================
        // CONSULTA DE ASISTENCIAS
        // ======================================================
        $sqlAsistencias = "
            SELECT a.*, e.nombre, e.apellidos, d.nombre AS departamento
            FROM asistencias a
            JOIN empleados e ON e.id = a.empleado_id
            JOIN departamentos d ON d.id = e.departamento_id
            WHERE a.fecha BETWEEN ? AND ?
        ";

        $paramsA = [$fecha_inicio, $fecha_fin];

        if ($empleado_id) {
            $sqlAsistencias .= " AND e.id = ?";
            $paramsA[] = $empleado_id;
        }

        if ($departamento_id) {
            $sqlAsistencias .= " AND d.id = ?";
            $paramsA[] = $departamento_id;
        }

        $stmtA = $db->prepare($sqlAsistencias);
        $stmtA->execute($paramsA);
        $asistencias = $stmtA->fetchAll();

        // ======================================================
        // CONSULTA DE DISPONIBILIDAD
        // ======================================================
        $sqlDisp = "
            SELECT dis.*, e.nombre, e.apellidos, d.nombre AS departamento
            FROM disponibilidades dis
            JOIN empleados e ON e.id = dis.empleado_id
            JOIN departamentos d ON d.id = e.departamento_id
            WHERE dis.fecha BETWEEN ? AND ?
        ";

        $paramsD = [$fecha_inicio, $fecha_fin];

        if ($empleado_id) {
            $sqlDisp .= " AND e.id = ?";
            $paramsD[] = $empleado_id;
        }
        if ($departamento_id) {
            $sqlDisp .= " AND d.id = ?";
            $paramsD[] = $departamento_id;
        }

        $stmtD = $db->prepare($sqlDisp);
        $stmtD->execute($paramsD);
        $disponibilidades = $stmtD->fetchAll();

        // ======================================================
        // GRÁFICA 1: Línea simple (ya existía)
        // ======================================================
        $grafica_labels = [];
        $grafica_data = [];

        foreach ($asistencias as $a) {
            $grafica_labels[] = $a['fecha'];
            $grafica_data[] = ($a['estado'] === 'completa') ? 1 : 0;
        }

        $grafica_labels = json_encode($grafica_labels);
        $grafica_data = json_encode($grafica_data);

        // ======================================================
        // GRÁFICA 2: COMPARATIVA PROFESIONAL
        // ======================================================
        $empleados_graf = [];

        foreach ($asistencias as $a) {
            $empleado = $a['nombre'] . ' ' . $a['apellidos'];

            if (!isset($empleados_graf[$empleado])) {
                $empleados_graf[$empleado] = [
                    'completa' => 0,
                    'pendiente' => 0,
                    'justificada' => 0
                ];
            }

            $empleados_graf[$empleado][$a['estado']]++;
        }

        // Convertir datos a JSON para Chart.js
        $graf_empleados = json_encode(array_keys($empleados_graf));
        $graf_completas = json_encode(array_column($empleados_graf, 'completa'));
        $graf_pendientes = json_encode(array_column($empleados_graf, 'pendiente'));
        $graf_justificadas = json_encode(array_column($empleados_graf, 'justificada'));

        return $this->view('reportes/index', compact(
            'empleados',
            'departamentos',
            'asistencias',
            'disponibilidades',
            'fecha_inicio',
            'fecha_fin',
            'grafica_labels',
            'grafica_data',
            'graf_empleados',
            'graf_completas',
            'graf_pendientes',
            'graf_justificadas'
        ));
    }

    public function pdfTablas() {
    // Reusar tu PDF pero SOLO con tabla
    // (Puedo adaptarlo si lo quieres más pro)

    $this->generarPDF('tablas');
}

public function pdfGraficas() {
    // Manda bandera para generar dashboard visual en PDF
    $this->generarPDF('graficas');
}


    // ======================================================
    // GENERAR PDF
    // ======================================================
public function pdf() {
    Auth::authorize(['admin','rh']);

    $db = Database::getInstance();

    $empleado_id = $_GET['empleado_id'] ?? '';
    $fecha_inicio = $_GET['inicio'] ?? '';
    $fecha_fin = $_GET['fin'] ?? '';

    $sql = "
        SELECT a.*, e.nombre, e.apellidos, d.nombre AS departamento
        FROM asistencias a
        JOIN empleados e ON e.id = a.empleado_id
        JOIN departamentos d ON d.id = e.departamento_id
        WHERE a.fecha BETWEEN ? AND ?
    ";

    $params = [$fecha_inicio, $fecha_fin];

    if ($empleado_id) {
        $sql .= " AND e.id = ?";
        $params[] = $empleado_id;
    }

    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll();

    // ==============================
    // TOTALES
    // ==============================
    $total_completa = 0;
    $total_pendiente = 0;
    $total_justificada = 0;

    foreach ($rows as $r) {
        if ($r["estado"] == "completa") $total_completa++;
        if ($r["estado"] == "pendiente") $total_pendiente++;
        if ($r["estado"] == "justificada") $total_justificada++;
    }

    // ==============================
    // INICIAR PDF
    // ==============================
    $pdf = new FPDF();
    $pdf->AddPage();

    // ==============================
    // HOJA 1: TABLA
    // ==============================

    // HEADER PRO
    $pdf->SetFont('Arial','B',18);
    $pdf->SetTextColor(40,40,40);
    $pdf->Cell(0,10,utf8_decode("Reporte de Asistencias"),0,1,'C');
    $pdf->Ln(2);

    $pdf->SetDrawColor(0,102,204);
    $pdf->SetLineWidth(1);
    $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
    $pdf->Ln(5);

    $pdf->SetFont('Arial','I',11);
    $pdf->SetTextColor(80,80,80);
    $pdf->Cell(0,8,utf8_decode("Generado el: ".date("d/m/Y H:i")),0,1,'R');
    $pdf->Cell(0,8,utf8_decode("Rango: $fecha_inicio al $fecha_fin"),0,1,'L');
    $pdf->Ln(3);

    // ANCHOS
    $wEmpl = 40;
    $wDept = 55;
    $wFecha = 25;
    $wEnt = 25;
    $wSal = 25;
    $wEst = 20;

    // HEADER TABLA
    $pdf->SetFont('Arial','B',11);
    $pdf->SetFillColor(0,102,204);
    $pdf->SetTextColor(255,255,255);

    $pdf->Cell($wEmpl,10,"Empleado",1,0,'C',true);
    $pdf->Cell($wDept,10,"Departamento",1,0,'C',true);
    $pdf->Cell($wFecha,10,"Fecha",1,0,'C',true);
    $pdf->Cell($wEnt,10,"Entrada",1,0,'C',true);
    $pdf->Cell($wSal,10,"Salida",1,0,'C',true);
    $pdf->Cell($wEst,10,"Estado",1,1,'C',true);

    $pdf->SetFont('Arial','',10);
    $pdf->SetTextColor(0,0,0);

    // FILAS
    foreach ($rows as $r) {

        if ($r["estado"] === "completa") {
            $pdf->SetFillColor(198,239,206);
        } elseif ($r["estado"] === "pendiente") {
            $pdf->SetFillColor(255,235,156);
        } elseif ($r["estado"] === "justificada") {
            $pdf->SetFillColor(252,228,214);
        } else {
            $pdf->SetFillColor(255,255,255);
        }

        // Empleado
        $pdf->Cell($wEmpl,9,utf8_decode($r['nombre']." ".$r['apellidos']),1,0,'L',true);

        // MULTICELL Departamento
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->MultiCell($wDept,9,utf8_decode($r['departamento']),1,'L',true);
        $y2 = $pdf->GetY();
        $pdf->SetXY($x + $wDept, $y);

        // Resto columnas
        $pdf->Cell($wFecha,9,$r['fecha'],1,0,'C',true);
        $pdf->Cell($wEnt,9,$r['hora_entrada'],1,0,'C',true);
        $pdf->Cell($wSal,9,$r['hora_salida'],1,0,'C',true);
        $pdf->Cell($wEst,9,ucfirst($r['estado']),1,1,'C',true);

        if ($pdf->GetY() < $y2) $pdf->SetY($y2);
    }

    // ==============================
    // NUEVA HOJA
    // ==============================
    $pdf->AddPage();

    // ==============================
    // HOJA 2: TOTALES + GRAFICA + FIRMA
    // ==============================

    $pdf->SetFont('Arial','B',18);
    $pdf->SetTextColor(40,40,40);
    $pdf->Cell(0,10,"Resumen del periodo",0,1,'C');
    $pdf->Ln(3);

    // TOTALSEX
   $pdf->SetFont('Arial','',12);
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell(60,8,"Asistencias completas:",0,0);
    $pdf->Cell(20,8,$total_completa,0,1);

    $pdf->Cell(60,8,"Pendientes:",0,0);
    $pdf->Cell(20,8,$total_pendiente,0,1);

    $pdf->Cell(60,8,"Justificadas:",0,0);
    $pdf->Cell(20,8,$total_justificada,0,1);

    $pdf->Ln(10);

    // MINI GRAFICA
    $barX = 30;
    $barY = $pdf->GetY() + 5;
    $max = max($total_completa, $total_pendiente, $total_justificada);
    $scale = 80 / $max;

    // COMPLETAS
    $pdf->SetFillColor(198,239,206);
    $pdf->Rect($barX, $barY, $total_completa * $scale, 8, 'F');
    $pdf->SetXY($barX + 90, $barY);
    $pdf->Cell(60,8,"Completas: $total_completa");

    // PENDIENTES
    $barY += 12;
    $pdf->SetFillColor(255,235,156);
    $pdf->Rect($barX, $barY, $total_pendiente * $scale, 8, 'F');
    $pdf->SetXY($barX + 90, $barY);
    $pdf->Cell(60,8,"Pendientes: $total_pendiente");

    // JUSTIFICADAS
    $barY += 12;
    $pdf->SetFillColor(252,228,214);
    $pdf->Rect($barX, $barY, $total_justificada * $scale, 8, 'F');
    $pdf->SetXY($barX + 90, $barY);
    $pdf->Cell(60,8,"Justificadas: $total_justificada");

    $pdf->Ln(30);

    // FIRMA
    $pdf->Cell(0,10,"_____________________________________",0,1,'C');
    $pdf->Cell(0,6,utf8_decode("Firma del responsable"),0,1,'C');

    $pdf->Output();
}
private function generarPDF($tipo) {
    Auth::authorize(['admin','rh']);

    $db = Database::getInstance();

    $empleado_id = $_GET['empleado_id'] ?? '';
    $fecha_inicio = $_GET['inicio'] ?? '';
    $fecha_fin = $_GET['fin'] ?? '';

    // ==============================
    // CONSULTA PRINCIPAL
    // ==============================
    $sql = "
        SELECT a.*, e.nombre, e.apellidos, d.nombre AS departamento
        FROM asistencias a
        JOIN empleados e ON e.id = a.empleado_id
        JOIN departamentos d ON d.id = e.departamento_id
        WHERE a.fecha BETWEEN ? AND ?
    ";

    $params = [$fecha_inicio, $fecha_fin];

    if ($empleado_id) {
        $sql .= " AND e.id = ?";
        $params[] = $empleado_id;
    }

    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll();

    // ==============================
    // TOTALES
    // ==============================
    $total_completa = 0;
    $total_pendiente = 0;
    $total_justificada = 0;

    foreach ($rows as $r) {
        if ($r["estado"] == "completa") $total_completa++;
        if ($r["estado"] == "pendiente") $total_pendiente++;
        if ($r["estado"] == "justificada") $total_justificada++;
    }

    // ==============================
    // INIT PDF
    // ==============================
    $pdf = new FPDF();
    $pdf->AddPage();

    // HEADER PRO
    $pdf->SetFont('Arial','B',18);
    $pdf->SetTextColor(40,40,40);
    $pdf->Cell(0,10,utf8_decode("Reporte de Asistencias - ".ucfirst($tipo)),0,1,'C');
    $pdf->Ln(2);

    $pdf->SetDrawColor(0,102,204);
    $pdf->SetLineWidth(1);
    $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
    $pdf->Ln(5);

    $pdf->SetFont('Arial','I',11);
    $pdf->SetTextColor(80,80,80);
    $pdf->Cell(0,8,utf8_decode("Generado el: ".date("d/m/Y H:i")),0,1,'R');
    $pdf->Cell(0,8,utf8_decode("Rango: $fecha_inicio al $fecha_fin"),0,1,'L');
    $pdf->Ln(5);

    // =====================================================
    // MODO TABLAS (PRO)
    // =====================================================
    if ($tipo === "tablas") {

        // ANCHOS
        $wEmpl = 40;
        $wDept = 55;
        $wFecha = 25;
        $wEnt = 25;
        $wSal = 25;
        $wEst = 20;

        // HEADER TABLA
        $pdf->SetFont('Arial','B',11);
        $pdf->SetFillColor(0,102,204);
        $pdf->SetTextColor(255,255,255);

        $pdf->Cell($wEmpl,10,"Empleado",1,0,'C',true);
        $pdf->Cell($wDept,10,"Departamento",1,0,'C',true);
        $pdf->Cell($wFecha,10,"Fecha",1,0,'C',true);
        $pdf->Cell($wEnt,10,"Entrada",1,0,'C',true);
        $pdf->Cell($wSal,10,"Salida",1,0,'C',true);
        $pdf->Cell($wEst,10,"Estado",1,1,'C',true);

        $pdf->SetFont('Arial','',10);
        $pdf->SetTextColor(0,0,0);

        foreach ($rows as $r) {

            if ($r["estado"] === "completa") {
                $pdf->SetFillColor(198,239,206);
            } elseif ($r["estado"] === "pendiente") {
                $pdf->SetFillColor(255,235,156);
            } elseif ($r["estado"] === "justificada") {
                $pdf->SetFillColor(252,228,214);
            } else {
                $pdf->SetFillColor(255,255,255);
            }

            $pdf->Cell($wEmpl,9,utf8_decode($r['nombre']." ".$r['apellidos']),1,0,'L',true);

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell($wDept,9,utf8_decode($r['departamento']),1,'L',true);
            $y2 = $pdf->GetY();
            $pdf->SetXY($x + $wDept, $y);

            $pdf->Cell($wFecha,9,$r['fecha'],1,0,'C',true);
            $pdf->Cell($wEnt,9,$r['hora_entrada'],1,0,'C',true);
            $pdf->Cell($wSal,9,$r['hora_salida'],1,0,'C',true);
            $pdf->Cell($wEst,9,ucfirst($r['estado']),1,1,'C',true);

            if ($pdf->GetY() < $y2) $pdf->SetY($y2);
        }

        $pdf->Output();
        return;
    }

    // =====================================================
    // MODO GRAFICAS (PRO)
    // =====================================================
    if ($tipo === "graficas") {

        $pdf->SetFont('Arial','B',16);
        $pdf->Ln(10);
        $pdf->Cell(0,10,"Totales del periodo",0,1,'C');

        $pdf->SetFont('Arial','',12);
        $pdf->Ln(5);
        $pdf->Cell(0,8,"Asistencias completas: $total_completa",0,1);
        $pdf->Cell(0,8,"Pendientes: $total_pendiente",0,1);
        $pdf->Cell(0,8,"Justificadas: $total_justificada",0,1);

        $pdf->Ln(15);

        $barX = 30;
        $barY = $pdf->GetY();
        $max = max($total_completa, $total_pendiente, $total_justificada);
        $scale = 80 / max($max, 1);

        // Graficas PRO
        $pdf->SetFillColor(198,239,206);
        $pdf->Rect($barX, $barY, $total_completa * $scale, 10, 'F');
        $pdf->SetXY($barX + 90, $barY);
        $pdf->Cell(50,8,"Completas");

        $barY += 14;
        $pdf->SetFillColor(255,235,156);
        $pdf->Rect($barX, $barY, $total_pendiente * $scale, 10, 'F');
        $pdf->SetXY($barX + 90, $barY);
        $pdf->Cell(50,8,"Pendientes");

        $barY += 14;
        $pdf->SetFillColor(252,228,214);
        $pdf->Rect($barX, $barY, $total_justificada * $scale, 10, 'F');
        $pdf->SetXY($barX + 90, $barY);
        $pdf->Cell(50,8,"Justificadas");

        $pdf->Output();
        return;
    }
}




}
