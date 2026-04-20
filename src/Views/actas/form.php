<div class="row justify-content-center">
    <div class="col-lg-7 col-xl-6">

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
