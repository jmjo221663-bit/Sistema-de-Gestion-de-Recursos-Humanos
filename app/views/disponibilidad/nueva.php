<div class="container mt-4">

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Nueva Disponibilidad</h4>
        </div>

        <div class="card-body">

            <form method="POST" action="/SGRH_PHP_MVC/public/?route=disponibilidad_crear">

                <div class="mb-3">
                    <label class="form-label">Empleado</label>
                    <select name="empleado_id" class="form-select" required>
                        <?php foreach ($empleados as $e): ?>
                            <option value="<?= $e['id'] ?>">
                                <?= $e['nombre'] . " " . $e['apellidos'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>


                <div class="mb-3">
                    <label class="form-label">Fecha</label>
                    <input type="date" name="fecha" class="form-control" required>
                </div>


                <div class="mb-3">
                    <label class="form-label">Estado</label>
                    <select name="estado" class="form-select" required>
                        <option value="disponible">Disponible</option>
                        <option value="ausente">Ausente</option>
                        <option value="descanso">Descanso</option>
                        <option value="especial">Especial</option>
                    </select>
                </div>


                <div class="mb-3">
                    <label class="form-label">Comentario (opcional)</label>
                    <textarea name="comentario" class="form-control" rows="3"></textarea>
                </div>


                <button class="btn btn-success px-4">Guardar</button>
                <a href="/SGRH_PHP_MVC/public/?route=disponibilidad" class="btn btn-secondary ms-2">Cancelar</a>

            </form>

        </div>
    </div>

</div>
