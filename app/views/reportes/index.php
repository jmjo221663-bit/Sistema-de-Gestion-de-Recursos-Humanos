<?php require_once __DIR__ . '/../layout/header.php'; ?>
<?php require_once __DIR__ . '/../layout/nav.php'; ?>

<div class="container mt-4">

    <h2 class="mb-4"><i class="fa-solid fa-chart-line"></i> Reportes del Sistema</h2>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- ==========================================
         FORMULARIO DE FILTRO
    =========================================== -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white">
            <strong>Filtrar Información</strong>
        </div>
        <div class="card-body">
            <form method="POST">

                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label">Empleado:</label>
                        <select class="form-select" name="empleado_id">
                            <option value="">Todos</option>
                            <?php foreach ($empleados as $e): ?>
                                <option value="<?= $e['id'] ?>"
                                    <?= isset($_POST['empleado_id']) && $_POST['empleado_id'] == $e['id'] ? 'selected' : '' ?>>
                                    <?= $e['nombre'] . ' ' . $e['apellidos'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Departamento:</label>
                        <select class="form-select" name="departamento_id">
                            <option value="">Todos</option>
                            <?php foreach ($departamentos as $d): ?>
                                <option value="<?= $d['id'] ?>"
                                    <?= isset($_POST['departamento_id']) && $_POST['departamento_id'] == $d['id'] ? 'selected' : '' ?>>
                                    <?= $d['nombre'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Fecha Inicio:</label>
                        <input type="date" class="form-control" name="fecha_inicio" required
                               value="<?= $fecha_inicio ?? '' ?>">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Fecha Fin:</label>
                        <input type="date" class="form-control" name="fecha_fin" required
                               value="<?= $fecha_fin ?? '' ?>">
                    </div>
                </div>

                <button class="btn btn-primary mt-3">
                    <i class="fa-solid fa-magnifying-glass"></i> Generar Reporte
                </button>

            </form>
        </div>
    </div>

    <?php if (isset($asistencias)): ?>

        <!-- ============================================================
             2 BOTONES NUEVOS (TABLAS / GRÁFICAS)
        ============================================================ -->
        <div class="d-flex gap-2 mb-3">

            <!-- PDF TABLAS -->
            <a class="btn btn-danger"
               target="_blank"
               href="/SGRH_PHP_MVC/public/?route=reportes/pdf_tablas&empleado_id=<?= $_POST['empleado_id'] ?? '' ?>&inicio=<?= $fecha_inicio ?>&fin=<?= $fecha_fin ?>">
                <i class="fa-solid fa-table"></i> Descargar Tablas
            </a>

            <!-- PDF GRÁFICAS -->
            <a class="btn btn-warning text-dark"
               target="_blank"
               href="/SGRH_PHP_MVC/public/?route=reportes/pdf_graficas&empleado_id=<?= $_POST['empleado_id'] ?? '' ?>&inicio=<?= $fecha_inicio ?>&fin=<?= $fecha_fin ?>">
                <i class="fa-solid fa-chart-pie"></i> Descargar Gráficas
            </a>

        </div>

        <!-- ==========================================
             TABLA DE ASISTENCIAS
        =========================================== -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-dark text-white">
                <strong>Asistencias Encontradas</strong>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Empleado</th>
                            <th>Departamento</th>
                            <th>Fecha</th>
                            <th>Entrada</th>
                            <th>Salida</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($asistencias as $a): ?>
                        <tr>
                            <td><?= $a['nombre'] . ' ' . $a['apellidos'] ?></td>
                            <td><?= $a['departamento'] ?></td>
                            <td><?= $a['fecha'] ?></td>
                            <td><?= $a['hora_entrada'] ?></td>
                            <td><?= $a['hora_salida'] ?></td>
                            <td><?= ucfirst($a['estado']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ==========================================
             TABLA DE DISPONIBILIDAD
        =========================================== -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-secondary text-white">
                <strong>Disponibilidades</strong>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Empleado</th>
                            <th>Departamento</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($disponibilidades as $d): ?>
                        <tr>
                            <td><?= $d['nombre'] . ' ' . $d['apellidos'] ?></td>
                            <td><?= $d['departamento'] ?></td>
                            <td><?= $d['fecha'] ?></td>
                            <td><?= ucfirst($d['estado']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ==========================================
             GRÁFICAS LADO A LADO
        =========================================== -->
        <div class="row mb-4">

            <!-- GRAFICA IZQUIERDA -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-info text-white">
                        <strong>Asistencias Completas por Fecha</strong>
                    </div>
                    <div class="card-body">
                        <canvas id="graficaAsistencias"></canvas>
                    </div>
                </div>
            </div>

            <!-- GRAFICA DERECHA -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <strong>Comparativa por Empleado</strong>
                    </div>
                    <div class="card-body">
                        <canvas id="graficaComparativa"></canvas>
                    </div>
                </div>
            </div>

        </div>

    <?php endif; ?>

</div>

<!-- ================== SCRIPTS DE GRÁFICAS ================== -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- GRAFICA IZQUIERDA (LINEA) -->
<?php if (isset($grafica_labels)): ?>
<script>
const ctx1 = document.getElementById('graficaAsistencias');
new Chart(ctx1, {
    type: 'line',
    data: {
        labels: <?= $grafica_labels ?>,
        datasets: [{
            label: 'Asistencias completas',
            data: <?= $grafica_data ?>,
            borderWidth: 2,
            borderColor: 'rgba(54, 162, 235, 1)',
            backgroundColor: 'rgba(54, 162, 235, 0.4)'
        }]
    },
    options: { scales: { y: { beginAtZero: true }}}
});
</script>
<?php endif; ?>

<!-- GRAFICA DERECHA (BARRAS) -->
<?php if (isset($graf_empleados)): ?>
<script>
const ctx2 = document.getElementById('graficaComparativa');
new Chart(ctx2, {
    type: 'bar',
    data: {
        labels: <?= $graf_empleados ?>,
        datasets: [
            {
                label: 'Completas',
                data: <?= $graf_completas ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            },
            {
                label: 'Pendientes',
                data: <?= $graf_pendientes ?>,
                backgroundColor: 'rgba(255, 99, 132, 0.7)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            },
            {
                label: 'Justificadas',
                data: <?= $graf_justificadas ?>,
                backgroundColor: 'rgba(255, 206, 86, 0.7)',
                borderColor: 'rgba(255, 206, 86, 1)',
                borderWidth: 1
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'top' },
            title: { display: true, text: 'Comparativa de Estados por Empleado' }
        },
        scales: { y: { beginAtZero: true }}
    }
});
</script>
<?php endif; ?>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
