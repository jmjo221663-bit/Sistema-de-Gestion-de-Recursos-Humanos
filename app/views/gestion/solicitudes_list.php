
<div class="d-flex justify-content-between align-items-center mb-2">
  <h5>Solicitudes (vacaciones/permisos)</h5>
  <a class="btn btn-primary" href="/SGRH_PHP_MVC/public/?route=solicitudes_create">Nueva solicitud</a>
</div>
<div class="table-responsive bg-white rounded shadow-sm">
<table class="table table-striped m-0">
  <thead><tr><th>ID</th><th>Empleado</th><th>Tipo</th><th>Motivo</th><th>Inicio</th><th>Fin</th><th>Estado</th><th></th></tr></thead>
  <tbody>
    <?php foreach($solicitudes as $s): ?>
    <tr>
      <td><?= $s['id'] ?></td>
      <td><?= htmlspecialchars(($s['nombre_empleado']??'').' '.($s['apellidos']??'')) ?></td>
      <td><?= htmlspecialchars($s['tipo']) ?></td>
      <td><?= htmlspecialchars($s['motivo']) ?></td>
      <td><?= htmlspecialchars($s['fecha_inicio']) ?></td>
      <td><?= htmlspecialchars($s['fecha_fin']) ?></td>
      <td><span class="badge bg-secondary"><?= htmlspecialchars($s['estado']) ?></span></td>
      <td class="text-end">
        <form class="d-inline" method="post" action="/SGRH_PHP_MVC/public/?route=solicitudes_estado">
          <input type="hidden" name="_csrf" value="<?= htmlspecialchars($_SESSION['csrf'] ?? '') ?>">
          <input type="hidden" name="id" value="<?= $s['id'] ?>">
          <select class="form-select form-select-sm d-inline w-auto" name="estado">
            <?php foreach(['pendiente','aprobada','rechazada'] as $st): ?>
              <option value="<?= $st ?>" <?= $s['estado']===$st?'selected':'' ?>><?= $st ?></option>
            <?php endforeach; ?>
          </select>
          <button class="btn btn-sm btn-outline-primary">Actualizar</button>
        </form>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
</div>
