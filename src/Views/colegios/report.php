<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0">
        <i class="bi bi-building me-2 text-primary"></i>Colegios
    </h2>
    <a href="<?= url('/colegios/nuevo') ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Registrar
    </a>
</div>

<div class="card border-0 shadow-sm">
    <?php if (empty($colegios)): ?>
        <div class="card-body text-center py-5 text-muted">
            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
            No hay colegios registrados aún.
            <div class="mt-3">
                <a href="<?= url('/colegios/nuevo') ?>" class="btn btn-primary btn-sm">
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
                            <th>Institución</th>
                            <th>Rector</th>
                            <th>Dirección</th>
                            <th>Teléfono</th>
                            <th>Distrito</th>
                            <th>CNEL Vinculado</th>
                            <th class="pe-4">Registro</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($colegios as $i => $c): ?>
                        <tr>
                            <td class="ps-4 text-muted small"><?= $i + 1 ?></td>
                            <td class="fw-semibold"><?= e($c['nombreinstitucion']) ?></td>
                            <td><?= e($c['rector']) ?></td>
                            <td class="text-muted small"><?= e($c['direccion']) ?></td>
                            <td><?= e($c['telefono']) ?></td>
                            <td>
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">
                                    <?= e($c['distrito']) ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($c['cnel_institucion']): ?>
                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">
                                        <?= e($c['cnel_institucion']) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td class="pe-4 text-muted small">
                                <?= date('d/m/Y', strtotime($c['created_at'])) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-transparent text-muted small py-2 px-4">
            Total: <strong><?= count($colegios) ?></strong> registro(s)
        </div>
    <?php endif; ?>
</div>
