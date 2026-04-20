<?php
use App\Models\User;
?>
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div class="d-flex align-items-center gap-3">
        <a href="<?= url('/') ?>" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h2 class="fw-bold mb-0">
                <i class="bi bi-people-gear me-2 text-primary"></i>Gestión de Usuarios
            </h2>
            <small class="text-muted"><?= count($usuarios) ?> usuario(s) registrado(s)</small>
        </div>
    </div>
    <a href="<?= url('/usuarios/nuevo') ?>" class="btn btn-primary">
        <i class="bi bi-person-plus me-2"></i>Nuevo Usuario
    </a>
</div>

<!-- Leyenda de roles -->
<div class="d-flex flex-wrap gap-2 mb-4">
    <?php foreach ($roles as $key => $label): ?>
        <span class="badge bg-<?= e(User::getRoleColor($key)) ?> fs-6 px-3 py-2">
            <i class="bi bi-circle-fill me-1" style="font-size:.5rem;"></i><?= e($label) ?>
        </span>
    <?php endforeach; ?>
    <span class="text-muted small align-self-center ms-2">Jerarquía: Administrador &gt; Supervisor &gt; Operador</span>
</div>

<?php if (empty($usuarios)): ?>
    <div class="alert alert-info">No hay usuarios registrados.</div>
<?php else: ?>
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Usuario</th>
                    <th>Nombre Completo</th>
                    <th>Correo</th>
                    <th class="text-center">Rol</th>
                    <th class="text-center">Estado</th>
                    <th>Creado</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($usuarios as $u): ?>
                <tr class="<?= $u['activo'] ? '' : 'table-secondary text-muted' ?>">
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                 style="width:34px; height:34px;">
                                <i class="bi bi-person-fill text-primary small"></i>
                            </div>
                            <strong><?= e($u['username']) ?></strong>
                        </div>
                    </td>
                    <td><?= $u['nombre_completo'] ? e($u['nombre_completo']) : '<span class="text-muted fst-italic small">Sin nombre</span>' ?></td>
                    <td><?= $u['email'] ? e($u['email']) : '<span class="text-muted fst-italic small">—</span>' ?></td>
                    <td class="text-center">
                        <span class="badge bg-<?= e(User::getRoleColor($u['rol'])) ?>">
                            <?= e(User::getRoleLabel($u['rol'])) ?>
                        </span>
                    </td>
                    <td class="text-center">
                        <?php if ($u['activo']): ?>
                            <span class="badge bg-success-subtle text-success border border-success">Activo</span>
                        <?php else: ?>
                            <span class="badge bg-danger-subtle text-danger border border-danger">Inactivo</span>
                        <?php endif; ?>
                    </td>
                    <td class="small text-muted">
                        <?= date('d/m/Y', strtotime($u['created_at'])) ?>
                    </td>
                    <td class="text-center">
                        <div class="d-flex gap-1 justify-content-center">
                            <a href="<?= url('/usuarios/' . $u['id'] . '/editar') ?>"
                               class="btn btn-sm btn-outline-primary" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <?php
                            $selfId = $currentUser['id'] ?? 0;
                            if ($u['id'] != $selfId):
                            ?>
                            <form method="POST" action="<?= url('/usuarios/' . $u['id'] . '/toggle') ?>"
                                  onsubmit="return confirm('¿Confirma cambiar el estado de este usuario?');">
                                <?= csrfField() ?>
                                <button type="submit"
                                        class="btn btn-sm <?= $u['activo'] ? 'btn-outline-danger' : 'btn-outline-success' ?>"
                                        title="<?= $u['activo'] ? 'Desactivar' : 'Activar' ?>">
                                    <i class="bi bi-<?= $u['activo'] ? 'person-dash' : 'person-check' ?>"></i>
                                </button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>
