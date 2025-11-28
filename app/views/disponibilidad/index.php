<div class="d-flex justify-content-between align-items-center mb-2">
  <h5>Gestión de Disponibilidad</h5>
  <a class="btn btn-primary" href="/SGRH_PHP_MVC/public/?route=disponibilidad_crear">Nueva disponibilidad</a>
</div>

<div class="table-responsive bg-white rounded shadow-sm">
<table class="table table-striped m-0">
  <thead>
    <tr>
      <th>ID</th>
      <th>Empleado</th>
      <th>Fecha</th>
      <th>Estado</th>
      <th>Comentario</th>
      <th class="text-end">Acciones</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($disponibilidades as $d): ?>
      <tr>
        <td><?= $d['id'] ?></td>
        <td><?= htmlspecialchars($d['nombre'] . ' ' . $d['apellidos']) ?></td>
        <td><?= htmlspecialchars($d['fecha']) ?></td>

        <td>
          <?php
            $badge = [
              'disponible' => 'success',
              'ausente' => 'danger',
              'descanso' => 'secondary',
              'especial' => 'info'
            ][$d['estado']] ?? 'light';
          ?>
          <span class="badge bg-<?= $badge ?>"><?= htmlspecialchars($d['estado']) ?></span>
        </td>

        <td><?= htmlspecialchars($d['comentario']) ?></td>
<td class="text-end">

  <a 
    href="/SGRH_PHP_MVC/public/?route=disponibilidad_editar&id=<?= $d['id'] ?>" 
    class="btn btn-sm btn-outline-secondary">
    Editar
  </a>

  <a 
    href="/SGRH_PHP_MVC/public/?route=disponibilidad_eliminar&id=<?= $d['id'] ?>" 
    class="btn btn-sm btn-outline-danger"
    onclick="return confirm('¿Seguro que quieres eliminar este registro?');">
    Eliminar
  </a>

</td>

      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
</div>
