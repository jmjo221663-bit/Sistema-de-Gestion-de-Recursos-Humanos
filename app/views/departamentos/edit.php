
<div class="col-md-6">
  <div class="card shadow-sm">
    <div class="card-body">
      <h5 class="card-title">Editar departamento</h5>
      <form method="post">
        <input type="hidden" name="_csrf" value="<?= htmlspecialchars($_SESSION['csrf'] ?? '') ?>">
        <div class="mb-2"><label class="form-label">Nombre</label><input class="form-control" name="nombre" value="<?= htmlspecialchars($departamento['nombre']) ?>" required></div>
        <div class="mb-2"><label class="form-label">Descripción</label><textarea class="form-control" name="descripcion"><?= htmlspecialchars($departamento['descripcion']) ?></textarea></div>
        <button class="btn btn-primary">Actualizar</button>
      </form>
    </div>
  </div>
</div>
