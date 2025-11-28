<?php $u = Auth::user(); ?>

<div class="p-4 bg-white shadow-sm rounded">
  <h5 class="mb-3">Panel de Asistencia</h5>

  <?php if ($u['role'] === 'admin' || $u['role'] === 'rh'): ?>
    <!-- Solo muestra el botón para ir al historial general -->
    <div class="mb-3">
      <a href="/SGRH_PHP_MVC/public/?route=asistencia" class="btn btn-outline-primary btn-sm">
        Ver historial general
      </a>
    </div>
    <p>Actualmente estás logueado como <strong><?= htmlspecialchars($u['role']) ?></strong>. 
       Solo los empleados pueden registrar entrada o salida.</p>

  <?php else: ?>
    <!-- Panel de registro solo visible para empleados -->
    <p>Empleado: <strong><?= htmlspecialchars($u['name']) ?></strong></p>

    <div class="d-flex gap-3 mb-4">
      <a href="/SGRH_PHP_MVC/public/?route=asistencia_entrada" class="btn btn-success">Registrar Entrada</a>
      <a href="/SGRH_PHP_MVC/public/?route=asistencia_salida" class="btn btn-danger">Registrar Salida</a>
    </div>

    <h6>Historial de Asistencias</h6>
    <div class="table-responsive mt-3">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Fecha</th>
            <th>Hora Entrada</th>
            <th>Hora Salida</th>
            <th>Estado</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($asistencias as $a): ?>
            <tr>
              <td><?= htmlspecialchars($a['fecha']) ?></td>
              <td><?= htmlspecialchars($a['hora_entrada'] ?? '-') ?></td>
              <td><?= htmlspecialchars($a['hora_salida'] ?? '-') ?></td>
              <td>
                <?php
                  $badge = [
                    'pendiente' => 'warning',
                    'completa' => 'success',
                    'justificada' => 'info'
                  ][$a['estado']] ?? 'secondary';
                ?>
                <span class="badge bg-<?= $badge ?>"><?= htmlspecialchars($a['estado']) ?></span>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>
