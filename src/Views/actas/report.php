<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0">
        <i class="bi bi-file-earmark-text me-2 text-info"></i>Actas de Trabajadores
    </h2>
    <a href="<?= url('/actas/nuevo') ?>" class="btn btn-info">
        <i class="bi bi-plus-circle me-2"></i>Registrar
    </a>
</div>

<div class="card border-0 shadow-sm">
    <?php if (empty($actas)): ?>
        <div class="card-body text-center py-5 text-muted">
            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
            No hay trabajadores registrados aún.
            <div class="mt-3">
                <a href="<?= url('/actas/nuevo') ?>" class="btn btn-info btn-sm">
                    <i class="bi bi-plus-circle me-1"></i>Registrar primero
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">#</th>
                            <th>Código</th>
                            <th>Nombre Completo</th>
                            <th>Cédula</th>
                            <th>Parroquia</th>
                            <th class="pe-4">Registro</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($actas as $i => $acta): ?>
                        <tr>
                            <td class="ps-4 text-muted small"><?= $i + 1 ?></td>
                            <td>
                                <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle">
                                    <?= e($acta['codtrabajador']) ?>
                                </span>
                            </td>
                            <td class="fw-semibold"><?= e($acta['nombretrabajador']) ?></td>
                            <td class="text-muted small font-monospace"><?= e($acta['cedula']) ?></td>
                            <td><?= e($acta['parroquia']) ?></td>
                            <td class="pe-4 text-muted small">
                                <?= date('d/m/Y', strtotime($acta['created_at'])) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-transparent text-muted small py-2 px-4">
            Total: <strong><?= count($actas) ?></strong> trabajador(es)
        </div>
    <?php endif; ?>
</div>
