<?php
use App\Models\User;
$rol      = $currentUser['rol'] ?? 'operador';
$rolLabel = User::getRoleLabel($rol);
$rolColor = User::getRoleColor($rol);
?>
<div class="row justify-content-center">
    <div class="col-lg-7 col-xl-6">

        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="<?= url('/') ?>" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h2 class="fw-bold mb-0">
                <i class="bi bi-person-circle me-2 text-primary"></i>Mi Perfil
            </h2>
        </div>

        <!-- Tarjeta de información de cuenta -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <h6 class="text-muted fw-semibold text-uppercase small mb-3 border-bottom pb-2">
                    Información de Cuenta
                </h6>
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:60px; height:60px;">
                        <i class="bi bi-person-fill fs-2 text-primary"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-5"><?= e($currentUser['username']) ?></div>
                        <span class="badge bg-<?= e($rolColor) ?> mt-1"><?= e($rolLabel) ?></span>
                    </div>
                </div>
                <div class="row g-2 text-muted small">
                    <div class="col-sm-6">
                        <i class="bi bi-shield-check me-1"></i>
                        Rol: <strong class="text-dark"><?= e($rolLabel) ?></strong>
                    </div>
                    <?php if (!empty($userRecord['nombre_completo'])): ?>
                    <div class="col-sm-6">
                        <i class="bi bi-person me-1"></i>
                        <?= e($userRecord['nombre_completo']) ?>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($userRecord['email'])): ?>
                    <div class="col-12">
                        <i class="bi bi-envelope me-1"></i>
                        <?= e($userRecord['email']) ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Editar datos del perfil -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <h6 class="text-muted fw-semibold text-uppercase small mb-3 border-bottom pb-2">
                    Editar Datos del Perfil
                </h6>
                <form method="POST" action="<?= url('/perfil/profile') ?>" novalidate>
                    <?= csrfField() ?>

                    <div class="mb-3">
                        <label for="nombre_completo" class="form-label fw-semibold">Nombre Completo</label>
                        <input type="text" id="nombre_completo" name="nombre_completo"
                               class="form-control" maxlength="100"
                               value="<?= e($userRecord['nombre_completo'] ?? '') ?>"
                               placeholder="Ej: Juan Carlos Pérez López">
                    </div>

                    <div class="mb-4">
                        <label for="email" class="form-label fw-semibold">Correo Electrónico</label>
                        <input type="email" id="email" name="email"
                               class="form-control" maxlength="100"
                               value="<?= e($userRecord['email'] ?? '') ?>"
                               placeholder="correo@ejemplo.com">
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i>Guardar Cambios
                    </button>
                </form>
            </div>
        </div>

        <!-- Cambiar contraseña -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h6 class="text-muted fw-semibold text-uppercase small mb-3 border-bottom pb-2">
                    Cambiar Contraseña
                </h6>
                <form method="POST" action="<?= url('/perfil/password') ?>" novalidate>
                    <?= csrfField() ?>

                    <div class="mb-3">
                        <label for="password_actual" class="form-label fw-semibold">
                            Contraseña Actual <span class="text-danger">*</span>
                        </label>
                        <input type="password" id="password_actual" name="password_actual"
                               class="form-control" required autocomplete="current-password">
                    </div>

                    <div class="mb-3">
                        <label for="password_nuevo" class="form-label fw-semibold">
                            Nueva Contraseña <span class="text-danger">*</span>
                        </label>
                        <input type="password" id="password_nuevo" name="password_nuevo"
                               class="form-control" required autocomplete="new-password" minlength="6">
                        <div class="form-text">Mínimo 6 caracteres.</div>
                    </div>

                    <div class="mb-4">
                        <label for="password_confirm" class="form-label fw-semibold">
                            Confirmar Nueva Contraseña <span class="text-danger">*</span>
                        </label>
                        <input type="password" id="password_confirm" name="password_confirm"
                               class="form-control" required autocomplete="new-password">
                    </div>

                    <button type="submit" class="btn btn-outline-primary">
                        <i class="bi bi-lock me-2"></i>Actualizar Contraseña
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>
