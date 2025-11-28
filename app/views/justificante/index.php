<div class="p-4 bg-white shadow-sm rounded">
  <h5 class="mb-3">Listado de Justificantes</h5>

  <?php if (!empty($justificantes)): ?>
  <div class="table-responsive">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Empleado</th>
          <th>Motivo</th>
          <th>Archivo</th>
          <th>Fecha Inicio</th>
          <th>Fecha Fin</th>
          <th>Estado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($justificantes as $j): ?>
        <tr>
          <td><?= htmlspecialchars($j['nombre'] . ' ' . $j['apellidos']) ?></td>
          <td><?= htmlspecialchars($j['motivo']) ?></td>
          <td>
            <a href="/SGRH_PHP_MVC/public/?route=justificante_ver&id=<?= $j['id'] ?>" target="_blank" class="btn btn-outline-primary btn-sm">
              Ver PDF
            </a>
          </td>
          <td><?= htmlspecialchars($j['fecha_inicio']) ?></td>
          <td><?= htmlspecialchars($j['fecha_fin']) ?></td>
          <td>
            <?php
              $badge = [
                'pendiente' => 'warning',
                'aprobado' => 'success',
                'rechazado' => 'danger'
              ][$j['estado']] ?? 'secondary';
            ?>
            <span class="badge bg-<?= $badge ?>"><?= htmlspecialchars($j['estado']) ?></span>
          </td>
          <td>
            <form action="/SGRH_PHP_MVC/public/?route=justificante_estado" method="POST" style="display:inline;">
              <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?? '' ?>">
              <input type="hidden" name="id" value="<?= $j['id'] ?>">
              <button name="estado" value="aprobado" class="btn btn-success btn-sm">Aprobar</button>
              <button name="estado" value="rechazado" class="btn btn-danger btn-sm">Rechazar</button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php else: ?>
    <p class="text-muted">No hay justificantes registrados.</p>
  <?php endif; ?>
</div>
