<?php require_once __DIR__ . '/../layout/header.php'; ?>
<?php require_once __DIR__ . '/../layout/nav.php'; ?>

<div class="container mt-4">

    <h2 class="mb-4">Inconsistencias en Asistencias</h2>

    <?php if (empty($inconsistencias)): ?>
        <div class="alert alert-success">
            No se encontraron inconsistencias. Todo está en orden 👌
        </div>
    <?php else: ?>

    <div class="alert alert-warning">
        Se encontraron <?= count($inconsistencias) ?> inconsistencias.
        Revisa cada una para tomar acción.
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Empleado</th>
                    <th>Fecha</th>
                    <th>Disponibilidad</th>
                    <th>Entrada</th>
                    <th>Salida</th>
                    <th>Estado Asistencia</th>
                    <th>Motivo</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach($inconsistencias as $i): ?>

                    <?php
                        $badgeDisp = [
                            'disponible' => 'success',
                            'ausente' => 'danger',
                            'descanso' => 'secondary',
                            'especial' => 'info'
                        ][$i['disponibilidad']] ?? 'dark';
                    ?>

                    <tr>
                        <td><?= $i['nombre'] . ' ' . $i['apellidos'] ?></td>
                        <td><?= $i['fecha'] ?></td>

                        <td>
                            <span class="badge bg-<?= $badgeDisp ?>">
                                <?= $i['disponibilidad'] ?? 'Sin registro' ?>
                            </span>
                        </td>

                        <td><?= $i['hora_entrada'] ?? '—' ?></td>
                        <td><?= $i['hora_salida'] ?? '—' ?></td>

                        <td>
                            <span class="badge bg-warning text-dark">
                                <?= $i['estado'] ?>
                            </span>
                        </td>

                        <td class="text-danger fw-bold">
                            <?= $i['motivo'] ?>
                        </td>

                    </tr>
                <?php endforeach; ?>
            </tbody>

        </table>
    </div>

    <?php endif; ?>

</div>
