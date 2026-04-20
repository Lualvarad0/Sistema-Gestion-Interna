<div class="row justify-content-center">
    <div class="col-lg-8 col-xl-7">

        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="<?= url('/encuentros') ?>" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h2 class="fw-bold mb-0">
                <i class="bi bi-people me-2 text-success"></i>Registrar Encuentro Ciudadano
            </h2>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 p-md-5">
                <form method="POST" action="<?= url('/encuentros') ?>" novalidate>
                    <?= csrfField() ?>

                    <div class="row g-3">
                        <div class="col-12">
                            <label for="direccion" class="form-label fw-semibold">
                                Dirección <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="direccion" name="direccion"
                                   class="form-control" required maxlength="200"
                                   placeholder="Calle principal y secundaria, número">
                        </div>

                        <div class="col-md-6">
                            <label for="parroquia" class="form-label fw-semibold">
                                Parroquia <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="parroquia" name="parroquia"
                                   class="form-control" required maxlength="100"
                                   placeholder="Ej: Parroquia Tarqui">
                        </div>

                        <div class="col-md-6">
                            <label for="estado" class="form-label fw-semibold">
                                Estado <span class="text-danger">*</span>
                            </label>
                            <select id="estado" name="estado" class="form-select" required>
                                <option value="">— Seleccionar —</option>
                                <option value="Pendiente">Pendiente</option>
                                <option value="En proceso">En proceso</option>
                                <option value="Realizado">Realizado</option>
                                <option value="Cancelado">Cancelado</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <hr class="my-1">
                            <p class="text-muted small mb-3 mt-2">
                                <i class="bi bi-person me-1"></i>Datos de contacto ciudadano
                            </p>
                        </div>

                        <div class="col-md-6">
                            <label for="nombrecontacto" class="form-label fw-semibold">
                                Nombre del Contacto <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="nombrecontacto" name="nombrecontacto"
                                   class="form-control" required maxlength="100"
                                   placeholder="Nombres y apellidos">
                        </div>

                        <div class="col-md-3">
                            <label for="cedula" class="form-label fw-semibold">
                                Cédula <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="cedula" name="cedula"
                                   class="form-control" required maxlength="20"
                                   placeholder="0000000000">
                        </div>

                        <div class="col-md-3">
                            <label for="telefono" class="form-label fw-semibold">
                                Teléfono <span class="text-danger">*</span>
                            </label>
                            <input type="tel" id="telefono" name="telefono"
                                   class="form-control" required maxlength="20"
                                   placeholder="09XXXXXXXX">
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success px-4">
                            <i class="bi bi-save me-2"></i>Guardar Encuentro
                        </button>
                        <a href="<?= url('/encuentros') ?>" class="btn btn-outline-secondary px-4">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
