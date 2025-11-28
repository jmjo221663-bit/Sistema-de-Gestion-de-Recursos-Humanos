<?php require_once __DIR__ . '/../layout/header.php'; ?>
<?php require_once __DIR__ . '/../layout/nav.php'; ?>

<div class="container mt-4">

    <h2 class="mb-4">Asistencias Pendientes</h2>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success">
            Estado actualizado correctamente: <?= htmlspecialchars($_GET['msg']) ?>
        </div>
    <?php endif; ?>

    <?php if (empty($pendientes)): ?>
        <div class="alert alert-info">No hay asistencias pendientes.</div>
    <?php else: ?>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Empleado</th>
                    <th>Disponibilidad del día</th>
                    <th>Fecha</th>
                    <th>Hora Entrada</th>
                    <th>Hora Salida</th>
                    <th>Estado Actual</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($pendientes as $a): ?>

                    <?php 
                        // Obtener el estado de disponibilidad del día
                        $estadoDisp = Disponibilidad::obtenerEstado($a['empleado_id'], $a['fecha']);

                        // Definir badge visual
                        $badges = [
                            'disponible' => 'success',
                            'ausente'    => 'danger',
                            'descanso'   => 'secondary',
                            'especial'   => 'info'
                        ];

                        $badgeColor = $badges[$estadoDisp] ?? 'dark';
                    ?>

                    <tr>
                        <td><?= $a['nombre'] . ' ' . $a['apellidos'] ?></td>

                        <!-- DISPONIBILIDAD -->
                        <td>
                            <?php if ($estadoDisp): ?>
                                <span class="badge bg-<?= $badgeColor ?>">
                                    <?= htmlspecialchars($estadoDisp) ?>
                                </span>
                            <?php else: ?>
                                <span class="badge bg-dark">Sin registro</span>
                            <?php endif; ?>
                        </td>

                        <td><?= $a['fecha'] ?></td>
                        <td><?= $a['hora_entrada'] ?></td>
                        <td><?= $a['hora_salida'] ?? '—' ?></td>

                        <td>
                            <span class="badge bg-warning text-dark">
                                <?= $a['estado'] ?>
                            </span>
                        </td>

                        <td>

                            <a href="/SGRH_PHP_MVC/public/?route=justificar_asistencia&id=<?= $a['id'] ?>" 
                               class="btn btn-sm btn-success"
                               onclick="return confirm('¿Seguro que deseas justificar esta asistencia?');">
                                Justificar
                            </a>

                            <a href="/SGRH_PHP_MVC/public/?route=completar_asistencia&id=<?= $a['id'] ?>" 
                               class="btn btn-sm btn-primary"
                               onclick="return confirm('¿Marcar como completa manualmente?');">
                                Completar
                            </a>

                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>

        </table>
    </div>

    <?php endif; ?>

</div>
