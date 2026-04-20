<div class="row g-0 mb-4">
    <div class="col">
        <h2 class="fw-bold mb-1">
            <i class="bi bi-grid-3x3-gap me-2 text-primary"></i>Dashboard
        </h2>
        <p class="text-muted mb-0">
            Bienvenido, <strong><?= e($currentUser['username']) ?></strong>.
            Resumen general del sistema.
        </p>
    </div>
</div>

<!-- Tarjetas de estadísticas -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100 stat-card">
            <div class="card-body d-flex align-items-center gap-3 p-4">
                <div class="stat-icon-wrap bg-primary bg-opacity-10 rounded-3 p-3">
                    <i class="bi bi-building fs-2 text-primary"></i>
                </div>
                <div>
                    <div class="display-6 fw-bold lh-1"><?= $stats['colegios'] ?></div>
                    <div class="text-muted small mt-1">Colegios registrados</div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-top-0 pb-3 px-4">
                <a href="<?= url('/colegios') ?>" class="btn btn-primary btn-sm w-100">
                    Ver registros <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100 stat-card">
            <div class="card-body d-flex align-items-center gap-3 p-4">
                <div class="stat-icon-wrap bg-warning bg-opacity-10 rounded-3 p-3">
                    <i class="bi bi-lightning-charge fs-2 text-warning"></i>
                </div>
                <div>
                    <div class="display-6 fw-bold lh-1"><?= $stats['cnel'] ?></div>
                    <div class="text-muted small mt-1">Registros CNEL</div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-top-0 pb-3 px-4">
                <a href="<?= url('/cnel') ?>" class="btn btn-warning btn-sm w-100">
                    Ver registros <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100 stat-card">
            <div class="card-body d-flex align-items-center gap-3 p-4">
                <div class="stat-icon-wrap bg-success bg-opacity-10 rounded-3 p-3">
                    <i class="bi bi-people fs-2 text-success"></i>
                </div>
                <div>
                    <div class="display-6 fw-bold lh-1"><?= $stats['encuentros'] ?></div>
                    <div class="text-muted small mt-1">Encuentros ciudadanos</div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-top-0 pb-3 px-4">
                <a href="<?= url('/encuentros') ?>" class="btn btn-success btn-sm w-100">
                    Ver registros <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100 stat-card">
            <div class="card-body d-flex align-items-center gap-3 p-4">
                <div class="stat-icon-wrap bg-info bg-opacity-10 rounded-3 p-3">
                    <i class="bi bi-file-earmark-text fs-2 text-info"></i>
                </div>
                <div>
                    <div class="display-6 fw-bold lh-1"><?= $stats['actas'] ?></div>
                    <div class="text-muted small mt-1">Trabajadores / Actas</div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-top-0 pb-3 px-4">
                <a href="<?= url('/actas') ?>" class="btn btn-info btn-sm w-100">
                    Ver registros <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Acciones rápidas -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom py-3">
        <h6 class="mb-0 fw-semibold text-muted">
            <i class="bi bi-lightning me-2"></i>Acciones rápidas
        </h6>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-sm-6 col-md-3">
                <a href="<?= url('/colegios/nuevo') ?>" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-2 py-3">
                    <i class="bi bi-plus-circle fs-5"></i>
                    <span>Nuevo Colegio</span>
                </a>
            </div>
            <div class="col-sm-6 col-md-3">
                <a href="<?= url('/cnel/nuevo') ?>" class="btn btn-outline-warning w-100 d-flex align-items-center justify-content-center gap-2 py-3">
                    <i class="bi bi-plus-circle fs-5"></i>
                    <span>Nuevo CNEL</span>
                </a>
            </div>
            <div class="col-sm-6 col-md-3">
                <a href="<?= url('/encuentros/nuevo') ?>" class="btn btn-outline-success w-100 d-flex align-items-center justify-content-center gap-2 py-3">
                    <i class="bi bi-plus-circle fs-5"></i>
                    <span>Nuevo Encuentro</span>
                </a>
            </div>
            <div class="col-sm-6 col-md-3">
                <a href="<?= url('/actas/nuevo') ?>" class="btn btn-outline-info w-100 d-flex align-items-center justify-content-center gap-2 py-3">
                    <i class="bi bi-plus-circle fs-5"></i>
                    <span>Nuevo Trabajador</span>
                </a>
            </div>
        </div>
    </div>
</div>
