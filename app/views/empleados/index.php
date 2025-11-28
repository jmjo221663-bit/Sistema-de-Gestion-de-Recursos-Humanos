<div class="d-flex justify-content-between align-items-center mb-2">
  <h5>Empleados</h5>
  <a class="btn btn-primary" href="/SGRH_PHP_MVC/public/?route=empleados_create">Nuevo</a>
</div>

<!-- 🔥 ALERTAS DE ÉXITO Y ERROR -->
<?php if (!empty($_SESSION['success'])): ?>
  <div class="alert alert-success">
    <?= $_SESSION['success']; ?>
  </div>
  <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['error'])): ?>
  <div class="alert alert-danger">
    <?= $_SESSION['error']; ?>
  </div>
  <?php unset($_SESSION['error']); ?>
<?php endif; ?>
<!-- 🔥 FIN ALERTAS -->

<div class="table-responsive bg-white rounded shadow-sm">
<table class="table table-striped m-0">
  <thead>
    <tr>
      <th>ID</th>
      <th>Nombre</th>
      <th>Correo</th>
      <th>CURP</th>
      <th>Puesto</th>
      <th>Departamento</th>
      <th>Estado</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($empleados as $e): ?>
    <tr>
      <td><?= $e['id'] ?></td>
      <td><?= htmlspecialchars($e['nombre'] . ' ' . $e['apellidos']) ?></td>
      <td><?= htmlspecialchars($e['correo']) ?></td>
      <td><?= htmlspecialchars($e['curp']) ?></td>
      <td><?= htmlspecialchars($e['puesto']) ?></td>
      <td><?= htmlspecialchars($e['departamento']) ?></td>
      <td><?= htmlspecialchars($e['estado']) ?></td>
      <td class="text-end">
        <a class="btn btn-sm btn-outline-secondary" href="/SGRH_PHP_MVC/public/?route=empleados_edit&id=<?= $e['id'] ?>">Editar</a>
        <form class="d-inline" method="post" action="/SGRH_PHP_MVC/public/?route=empleados_delete" onsubmit="return confirm('¿Eliminar?')">
          <input type="hidden" name="_csrf" value="<?= htmlspecialchars($_SESSION['csrf'] ?? '') ?>">
          <input type="hidden" name="id" value="<?= $e['id'] ?>">
          <button class="btn btn-sm btn-outline-danger">Borrar</button>
        </form>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
</div>
