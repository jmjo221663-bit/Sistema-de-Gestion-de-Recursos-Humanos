<div class="p-4 bg-white shadow-sm rounded">
  <h5 class="mb-3">Mis Justificantes</h5>

  <div class="mb-3">
    <a href="/SGRH_PHP_MVC/public/?route=justificante_crear" class="btn btn-success">
      Subir nuevo justificante
    </a>
  </div>

  <?php if (!empty($justificantes)): ?>
  <div class="table-responsive">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Motivo</th>
          <th>Fecha Inicio</th>
          <th>Fecha Fin</th>
          <th>Archivo</th>
          <th>Estado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($justificantes as $j): ?>
        <tr>
          <td><?= htmlspecialchars($j['motivo']) ?></td>
          <td><?= htmlspecialchars($j['fecha_inicio']) ?></td>
          <td><?= htmlspecialchars($j['fecha_fin']) ?></td>
          <td>
            <a href="/SGRH_PHP_MVC/public/?route=justificante_ver&id=<?= $j['id'] ?>" target="_blank" class="btn btn-outline-primary btn-sm">
              Ver PDF
            </a>
          </td>
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
            <form action="/SGRH_PHP_MVC/public/?route=justificante_eliminar" method="POST" style="display:inline;">
              <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?? '' ?>">
              <input type="hidden" name="id" value="<?= $j['id'] ?>">
              <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('¿Eliminar justificante?')">
                Eliminar
              </button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php else: ?>
    <p class="text-muted">No tienes justificantes registrados.</p>
  <?php endif; ?>
</div>
