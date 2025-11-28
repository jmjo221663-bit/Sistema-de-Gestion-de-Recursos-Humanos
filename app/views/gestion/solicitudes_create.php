
<div class="col-md-8">
  <div class="card shadow-sm">
    <div class="card-body">
      <h5 class="card-title">Nueva solicitud</h5>
      <?php if (!empty($error)): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
      <form method="post">
        <input type="hidden" name="_csrf" value="<?= htmlspecialchars($_SESSION['csrf'] ?? '') ?>">
        <div class="row g-2">
          <div class="col-md-6"><label class="form-label">Empleado</label>
            <select class="form-select" name="empleado_id" required>
              <option value="">-- Selecciona --</option>
              <?php foreach($empleados as $e): ?>
                <option value="<?= $e['id'] ?>"><?= htmlspecialchars($e['nombre'].' '.$e['apellidos']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">Tipo</label>
            <select class="form-select" name="tipo">
              <option value="vacaciones">vacaciones</option>
              <option value="permiso">permiso</option>
            </select>
          </div>
          <div class="col-12"><label class="form-label">Motivo</label><textarea class="form-control" name="motivo"></textarea></div>
          <div class="col-md-6"><label class="form-label">Fecha inicio</label><input class="form-control" type="date" name="fecha_inicio" required></div>
          <div class="col-md-6"><label class="form-label">Fecha fin</label><input class="form-control" type="date" name="fecha_fin" required></div>
        </div>
        <button class="btn btn-primary mt-3">Guardar</button>
      </form>
    </div>
  </div>
</div>
