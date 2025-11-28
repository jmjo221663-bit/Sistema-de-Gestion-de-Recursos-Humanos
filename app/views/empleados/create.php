<div class="col-md-8">
  <div class="card shadow-sm">
    <div class="card-body">
      <h5 class="card-title">Registrar empleado</h5>

      <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form method="post">
        <input type="hidden" name="_csrf" value="<?= htmlspecialchars($_SESSION['csrf'] ?? '') ?>">

        <div class="row g-2">

          <div class="col-md-6">
            <label class="form-label">Nombre</label>
            <input class="form-control" name="nombre" required>
          </div>

          <div class="col-md-6">
            <label class="form-label">Apellidos</label>
            <input class="form-control" name="apellidos" required>
          </div>

          <!-- 🔥 CAMPO GÉNERO -->
          <div class="col-md-6">
            <label class="form-label">Género</label>
            <select class="form-select" name="genero" required>
              <option value="">Selecciona una opción</option>
              <option value="masculino">Masculino</option>
              <option value="femenino">Femenino</option>
              <option value="otro">Otro</option>
            </select>
          </div>
          <!-- 🔥 FIN CAMPO GÉNERO -->

          <div class="col-md-6">
            <label class="form-label">Correo</label>
            <input class="form-control" type="email" name="correo" required>
          </div>

          <div class="col-md-6">
            <label class="form-label">CURP</label>
            <input class="form-control" name="curp" minlength="18" maxlength="18" required>
          </div>

          <div class="col-md-6">
            <label class="form-label">Puesto</label>
            <input class="form-control" name="puesto">
          </div>

          <div class="col-md-6">
            <label class="form-label">Departamento</label>
            <select class="form-select" name="departamento_id" required>
              <?php foreach($departamentos as $d): ?>
                <option value="<?= $d['id'] ?>">
                  <?= htmlspecialchars($d['nombre']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="col-md-4">
            <label class="form-label">Estado</label>
            <select class="form-select" name="estado">
              <option value="activo">activo</option>
              <option value="baja">baja</option>
            </select>
          </div>

        </div>

        <button class="btn btn-primary mt-3">Guardar</button>

      </form>
    </div>
  </div>
</div>
