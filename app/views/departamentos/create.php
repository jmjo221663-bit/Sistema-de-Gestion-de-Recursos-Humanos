
<div class="col-md-6">
  <div class="card shadow-sm">
    <div class="card-body">
      <h5 class="card-title">Nuevo departamento</h5>
      <?php if (!empty($error)): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
      <form method="post">
        <input type="hidden" name="_csrf" value="<?= htmlspecialchars($_SESSION['csrf'] ?? '') ?>">
        <div class="mb-2"><label class="form-label">Nombre</label><input class="form-control" name="nombre" required></div>
        <div class="mb-2"><label class="form-label">Descripción</label><textarea class="form-control" name="descripcion"></textarea></div>
        <button class="btn btn-primary">Guardar</button>
      </form>
    </div>
  </div>
</div>
