<?php
// Variables disponibles: $asistencias, $disponibilidades, $filtros (fecha_inicio, fecha_fin)
function e($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Reporte de Asistencias y Disponibilidad</title>
<style>
  body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color: #222; }
  h1,h2,h3 { margin: 0 0 8px; }
  .muted { color: #666; font-size: 11px; }
  .section { margin-top: 18px; }
  table { width: 100%; border-collapse: collapse; }
  th, td { border: 1px solid #ccc; padding: 6px; }
  th { background: #f2f2f2; text-align: left; }
</style>
</head>
<body>
  <h2>Reporte de Asistencias y Disponibilidad</h2>
  <p class="muted">Rango: <?= e($filtros['fecha_inicio']) ?> a <?= e($filtros['fecha_fin']) ?> · Generado: <?= date('Y-m-d H:i') ?></p>

  <div class="section">
    <h3>Asistencias</h3>
    <?php if (!empty($asistencias)): ?>
    <table>
      <thead>
        <tr>
          <th>Empleado</th>
          <th>Departamento</th>
          <th>Fecha</th>
          <th>Entrada</th>
          <th>Salida</th>
          <th>Estado</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($asistencias as $a): ?>
        <tr>
          <td><?= e($a['nombre'].' '.$a['apellidos']) ?></td>
          <td><?= e($a['departamento']) ?></td>
          <td><?= e($a['fecha']) ?></td>
          <td><?= e($a['hora_entrada'] ?? '-') ?></td>
          <td><?= e($a['hora_salida'] ?? '-') ?></td>
          <td><?= e($a['estado']) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php else: ?>
      <p class="muted">Sin registros de asistencia en el rango.</p>
    <?php endif; ?>
  </div>

  <div class="section">
    <h3>Disponibilidades</h3>
    <?php if (!empty($disponibilidades)): ?>
    <table>
      <thead>
        <tr>
          <th>Empleado</th>
          <th>Departamento</th>
          <th>Fecha</th>
          <th>Estado</th>
          <th>Comentario</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($disponibilidades as $d): ?>
        <tr>
          <td><?= e($d['nombre'].' '.$d['apellidos']) ?></td>
          <td><?= e($d['departamento']) ?></td>
          <td><?= e($d['fecha']) ?></td>
          <td><?= e($d['estado']) ?></td>
          <td><?= e($d['comentario'] ?? '-') ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php else: ?>
      <p class="muted">Sin registros de disponibilidad en el rango.</p>
    <?php endif; ?>
  </div>
</body>
</html>
