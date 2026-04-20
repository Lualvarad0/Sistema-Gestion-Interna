<div class="row justify-content-center">
    <div class="col-lg-9 col-xl-8">

        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="<?= url('/actas') ?>" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h2 class="fw-bold mb-0">
                <i class="bi bi-file-earmark-text me-2 text-info"></i>Registrar Trabajador
            </h2>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 p-md-5">
                <form method="POST" action="<?= url('/actas') ?>" novalidate>
                    <?= csrfField() ?>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="codtrabajador" class="form-label fw-semibold">
                                Código <span class="text-danger">*</span>
                            </label>
                            <input type="number" id="codtrabajador" name="codtrabajador"
                                   class="form-control" required min="1"
                                   placeholder="Ej: 1001">
                        </div>

                        <div class="col-md-8">
                            <label for="nombretrabajador" class="form-label fw-semibold">
                                Nombre Completo <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="nombretrabajador" name="nombretrabajador"
                                   class="form-control" required maxlength="100"
                                   placeholder="Nombres y apellidos completos">
                        </div>

                        <div class="col-md-6">
                            <label for="cedula" class="form-label fw-semibold">
                                Cédula <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="cedula" name="cedula"
                                   class="form-control" required maxlength="20"
                                   placeholder="0000000000">
                            <div class="form-text">Debe ser única en el sistema.</div>
                        </div>

                        <div class="col-md-6">
                            <label for="parroquia" class="form-label fw-semibold">
                                Parroquia <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="parroquia" name="parroquia"
                                   class="form-control" required maxlength="100"
                                   placeholder="Ej: Parroquia Tarqui">
                        </div>

                        <div class="col-12 mt-2">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-geo-alt me-1 text-danger"></i>Geolocalización
                                <span class="text-muted fw-normal small">(opcional)</span>
                            </label>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <button type="button" id="btn-geolocate" class="btn btn-outline-secondary btn-sm">
                                    <i class="bi bi-crosshair me-1"></i>Usar mi ubicación
                                </button>
                                <span id="geo-coords" class="text-muted small"></span>
                            </div>
                            <div id="map-picker" style="height:300px; border-radius:8px; border:1px solid #dee2e6;"></div>
                            <div class="form-text">Haz clic en el mapa para marcar la ubicación exacta.</div>
                            <input type="hidden" id="latitud"  name="latitud"  value="">
                            <input type="hidden" id="longitud" name="longitud" value="">
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-info px-4 fw-semibold">
                            <i class="bi bi-save me-2"></i>Guardar Trabajador
                        </button>
                        <a href="<?= url('/actas') ?>" class="btn btn-outline-secondary px-4">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    const map = L.map('map-picker').setView([-2.1975, -79.8862], 12);
    let marker = null;

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="https://openstreetmap.org">OpenStreetMap</a>',
        maxZoom: 19
    }).addTo(map);

    function setMarker(lat, lng) {
        if (marker) map.removeLayer(marker);
        marker = L.marker([lat, lng]).addTo(map);
        document.getElementById('latitud').value  = lat.toFixed(7);
        document.getElementById('longitud').value = lng.toFixed(7);
        document.getElementById('geo-coords').textContent =
            'Lat: ' + lat.toFixed(5) + ', Lng: ' + lng.toFixed(5);
    }

    map.on('click', e => setMarker(e.latlng.lat, e.latlng.lng));

    document.getElementById('btn-geolocate').addEventListener('click', function () {
        if (!navigator.geolocation) return;
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Detectando...';
        navigator.geolocation.getCurrentPosition(
            pos => {
                setMarker(pos.coords.latitude, pos.coords.longitude);
                map.setView([pos.coords.latitude, pos.coords.longitude], 16);
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-crosshair me-1"></i>Usar mi ubicación';
            },
            () => {
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-crosshair me-1"></i>Usar mi ubicación';
            }
        );
    });
})();
</script>
