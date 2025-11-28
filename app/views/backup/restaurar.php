<?php require_once __DIR__ . '/../layout/header.php'; ?>
<?php require_once __DIR__ . '/../layout/nav.php'; ?>

<div class="container mt-5" style="max-width: 650px;">

    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body p-4">

            <h2 class="mb-3 text-center">
                 Restaurar Base de Datos
            </h2>

            <p class="text-muted text-center mb-4">
                Sube un archivo <strong>.sql</strong> para restaurar todo el sistema.
            </p>

            <!-- Mensajes -->
            <?php if (isset($error)): ?>
                <div class="alert alert-danger text-center fw-bold"><?= $error ?></div>
            <?php endif; ?>

            <?php if (isset($success)): ?>
                <div class="alert alert-success text-center fw-bold"><?= $success ?></div>
            <?php endif; ?>

            <!-- Formulario -->
            <form action="index.php?route=backup_restaurar" method="POST" enctype="multipart/form-data">

                <div class="mb-3">
                    <label class="form-label fw-semibold">Seleccionar archivo SQL</label>
                    <input type="file" name="sql_file" accept=".sql" class="form-control form-control-lg" required>
                </div>

                <button class="btn btn-warning w-100 fw-bold py-2 mb-3" type="submit" style="font-size: 1.1rem;">
                     Restaurar Base de Datos
                </button>

                <a href="index.php?route=dashboard" 
                   class="btn btn-secondary w-100 fw-bold py-2" 
                   style="font-size: 1.05rem;">
                    ⬅ Regresar al Dashboard
                </a>

            </form>

        </div>
    </div>

</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
