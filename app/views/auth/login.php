<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Sistema de Gestión de Recursos Humanos</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body {
        background: #f5f7fa;
    }

    .login-header {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 6px rgb(0 0 0 / 10%);
        padding: 12px 20px;
        font-size: 1.2rem;
        font-weight: bold;
    }

    .login-card {
        border-radius: 15px;
    }

    .login-title {
        font-weight: 700;
        color: #1a1a1a;
    }
</style>
</head>

<body>



<div class="container py-4">

<!-- ICONOS (Bootstrap Icons) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<!-- Encabezado empresarial -->
<div class="d-flex align-items-center mb-4 p-3 rounded shadow-sm"
     style="background: #0A2342; color: white;">
    
    <i class="bi bi-building-check fs-4 me-2"></i>

    <span class="fw-bold fs-5">
        Sistema de Gestión de Recursos Humanos
    </span>
</div>


    <h2 class="text-center mb-4 login-title">
        Bienvenido
    </h2>

    <div class="row justify-content-center">
        <div class="col-md-4">

     
            <div class="card shadow-sm login-card">
                <div class="card-body">

                    <h5 class="card-title mb-3">Iniciar sesión</h5>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger">
                            <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>

                    <form method="post">
                        <input type="hidden" name="_csrf" value="<?= htmlspecialchars($_SESSION['csrf'] ?? '') ?>">

                        <div class="mb-3">
                            <label class="form-label">Correo</label>
                            <input class="form-control" type="email" name="email" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Contraseña</label>
                            <input class="form-control" type="password" name="password" minlength="8" required>
                        </div>

                        <button class="btn btn-primary w-100">
                            Entrar
                        </button>
                    </form>
                </div>
            </div>

            <p class="text-center text-muted mt-3 small">
                Usuario demo: admin@demo.com / Admin123*
            </p>
        </div>
    </div>

</div>

</body>
</html>
