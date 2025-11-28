<div class="container mt-4">

    <div class="card shadow-sm">
        <div class="card-header bg-warning text-dark">
            <h4 class="mb-0">Editar Disponibilidad</h4>
        </div>

        <div class="card-body">

            <form method="POST" action="/SGRH_PHP_MVC/public/?route=disponibilidad_editar&id=<?= $disponibilidad['id'] ?>">

                <div class="mb-3">
                    <label class="form-label">Empleado</label>
                    <select name="empleado_id" class="form-select" required>

                        <?php foreach ($empleados as $e): ?>
                            <option 
                                value="<?= $e['id'] ?>"
                                <?= $e['id'] == $disponibilidad['empleado_id'] ? 'selected' : '' ?>>
                                <?= $e['nombre'] . " " . $e['apellidos'] ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                </div>


                <div class="mb-3">
                    <label class="form-label">Fecha</label>
                    <input type="date" name="fecha" value="<?= $disponibilidad['fecha'] ?>" class="form-control" required>
                </div>


                <div class="mb-3">
                    <label class="form-label">Estado</label>
                    <select name="estado" class="form-select" required>
                        <option value="disponible" <?= $disponibilidad['estado'] == 'disponible' ? 'selected' : '' ?>>Disponible</option>
                        <option value="ausente" <?= $disponibilidad['estado'] == 'ausente' ? 'selected' : '' ?>>Ausente</option>
                        <option value="descanso" <?= $disponibilidad['estado'] == 'descanso' ? 'selected' : '' ?>>Descanso</option>
                        <option value="especial" <?= $disponibilidad['estado'] == 'especial' ? 'selected' : '' ?>>Especial</option>
                    </select>
                </div>


                <div class="mb-3">
                    <label class="form-label">Comentario (opcional)</label>
                    <textarea name="comentario" class="form-control" rows="3"><?= $disponibilidad['comentario'] ?></textarea>
                </div>


                <button class="btn btn-primary px-4">Actualizar</button>
                <a href="/SGRH_PHP_MVC/public/?route=disponibilidad" class="btn btn-secondary ms-2">Cancelar</a>

            </form>

        </div>
    </div>

</div>
