<div class="row justify-content-center">
    <div class="col-lg-8 col-xl-7">

        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="<?= url('/colegios') ?>" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h2 class="fw-bold mb-0">
                <i class="bi bi-building me-2 text-primary"></i>Registrar Colegio
            </h2>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 p-md-5">
                <form method="POST" action="<?= url('/colegios') ?>" novalidate>
                    <?= csrfField() ?>

                    <div class="row g-3">
                        <div class="col-12">
                            <label for="nombreinstitucion" class="form-label fw-semibold">
                                Nombre de la Institución <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="nombreinstitucion" name="nombreinstitucion"
                                   class="form-control" placeholder="Ej: Colegio Nacional Guayaquil"
                                   required maxlength="100">
                        </div>

                        <div class="col-md-6">
                            <label for="rector" class="form-label fw-semibold">
                                Rector <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="rector" name="rector"
                                   class="form-control" placeholder="Nombre completo del rector"
                                   required maxlength="100">
                        </div>

                        <div class="col-md-6">
                            <label for="telefono" class="form-label fw-semibold">
                                Teléfono <span class="text-danger">*</span>
                            </label>
                            <input type="tel" id="telefono" name="telefono"
                                   class="form-control" placeholder="04-XXXXXXX"
                                   required maxlength="20">
                        </div>

                        <div class="col-12">
                            <label for="direccion" class="form-label fw-semibold">
                                Dirección <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="direccion" name="direccion"
                                   class="form-control" placeholder="Calle principal y secundaria"
                                   required maxlength="200">
                        </div>

                        <div class="col-md-6">
                            <label for="distrito" class="form-label fw-semibold">
                                Distrito <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="distrito" name="distrito"
                                   class="form-control" placeholder="Ej: Distrito 09D01"
                                   required maxlength="50">
                        </div>

                        <div class="col-md-6">
                            <label for="idregistrocnel" class="form-label fw-semibold">
                                Registro CNEL <span class="text-muted fw-normal">(opcional)</span>
                            </label>
                            <select id="idregistrocnel" name="idregistrocnel" class="form-select">
                                <option value="">— Sin asignar —</option>
                                <?php foreach ($cnelOptions as $cnel): ?>
                                    <option value="<?= e($cnel['idregistrocnel']) ?>">
                                        <?= e($cnel['nombreinstitucion']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-save me-2"></i>Guardar Colegio
                        </button>
                        <a href="<?= url('/colegios') ?>" class="btn btn-outline-secondary px-4">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
