
<div class="d-flex justify-content-between align-items-center mb-2">
  <h5>Departamentos</h5>
  <a class="btn btn-primary" href="/SGRH_PHP_MVC/public/?route=departamentos_create">Nuevo</a>
</div>
<div class="table-responsive bg-white rounded shadow-sm">
<table class="table table-striped m-0">
  <thead><tr><th>ID</th><th>Nombre</th><th>Descripción</th><th></th></tr></thead>
  <tbody>
    <?php foreach($departamentos as $d): ?>
    <tr>
      <td><?= $d['id'] ?></td>
      <td><?= htmlspecialchars($d['nombre']) ?></td>
      <td><?= htmlspecialchars($d['descripcion']) ?></td>
      <td class="text-end">
        <a class="btn btn-sm btn-outline-secondary" href="/SGRH_PHP_MVC/public/?route=departamentos_edit&id=<?= $d['id'] ?>">Editar</a>
        <form class="d-inline" method="post" action="/SGRH_PHP_MVC/public/?route=departamentos_delete" onsubmit="return confirm('¿Eliminar?')">
          <input type="hidden" name="_csrf" value="<?= htmlspecialchars($_SESSION['csrf'] ?? '') ?>">
          <input type="hidden" name="id" value="<?= $d['id'] ?>">
          <button class="btn btn-sm btn-outline-danger">Borrar</button>
        </form>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
</div>
