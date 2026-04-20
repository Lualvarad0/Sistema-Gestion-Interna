<?php
$conCoords = array_values(array_filter($registros, fn($r) => !empty($r['latitud']) && !empty($r['longitud'])));
$mapPoints = array_map(fn($r) => [
    'lat'   => (float) $r['latitud'],
    'lng'   => (float) $r['longitud'],
    'title' => $r['nombreinstitucion'],
    'info'  => ($r['distrito'] ? e($r['distrito']) . ' — ' : '') . e($r['estado'] ?? ''),
], $conCoords);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0">
        <i class="bi bi-lightning-charge me-2 text-warning"></i>Registros CNEL
    </h2>
    <div class="d-flex gap-2">
        <?php if (!empty($mapPoints)): ?>
        <button class="btn btn-outline-secondary btn-sm" type="button"
                data-bs-toggle="collapse" data-bs-target="#mapa-cnel" aria-expanded="false">
            <i class="bi bi-map me-1"></i>Ver Mapa
            <span class="badge bg-secondary ms-1"><?= count($mapPoints) ?></span>
        </button>
        <?php endif; ?>
        <a href="<?= url('/cnel/nuevo') ?>" class="btn btn-warning">
            <i class="bi bi-plus-circle me-2"></i>Registrar
        </a>
    </div>
</div>

<?php if (!empty($mapPoints)): ?>
<div class="collapse mb-4" id="mapa-cnel">
    <div class="card border-0 shadow-sm">
        <div id="report-map-cnel" style="height:420px; border-radius:8px;"></div>
    </div>
</div>
<script>
(function () {
    const collapseEl = document.getElementById('mapa-cnel');
    let initialized = false;
    collapseEl.addEventListener('shown.bs.collapse', function () {
        if (initialized) return;
        initialized = true;
        const map = L.map('report-map-cnel').setView([-2.1975, -79.8862], 10);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© <a href="https://openstreetmap.org">OpenStreetMap</a>', maxZoom: 19
        }).addTo(map);
        const points = <?= json_encode($mapPoints) ?>;
        const bounds = points.map(p => {
            L.marker([p.lat, p.lng]).addTo(map)
                .bindPopup('<strong>' + p.title + '</strong><br><small>' + p.info + '</small>');
            return [p.lat, p.lng];
        });
        map.fitBounds(bounds, { padding: [40, 40] });
    });
})();
</script>
<?php endif; ?>

<div class="card border-0 shadow-sm">
    <?php if (empty($registros)): ?>
        <div class="card-body text-center py-5 text-muted">
            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
            No hay registros CNEL aún.
            <div class="mt-3">
                <a href="<?= url('/cnel/nuevo') ?>" class="btn btn-warning btn-sm">
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
                            <th class="text-center">Nuevas</th>
                            <th class="text-center">Mant.</th>
                            <th>Tipo</th>
                            <th class="text-center">Postes</th>
                            <th>Estado</th>
                            <th>Distrito</th>
                            <th>Trabajador</th>
                            <th class="text-center">Ubic.</th>
                            <th class="pe-4">Registro</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($registros as $i => $r): ?>
                        <tr>
                            <td class="ps-4 text-muted small"><?= $i + 1 ?></td>
                            <td class="fw-semibold"><?= e($r['nombreinstitucion']) ?></td>
                            <td class="text-center"><?= (int) $r['nuevasluminarias'] ?></td>
                            <td class="text-center"><?= (int) $r['mantenimiento'] ?></td>
                            <td><?= $r['tipo'] ? e($r['tipo']) : '<span class="text-muted">—</span>' ?></td>
                            <td class="text-center"><?= (int) $r['cantidad'] ?></td>
                            <td>
                                <?php
                                $estadoClass = match ($r['estado'] ?? '') {
                                    'Activo'      => 'bg-success-subtle text-success-emphasis border-success-subtle',
                                    'Completado'  => 'bg-primary-subtle text-primary-emphasis border-primary-subtle',
                                    'En proceso'  => 'bg-warning-subtle text-warning-emphasis border-warning-subtle',
                                    'Pendiente'   => 'bg-danger-subtle text-danger-emphasis border-danger-subtle',
                                    default       => 'bg-secondary-subtle text-secondary border-secondary-subtle',
                                };
                                ?>
                                <?php if ($r['estado']): ?>
                                    <span class="badge border <?= $estadoClass ?>">
                                        <?= e($r['estado']) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-muted small"><?= $r['distrito'] ? e($r['distrito']) : '—' ?></td>
                            <td class="small"><?= $r['nombretrabajador'] ? e($r['nombretrabajador']) : '<span class="text-muted">—</span>' ?></td>
                            <td class="text-center">
                                <?php if (!empty($r['latitud'])): ?>
                                    <i class="bi bi-geo-alt-fill text-warning" title="Lat: <?= e($r['latitud']) ?>, Lng: <?= e($r['longitud']) ?>"></i>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td class="pe-4 text-muted small">
                                <?= date('d/m/Y', strtotime($r['created_at'])) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-transparent text-muted small py-2 px-4">
            Total: <strong><?= count($registros) ?></strong> registro(s)
        </div>
    <?php endif; ?>
</div>
