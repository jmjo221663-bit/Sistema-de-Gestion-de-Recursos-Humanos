<div class="col-md-6 mx-auto">
  <div class="card shadow-sm mt-4">
    <div class="card-body">
      <h5 class="card-title">Cambiar contraseña</h5>

      <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form method="post">
        <input type="hidden" name="_csrf" value="<?= htmlspecialchars($_SESSION['csrf']) ?>">

        <label>Nueva contraseña</label>
        <input type="password" class="form-control" name="password" required>

        <label class="mt-2">Confirmar contraseña</label>
        <input type="password" class="form-control" name="password_confirm" required>

        <button class="btn btn-primary mt-3">Actualizar</button>
      </form>
    </div>
  </div>
</div>
