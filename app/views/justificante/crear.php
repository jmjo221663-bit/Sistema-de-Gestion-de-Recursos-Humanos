<div class="p-4 bg-white shadow-sm rounded">
  <h5 class="mb-3">Subir Justificante</h5>

  <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form action="" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="_csrf" value="<?= $_SESSION['csrf'] ?? '' ?>">

    <div class="mb-3">
      <label class="form-label">Motivo</label>
      <input type="text" name="motivo" class="form-control" required>
    </div>

    <div class="row mb-3">
      <div class="col">
        <label class="form-label">Fecha inicio</label>
        <input type="date" name="fecha_inicio" class="form-control" required>
      </div>
      <div class="col">
        <label class="form-label">Fecha fin</label>
        <input type="date" name="fecha_fin" class="form-control" required>
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label">Archivo (PDF máx. 5MB)</label>
      <input type="file" name="archivo" class="form-control" accept=".pdf" required>
    </div>

    <div class="d-flex gap-3">
      <button type="submit" class="btn btn-primary">Guardar</button>
      <a href="/SGRH_PHP_MVC/public/?route=panel_justificante" class="btn btn-secondary">Cancelar</a>
    </div>
  </form>
</div>
