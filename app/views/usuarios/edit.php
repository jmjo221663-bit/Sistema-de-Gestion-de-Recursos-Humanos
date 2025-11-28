
<div class="col-md-6">
  <div class="card shadow-sm">
    <div class="card-body">
      <h5 class="card-title">Editar usuario</h5>
      <form method="post">
        <input type="hidden" name="_csrf" value="<?= htmlspecialchars($_SESSION['csrf'] ?? '') ?>">
        <div class="mb-2"><label class="form-label">Nombre</label><input class="form-control" name="name" value="<?= htmlspecialchars($user['name']) ?>" required></div>
        <div class="mb-2"><label class="form-label">Email</label><input class="form-control" type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required></div>
        <div class="mb-2"><label class="form-label">Nueva contraseña (opcional)</label><input class="form-control" type="password" name="password" minlength="8"></div>
        <div class="mb-2"><label class="form-label">Rol</label>
          <select class="form-select" name="role">
            <?php foreach (['admin','rh','empleado'] as $r): ?>
            <option value="<?= $r ?>" <?= $user['role']===$r?'selected':'' ?>><?= $r ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-check mb-3"><input class="form-check-input" type="checkbox" name="active" id="a" <?= $user['active']?'checked':'' ?>><label class="form-check-label" for="a">Activo</label></div>
        <button class="btn btn-primary">Actualizar</button>
      </form>
    </div>
  </div>
</div>
