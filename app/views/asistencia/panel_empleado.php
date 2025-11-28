<?php require_once __DIR__ . '/../layout/header.php'; ?>
<?php require_once __DIR__ . '/../layout/nav.php'; ?>

<div class="container mt-4">

    <h2 class="mb-3">Mis asistencias</h2>

   <a href="?route=asistencia_entrada" class="btn btn-success mb-3">Registrar entrada</a>
<a href="?route=asistencia_salida" class="btn btn-danger mb-3">Registrar salida</a>


    <?php if (empty($asistencias)): ?>
        <div class="alert alert-info">Aún no tienes registros de asistencia.</div>
    <?php else: ?>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Fecha</th>
                    <th>Entrada</th>
                    <th>Salida</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($asistencias as $a): ?>
                    <tr>
                        <td><?= $a['fecha'] ?></td>
                        <td><?= $a['hora_entrada'] ?></td>
                        <td><?= $a['hora_salida'] ?></td>
                        <td><?= $a['estado'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

</div>
