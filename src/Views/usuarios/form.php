<div class="row justify-content-center">
    <div class="col-lg-6 col-xl-5">

        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="<?= url('/usuarios') ?>" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h2 class="fw-bold mb-0">
                <i class="bi bi-person-<?= $editing ? 'gear' : 'plus' ?> me-2 text-primary"></i>
                <?= $editing ? 'Editar Usuario' : 'Crear Usuario' ?>
            </h2>
        </div>

        <!-- Leyenda de roles -->
        <div class="alert alert-info py-2 mb-4">
            <i class="bi bi-info-circle me-1"></i>
            <strong>Jerarquía:</strong>
            <span class="badge bg-danger ms-1">Administrador</span> acceso total &amp; gestión de usuarios ·
            <span class="badge bg-warning text-dark ms-1">Supervisor</span> reportes y registros ·
            <span class="badge bg-info text-dark ms-1">Operador</span> solo ingreso de datos
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <?php
                $action = $editing
                    ? url('/usuarios/' . ($usuario['id'] ?? '') . '/update')
                    : url('/usuarios');
                ?>
                <form method="POST" action="<?= $action ?>" novalidate>
                    <?= csrfField() ?>

                    <?php if (!$editing): ?>
                    <div class="mb-3">
                        <label for="username" class="form-label fw-semibold">
                            Nombre de Usuario <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="username" name="username"
                               class="form-control" required maxlength="50"
                               autocomplete="off"
                               placeholder="Ej: jperez">
                        <div class="form-text">Solo letras, números y guión bajo. Sin espacios.</div>
                    </div>
                    <?php else: ?>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nombre de Usuario</label>
                        <input type="text" class="form-control" readonly
                               value="<?= e($usuario['username'] ?? '') ?>">
                        <div class="form-text">El nombre de usuario no puede modificarse.</div>
                    </div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <label for="nombre_completo" class="form-label fw-semibold">Nombre Completo</label>
                        <input type="text" id="nombre_completo" name="nombre_completo"
                               class="form-control" maxlength="100"
                               value="<?= e($usuario['nombre_completo'] ?? '') ?>"
                               placeholder="Ej: Juan Carlos Pérez">
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Correo Electrónico</label>
                        <input type="email" id="email" name="email"
                               class="form-control" maxlength="100"
                               value="<?= e($usuario['email'] ?? '') ?>"
                               placeholder="correo@gobernacion.gob.ec">
                    </div>

                    <div class="mb-3">
                        <label for="rol" class="form-label fw-semibold">
                            Rol <span class="text-danger">*</span>
                        </label>
                        <select id="rol" name="rol" class="form-select" required>
                            <option value="">— Seleccione un rol —</option>
                            <?php foreach ($roles as $key => $label): ?>
                                <option value="<?= e($key) ?>"
                                    <?= (($usuario['rol'] ?? '') === $key) ? 'selected' : '' ?>>
                                    <?= e($label) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label fw-semibold">
                            Contraseña <?= $editing ? '' : '<span class="text-danger">*</span>' ?>
                        </label>
                        <input type="password" id="password" name="password"
                               class="form-control"
                               <?= $editing ? '' : 'required' ?>
                               autocomplete="new-password" minlength="6"
                               placeholder="<?= $editing ? 'Dejar vacío para no cambiar' : 'Mínimo 6 caracteres' ?>">
                        <?php if ($editing): ?>
                        <div class="form-text">Dejar vacío si no desea cambiar la contraseña.</div>
                        <?php else: ?>
                        <div class="form-text">Mínimo 6 caracteres.</div>
                        <?php endif; ?>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="bi bi-<?= $editing ? 'save' : 'person-plus' ?> me-2"></i>
                            <?= $editing ? 'Guardar Cambios' : 'Crear Usuario' ?>
                        </button>
                        <a href="<?= url('/usuarios') ?>" class="btn btn-outline-secondary">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
