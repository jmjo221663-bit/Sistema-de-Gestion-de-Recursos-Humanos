<div class="col-md-8">
  <div class="card shadow-sm">
    <div class="card-body">
      <h5 class="card-title">Editar empleado</h5>
      <form method="post">
        <input type="hidden" name="_csrf" value="<?= htmlspecialchars($_SESSION['csrf'] ?? '') ?>">

        <div class="row g-2">

          <div class="col-md-6">
            <label class="form-label">Nombre</label>
            <input class="form-control" name="nombre" value="<?= htmlspecialchars($empleado['nombre']) ?>" required>
          </div>

          <div class="col-md-6">
            <label class="form-label">Apellidos</label>
            <input class="form-control" name="apellidos" value="<?= htmlspecialchars($empleado['apellidos']) ?>" required>
          </div>

          <!-- 🔥 AQUÍ SE AGREGA EL CAMPO GÉNERO -->
          <div class="col-md-6">
            <label class="form-label">Género</label>
            <select class="form-select" name="genero" required>
              <option value="masculino" <?= $empleado['genero']=='masculino'?'selected':'' ?>>Masculino</option>
              <option value="femenino" <?= $empleado['genero']=='femenino'?'selected':'' ?>>Femenino</option>
              <option value="otro" <?= $empleado['genero']=='otro'?'selected':'' ?>>Otro</option>
            </select>
          </div>
          <!-- 🔥 FIN DEL CAMPO GÉNERO -->

          <div class="col-md-6">
            <label class="form-label">Correo</label>
            <input class="form-control" type="email" name="correo" value="<?= htmlspecialchars($empleado['correo']) ?>" required>
          </div>

          <div class="col-md-6">
            <label class="form-label">CURP</label>
            <input class="form-control" name="curp" minlength="18" maxlength="18" value="<?= htmlspecialchars($empleado['curp']) ?>" required>
          </div>

          <div class="col-md-6">
            <label class="form-label">Puesto</label>
            <input class="form-control" name="puesto" value="<?= htmlspecialchars($empleado['puesto']) ?>">
          </div>

          <div class="col-md-6">
            <label class="form-label">Departamento</label>
            <select class="form-select" name="departamento_id" required>
              <?php foreach($departamentos as $d): ?>
                <option value="<?= $d['id'] ?>" <?= $empleado['departamento_id']==$d['id']?'selected':'' ?>>
                  <?= htmlspecialchars($d['nombre']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="col-md-4">
            <label class="form-label">Estado</label>
            <select class="form-select" name="estado">
              <?php foreach(['activo','baja'] as $st): ?>
                <option value="<?= $st ?>" <?= $empleado['estado']===$st?'selected':'' ?>><?= $st ?></option>
              <?php endforeach; ?>
            </select>
          </div>

        </div>

        <button class="btn btn-primary mt-3">Actualizar</button>
      </form>
    </div>
  </div>
</div>
