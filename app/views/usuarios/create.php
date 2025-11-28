
<div class="col-md-6">
  <div class="card shadow-sm">
    <div class="card-body">
      <h5 class="card-title">Nuevo usuario</h5>
      <?php if (!empty($error)): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
      <form method="post">
        <input type="hidden" name="_csrf" value="<?= htmlspecialchars($_SESSION['csrf'] ?? '') ?>">
        <div class="mb-2"><label class="form-label">Nombre</label><input class="form-control" name="name" required></div>
        <div class="mb-2"><label class="form-label">Email</label><input class="form-control" type="email" name="email" required></div>
        <div class="mb-2"><label class="form-label">Contraseña</label><input class="form-control" type="password" name="password" minlength="8" required></div>
        <div class="mb-2"><label class="form-label">Rol</label>
          <select class="form-select" name="role">
            <option value="admin">Administrador</option>
            <option value="rh">Recursos Humanos</option>
            <option value="empleado">Empleado</option>
          </select>
        </div>
        <div class="form-check mb-3"><input class="form-check-input" type="checkbox" name="active" checked id="a"><label class="form-check-label" for="a">Activo</label></div>
        <button class="btn btn-primary">Guardar</button>
      </form>
    </div>
  </div>
</div>
