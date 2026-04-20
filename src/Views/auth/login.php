<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión — Gobernación del Guayas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?= asset('css/app.css') ?>" rel="stylesheet">
</head>
<body class="login-bg d-flex align-items-center justify-content-center min-vh-100">

<div class="login-card card border-0 shadow-lg">
    <div class="card-body p-5">

        <div class="text-center mb-4">
            <div class="login-logo mb-3">
                <i class="bi bi-building-fill-gear text-primary" style="font-size: 3.5rem;"></i>
            </div>
            <h4 class="fw-bold mb-1">Gobernación del Guayas</h4>
            <p class="text-muted small mb-0">Sistema de Gestión Interna</p>
        </div>

        <?php if (!empty($flash)): ?>
            <div class="alert alert-<?= $flash['type'] === 'error' ? 'danger' : e($flash['type']) ?> d-flex align-items-center gap-2 py-2 small">
                <i class="bi bi-exclamation-circle-fill flex-shrink-0"></i>
                <?= e($flash['message']) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= url('/login') ?>" novalidate>
            <?= csrfField() ?>

            <div class="mb-3">
                <label for="username" class="form-label fw-semibold small">Usuario</label>
                <div class="input-group">
                    <span class="input-group-text bg-light">
                        <i class="bi bi-person text-muted"></i>
                    </span>
                    <input type="text" id="username" name="username"
                           class="form-control" placeholder="Ingrese su usuario"
                           required autofocus autocomplete="username">
                </div>
            </div>

            <div class="mb-4">
                <label for="password" class="form-label fw-semibold small">Contraseña</label>
                <div class="input-group">
                    <span class="input-group-text bg-light">
                        <i class="bi bi-lock text-muted"></i>
                    </span>
                    <input type="password" id="password" name="password"
                           class="form-control" placeholder="Ingrese su contraseña"
                           required autocomplete="current-password">
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword"
                            title="Mostrar/ocultar contraseña">
                        <i class="bi bi-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 fw-semibold py-2">
                <i class="bi bi-box-arrow-in-right me-2"></i>Ingresar
            </button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('togglePassword').addEventListener('click', function () {
        const input   = document.getElementById('password');
        const icon    = document.getElementById('eyeIcon');
        const showing = input.type === 'text';
        input.type    = showing ? 'password' : 'text';
        icon.className = showing ? 'bi bi-eye' : 'bi bi-eye-slash';
    });
</script>
</body>
</html>
