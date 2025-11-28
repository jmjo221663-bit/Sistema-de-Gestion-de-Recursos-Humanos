<div class="col-md-8">
  <div class="card shadow-sm">
    <div class="card-body">
      <h5 class="card-title">Registrar Disponibilidad</h5>
      <?php if (!empty($error)): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
      <form method="post">
        <input type="hidden" name="_csrf" value="<?= htmlspecialchars($_SESSION['csrf'] ?? '') ?>">
        <div class="row g-2">
          <div class="col-md-6">
            <label class="form-label">Empleado</label>
            <select class="form-select" name="empleado_id" required>
              <option value="">-- Selecciona --</option>
              <?php foreach($empleados as $e): ?>
                <option value="<?= $e['id'] ?>"><?= htmlspecialchars($e['nombre'].' '.$e['apellidos']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">Fecha</label>
            <input type="date" name="fecha" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Estado</label>
            <select class="form-select" name="estado">
              <option value="disponible">Disponible</option>
              <option value="ausente">Ausente</option>
              <option value="descanso">Descanso</option>
              <option value="especial">Especial</option>
            </select>
          </div>
          <div class="col-md-12">
            <label class="form-label">Comentario</label>
            <textarea name="comentario" class="form-control" rows="2"></textarea>
          </div>
        </div>
        <button class="btn btn-primary mt-3">Guardar</button>
      </form>
    </div>
  </div>
</div>
