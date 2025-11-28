<?php require_once __DIR__ . '/../layout/header.php'; ?>
<?php require_once __DIR__ . '/../layout/nav.php'; ?>

<div class="container mt-4">

    <h2 class="mb-4">Historial de Justificantes</h2>

    <?php if (empty($justificantes)): ?>
        <div class="alert alert-info">No tienes justificantes registrados.</div>
    <?php else: ?>

        <div class="table-responsive bg-white shadow-sm rounded p-3">

            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Motivo</th>
                        <th>Rango</th>
                        <th>Estado</th>
                        <th>PDF</th>
                        <th>Fecha registro</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($justificantes as $j): ?>
                        <tr>
                            <td><?= htmlspecialchars($j['motivo']) ?></td>
                            <td><?= $j['fecha_inicio'] ?> a <?= $j['fecha_fin'] ?></td>

                            <td>
                                <?php
                                    $badge = [
                                        'pendiente' => 'warning',
                                        'aprobado' => 'success',
                                        'rechazado' => 'danger'
                                    ][$j['estado']] ?? 'secondary';
                                ?>

                                <span class="badge bg-<?= $badge ?>">
                                    <?= htmlspecialchars($j['estado']) ?>
                                </span>
                            </td>

                            <td>
                                <a class="btn btn-sm btn-primary"
                                   href="/SGRH_PHP_MVC/public/?route=ver_justificante&id=<?= $j['id'] ?>">
                                   Ver PDF
                                </a>
                            </td>

                            <td><?= $j['created_at'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>

        </div>

    <?php endif; ?>

</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
