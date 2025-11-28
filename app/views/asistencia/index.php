<?php require_once __DIR__ . '/../layout/header.php'; ?>
<?php require_once __DIR__ . '/../layout/nav.php'; ?>

<div class="container mt-4">

   

    <h2 class="mb-4">Registro General de Asistencias</h2>

<a href="/SGRH_PHP_MVC/public/?route=asistencias_pendientes" 
   class="btn btn-warning mb-3">
   Ver asistencias pendientes
</a>


    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($_GET['msg']) ?>
        </div>
    <?php endif; ?>

    <?php if (empty($asistencias)): ?>
        <div class="alert alert-info">No hay registros de asistencia.</div>
    <?php else: ?>

        <div class="table-responsive bg-white rounded shadow-sm">
            <table class="table table-striped m-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Empleado</th>
                        <th>Fecha</th>
                        <th>Hora Entrada</th>
                        <th>Hora Salida</th>
                        <th>Estado</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($asistencias as $a): ?>
                        <tr>
                            <td><?= $a['id'] ?></td>

                            <td><?= htmlspecialchars($a['nombre'] . ' ' . $a['apellidos']) ?></td>

                            <td><?= htmlspecialchars($a['fecha']) ?></td>

                            <td><?= htmlspecialchars($a['hora_entrada'] ?? '-') ?></td>

                            <td><?= htmlspecialchars($a['hora_salida'] ?? '-') ?></td>

                            <td>
                              <?php
                                        $colores = [
                                            'pendiente'     => 'warning',   // amarillo
                                            'completa'      => 'success',   // verde
                                            'justificada'   => 'primary'    // azul
                                        ];

                                        $badge = $colores[$a['estado']] ?? 'secondary';
                                        ?>


                                <span class="badge bg-<?= $badge ?>">
                                    <?= htmlspecialchars($a['estado']) ?>
                                </span>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>
        </div>

    <?php endif; ?>

</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
