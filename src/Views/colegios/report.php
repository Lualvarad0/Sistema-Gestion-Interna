<?php
$conCoords = array_values(array_filter($colegios, fn($r) => !empty($r['latitud']) && !empty($r['longitud'])));
$mapPoints = array_map(fn($r) => [
    'lat'   => (float) $r['latitud'],
    'lng'   => (float) $r['longitud'],
    'title' => $r['nombreinstitucion'],
    'info'  => e($r['direccion']) . ' — ' . e($r['distrito']),
], $conCoords);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0">
        <i class="bi bi-building me-2 text-primary"></i>Colegios
    </h2>
    <div class="d-flex gap-2">
        <?php if (!empty($mapPoints)): ?>
        <button class="btn btn-outline-secondary btn-sm" type="button"
                data-bs-toggle="collapse" data-bs-target="#mapa-colegios" aria-expanded="false">
            <i class="bi bi-map me-1"></i>Ver Mapa
            <span class="badge bg-secondary ms-1"><?= count($mapPoints) ?></span>
        </button>
        <?php endif; ?>
        <a href="<?= url('/colegios/nuevo') ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Registrar
        </a>
    </div>
</div>

<?php if (!empty($mapPoints)): ?>
<div class="collapse mb-4" id="mapa-colegios">
    <div class="card border-0 shadow-sm">
        <div id="report-map-colegios" style="height:420px; border-radius:8px;"></div>
    </div>
</div>
<script>
(function () {
    const collapseEl = document.getElementById('mapa-colegios');
    let initialized = false;
    collapseEl.addEventListener('shown.bs.collapse', function () {
        if (initialized) return;
        initialized = true;
        const map = L.map('report-map-colegios').setView([-2.1975, -79.8862], 10);
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
                            <th class="text-center">Ubic.</th>
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
                            <td class="text-center">
                                <?php if (!empty($c['latitud'])): ?>
                                    <i class="bi bi-geo-alt-fill text-danger" title="Lat: <?= e($c['latitud']) ?>, Lng: <?= e($c['longitud']) ?>"></i>
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
