<?php
$iconosPorTipo = [
    'info'    => 'info-circle-fill',
    'success' => 'check-circle-fill',
    'warning' => 'exclamation-triangle-fill',
    'danger'  => 'x-circle-fill',
];
$colorsPorModulo = [
    'colegios'   => 'primary',
    'cnel'       => 'warning',
    'encuentros' => 'success',
    'actas'      => 'info',
    'usuarios'   => 'danger',
];
$unreadCount = count(array_filter($notificaciones, fn($n) => !$n['leida']));
?>

<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div class="d-flex align-items-center gap-3">
        <a href="<?= url('/') ?>" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h2 class="fw-bold mb-0">
                <i class="bi bi-bell me-2 text-primary"></i>Notificaciones
            </h2>
            <?php if ($unreadCount > 0): ?>
                <small class="text-muted"><?= $unreadCount ?> sin leer</small>
            <?php else: ?>
                <small class="text-muted">Todas leídas</small>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($unreadCount > 0): ?>
    <form method="POST" action="<?= url('/notificaciones/leer-todas') ?>">
        <?= csrfField() ?>
        <button type="submit" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-check2-all me-1"></i>Marcar todas como leídas
        </button>
    </form>
    <?php endif; ?>
</div>

<?php if (empty($notificaciones)): ?>
    <div class="text-center py-5 text-muted">
        <i class="bi bi-bell-slash fs-1 d-block mb-3 opacity-50"></i>
        <p class="mb-0">No hay notificaciones disponibles.</p>
    </div>
<?php else: ?>
<div class="d-flex flex-column gap-2">
    <?php foreach ($notificaciones as $n): ?>
    <?php
    $tipo   = $n['tipo'] ?? 'info';
    $icono  = $iconosPorTipo[$tipo] ?? 'info-circle-fill';
    $modulo = $n['modulo'] ?? '';
    $badgeColor = $colorsPorModulo[$modulo] ?? 'secondary';
    ?>
    <div class="card border-0 shadow-sm <?= $n['leida'] ? 'opacity-75' : '' ?>">
        <div class="card-body py-3 px-4">
            <div class="d-flex align-items-start gap-3">
                <div class="flex-shrink-0 mt-1">
                    <i class="bi bi-<?= e($icono) ?> text-<?= e($tipo) ?> fs-5"></i>
                </div>
                <div class="flex-grow-1 min-w-0">
                    <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                        <strong class="<?= $n['leida'] ? 'text-muted fw-normal' : '' ?>">
                            <?= e($n['titulo']) ?>
                        </strong>
                        <?php if ($modulo): ?>
                            <span class="badge bg-<?= e($badgeColor) ?>-subtle text-<?= e($badgeColor) ?> border border-<?= e($badgeColor) ?> small">
                                <?= e(ucfirst($modulo)) ?>
                            </span>
                        <?php endif; ?>
                        <?php if (!$n['leida']): ?>
                            <span class="badge bg-primary rounded-pill" style="font-size:.6rem;">Nuevo</span>
                        <?php endif; ?>
                    </div>
                    <p class="mb-1 small text-muted"><?= e($n['mensaje']) ?></p>
                    <small class="text-muted">
                        <i class="bi bi-clock me-1"></i>
                        <?= date('d/m/Y H:i', strtotime($n['created_at'])) ?>
                    </small>
                </div>
                <?php if (!$n['leida']): ?>
                <div class="flex-shrink-0">
                    <form method="POST" action="<?= url('/notificaciones/' . $n['id'] . '/leer') ?>">
                        <?= csrfField() ?>
                        <button type="submit" class="btn btn-sm btn-outline-secondary" title="Marcar como leída">
                            <i class="bi bi-check2"></i>
                        </button>
                    </form>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
