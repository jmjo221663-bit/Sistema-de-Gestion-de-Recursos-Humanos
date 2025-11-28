
<div class="d-flex justify-content-between align-items-center mb-2">
  <h5>Usuarios</h5>
  <a class="btn btn-primary" href="/SGRH_PHP_MVC/public/?route=users_create">Nuevo</a>
</div>
<div class="table-responsive bg-white rounded shadow-sm">
<table class="table table-striped m-0">
  <thead><tr><th>ID</th><th>Nombre</th><th>Email</th><th>Rol</th><th>Activo</th><th></th></tr></thead>
  <tbody>
    <?php foreach($users as $u): ?>
    <tr>
      <td><?= $u['id'] ?></td>
      <td><?= htmlspecialchars($u['name']) ?></td>
      <td><?= htmlspecialchars($u['email']) ?></td>
      <td><?= htmlspecialchars($u['role']) ?></td>
      <td><?= $u['active'] ? 'Sí' : 'No' ?></td>
      <td class="text-end">
        <a class="btn btn-sm btn-outline-secondary" href="/SGRH_PHP_MVC/public/?route=users_edit&id=<?= $u['id'] ?>">Editar</a>
        <form class="d-inline" method="post" action="/SGRH_PHP_MVC/public/?route=users_delete" onsubmit="return confirm('¿Eliminar?')">
          <input type="hidden" name="_csrf" value="<?= htmlspecialchars($_SESSION['csrf'] ?? '') ?>">
          <input type="hidden" name="id" value="<?= $u['id'] ?>">
          <button class="btn btn-sm btn-outline-danger">Borrar</button>
        </form>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
</div>
