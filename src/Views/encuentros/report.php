<?php
$conCoords = array_values(array_filter($encuentros, fn($r) => !empty($r['latitud']) && !empty($r['longitud'])));
$mapPoints = array_map(fn($r) => [
    'lat'   => (float) $r['latitud'],
    'lng'   => (float) $r['longitud'],
    'title' => $r['parroquia'],
    'info'  => e($r['direccion']) . ' — ' . e($r['estado']),
], $conCoords);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0">
        <i class="bi bi-people me-2 text-success"></i>Encuentros Ciudadanos
    </h2>
    <div class="d-flex gap-2">
        <?php if (!empty($mapPoints)): ?>
        <button class="btn btn-outline-secondary btn-sm" type="button"
                data-bs-toggle="collapse" data-bs-target="#mapa-encuentros" aria-expanded="false">
            <i class="bi bi-map me-1"></i>Ver Mapa
            <span class="badge bg-secondary ms-1"><?= count($mapPoints) ?></span>
        </button>
        <?php endif; ?>
        <a href="<?= url('/encuentros/nuevo') ?>" class="btn btn-success">
            <i class="bi bi-plus-circle me-2"></i>Registrar
        </a>
    </div>
</div>

<?php if (!empty($mapPoints)): ?>
<div class="collapse mb-4" id="mapa-encuentros">
    <div class="card border-0 shadow-sm">
        <div id="report-map-encuentros" style="height:420px; border-radius:8px;"></div>
    </div>
</div>
<script>
(function () {
    const collapseEl = document.getElementById('mapa-encuentros');
    let initialized = false;
    collapseEl.addEventListener('shown.bs.collapse', function () {
        if (initialized) return;
        initialized = true;
        const map = L.map('report-map-encuentros').setView([-2.1975, -79.8862], 10);
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
    <?php if (empty($encuentros)): ?>
        <div class="card-body text-center py-5 text-muted">
            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
            No hay encuentros registrados aún.
            <div class="mt-3">
                <a href="<?= url('/encuentros/nuevo') ?>" class="btn btn-success btn-sm">
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
                            <th>Dirección</th>
                            <th>Parroquia</th>
                            <th>Estado</th>
                            <th>Contacto</th>
                            <th>Cédula</th>
                            <th>Teléfono</th>
                            <th class="text-center">Ubic.</th>
                            <th class="pe-4">Registro</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($encuentros as $i => $enc): ?>
                        <tr>
                            <td class="ps-4 text-muted small"><?= $i + 1 ?></td>
                            <td class="small"><?= e($enc['direccion']) ?></td>
                            <td><?= e($enc['parroquia']) ?></td>
                            <td>
                                <?php
                                $estadoClass = match ($enc['estado']) {
                                    'Realizado'  => 'bg-success-subtle text-success-emphasis border-success-subtle',
                                    'En proceso' => 'bg-warning-subtle text-warning-emphasis border-warning-subtle',
                                    'Pendiente'  => 'bg-secondary-subtle text-secondary border-secondary-subtle',
                                    'Cancelado'  => 'bg-danger-subtle text-danger-emphasis border-danger-subtle',
                                    default      => 'bg-light text-muted border-light',
                                };
                                ?>
                                <span class="badge border <?= $estadoClass ?>">
                                    <?= e($enc['estado']) ?>
                                </span>
                            </td>
                            <td class="fw-semibold"><?= e($enc['nombrecontacto']) ?></td>
                            <td class="text-muted small"><?= e($enc['cedula']) ?></td>
                            <td class="text-muted small"><?= e($enc['telefono']) ?></td>
                            <td class="text-center">
                                <?php if (!empty($enc['latitud'])): ?>
                                    <i class="bi bi-geo-alt-fill text-success" title="Lat: <?= e($enc['latitud']) ?>, Lng: <?= e($enc['longitud']) ?>"></i>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td class="pe-4 text-muted small">
                                <?= date('d/m/Y', strtotime($enc['created_at'])) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-transparent text-muted small py-2 px-4">
            Total: <strong><?= count($encuentros) ?></strong> registro(s)
        </div>
    <?php endif; ?>
</div>
