<?php 
$u = Auth::user(); 

$contadorPendientes = 0;
if ($u && ($u['role'] === 'admin' || $u['role'] === 'rh')) {
    $contadorPendientes = Asistencia::contarPendientes();
}

$pendJust = Justificante::pendientesCount();
?>
<nav class="navbar navbar-expand-lg bg-white shadow-sm rounded mb-3 px-3 py-2">
  
  <?php if (!isset($_GET['route']) || $_GET['route'] !== 'login'): ?>
      <a class="navbar-brand fw-bold text-primary" href="/SGRH_PHP_MVC/public/?route=dashboard">
          <i class="fa-solid fa-briefcase"></i> SGRH
      </a>
  <?php endif; ?>

  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
      <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="mainNav">

    <ul class="navbar-nav me-auto mb-2 mb-lg-0">

      <?php if ($u): ?>

        <?php if ($u['role'] === 'empleado'): ?>

          <li class="nav-item"><a class="nav-link" href="?route=panel_asistencia">Mi Asistencia</a></li>
          <li class="nav-item"><a class="nav-link" href="?route=panel_justificante">Mis Justificantes</a></li>
          <li class="nav-item"><a class="nav-link" href="?route=historial_justificantes">Historial</a></li>

        <?php else: ?>

          <li class="nav-item"><a class="nav-link" href="?route=empleados">Empleados</a></li>
          <li class="nav-item"><a class="nav-link" href="?route=departamentos">Departamentos</a></li>

          <!-- Dropdown Gestión -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Gestión</a>

            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="?route=solicitudes">Administrativa</a></li>
              <li><a class="dropdown-item" href="?route=disponibilidad">Disponibilidad</a></li>
              <li><a class="dropdown-item" href="?route=asistencia">Asistencias</a></li>
              <li>
                <a class="dropdown-item position-relative" href="?route=justificantes">
                  Justificantes
                  <?php if ($pendJust > 0): ?>
                    <span class="badge bg-danger rounded-pill ms-2"><?= $pendJust ?></span>
                  <?php endif; ?>
                </a>
              </li>
              <li><a class="dropdown-item" href="?route=asistencias_inconsistencias">Inconsistencias</a></li>
            </ul>
          </li>

          <li class="nav-item"><a class="nav-link" href="?route=reportes">Reportes</a></li>

          <?php if ($u['role'] === 'admin'): ?>
            <!-- Dropdown Respaldo BD -->
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle text-danger" href="#" data-bs-toggle="dropdown">Base de Datos</a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="index.php?route=backup">Generar respaldo</a></li>
                <li><a class="dropdown-item text-danger" href="index.php?route=backup_form">Restaurar BD</a></li>
              </ul>
            </li>

            <li class="nav-item"><a class="nav-link" href="?route=users">Usuarios</a></li>
          <?php endif; ?>

        <?php endif; ?>

      <?php endif; ?>

    </ul>

    <!-- Info usuario + botón salir -->
    <?php if ($u): ?>
      <span class="navbar-text me-3 text-muted">
        Bienvenido, <strong><?= htmlspecialchars($u['name']) ?></strong> (<?= $u['role'] ?>)
      </span>
      <a class="btn btn-outline-danger btn-sm" href="?route=logout">
        <i class="fa-solid fa-right-from-bracket"></i> Salir
      </a>
    <?php endif; ?>

  </div>
</nav>
