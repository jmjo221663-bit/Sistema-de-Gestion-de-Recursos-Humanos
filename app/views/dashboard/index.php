<?php if ($user['role'] === 'admin' || $user['role'] === 'rh'): ?>



        
    <h2 class="mb-4">Panel de Control</h2>

    <!-- ========================== -->
    <!-- TARJETAS DE JUSTIFICANTES -->
    <!-- ========================== -->
    <h4 class="mb-3">Justificantes</h4>

    <div class="row g-3 mb-4">

        <!-- Pendientes -->
        <div class="col-md-4">
            <div class="card text-white bg-warning shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Pendientes</h5>
                    <h2 class="fw-bold"><?= $justPend ?></h2>
                </div>
            </div>
        </div>

        <!-- Aprobados -->
        <div class="col-md-4">
            <div class="card text-white bg-success shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Aprobados</h5>
                    <h2 class="fw-bold"><?= $justApr ?></h2>
                </div>
            </div>
        </div>

        <!-- Rechazados -->
        <div class="col-md-4">
            <div class="card text-white bg-danger shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Rechazados</h5>
                    <h2 class="fw-bold"><?= $justRec ?></h2>
                </div>
            </div>
        </div>

    </div>
    

    <!-- ====================== -->
    <!-- TARJETAS DE ASISTENCIAS -->
    <!-- ====================== -->
    <h4 class="mb-3">Asistencias</h4>

    <div class="row g-3">

        <!-- Pendientes -->
        <div class="col-md-3">
            <div class="card text-white bg-danger shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Pendientes</h5>
                    <h2 class="fw-bold"><?= $pendientes ?></h2>
                </div>
            </div>
        </div>

        <!-- Completas -->
        <div class="col-md-3">
            <div class="card text-white bg-success shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Completas</h5>
                    <h2 class="fw-bold"><?= $completas ?></h2>
                </div>
            </div>
        </div>

        <!-- Justificadas -->
        <div class="col-md-3">
            <div class="card text-white bg-info shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Justificadas</h5>
                    <h2 class="fw-bold"><?= $justificadas ?></h2>
                </div>
            </div>
        </div>

        <!-- Empleados activos -->
        <div class="col-md-3">
            <div class="card bg-secondary text-white shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Empleados Activos</h5>
                    <h2 class="fw-bold"><?= $empleadosActivos ?></h2>
                </div>
            </div>
        </div>

    </div>

    <hr class="my-4">

    <div class="p-4 bg-white shadow-sm rounded">
        <h4 class="mb-3"><i class="fa-regular fa-cloud"></i> Panel Administrativo</h4>
        <p>Bienvenido al Sistema de Gestión de Recursos Humanos. Usa el menú para navegar.</p>
    </div>

<?php else: ?>

    <!-- PANEL DE EMPLEADO -->
    <div class="p-4 bg-white shadow-sm rounded">
        <script src="https://kit.fontawesome.com/dabf5dbb8f.js" crossorigin="anonymous"></script>
        <h4 class="mb-3"><i class="fa-regular fa-cloud"></i> Panel</h4>
        <p>Bienvenido al Sistema de Gestión de Recursos Humanos. Usa el menú para navegar.</p>
    </div>

<?php endif; ?>
