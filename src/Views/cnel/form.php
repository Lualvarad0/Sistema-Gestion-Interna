<div class="row justify-content-center">
    <div class="col-lg-9 col-xl-8">

        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="<?= url('/cnel') ?>" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h2 class="fw-bold mb-0">
                <i class="bi bi-lightning-charge me-2 text-warning"></i>Registrar CNEL / Luminarias
            </h2>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 p-md-5">
                <form method="POST" action="<?= url('/cnel') ?>" novalidate>
                    <?= csrfField() ?>

                    <h6 class="text-muted fw-semibold text-uppercase small mb-3 border-bottom pb-2">
                        Datos de la Institución
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-8">
                            <label for="nombreinstitucion" class="form-label fw-semibold">
                                Nombre de la Institución <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="nombreinstitucion" name="nombreinstitucion"
                                   class="form-control" required maxlength="100">
                        </div>
                        <div class="col-md-4">
                            <label for="distrito" class="form-label fw-semibold">Distrito</label>
                            <input type="text" id="distrito" name="distrito"
                                   class="form-control" maxlength="50">
                        </div>
                    </div>

                    <h6 class="text-muted fw-semibold text-uppercase small mb-3 border-bottom pb-2">
                        Datos de Luminarias
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <label for="nuevasluminarias" class="form-label fw-semibold">Nuevas</label>
                            <input type="number" id="nuevasluminarias" name="nuevasluminarias"
                                   class="form-control" value="0" min="0">
                        </div>
                        <div class="col-md-3">
                            <label for="mantenimiento" class="form-label fw-semibold">Mantenimiento</label>
                            <input type="number" id="mantenimiento" name="mantenimiento"
                                   class="form-control" value="0" min="0">
                        </div>
                        <div class="col-md-3">
                            <label for="cantidad" class="form-label fw-semibold">Cantidad Postes</label>
                            <input type="number" id="cantidad" name="cantidad"
                                   class="form-control" value="0" min="0">
                        </div>
                        <div class="col-md-3">
                            <label for="tipo" class="form-label fw-semibold">Tipo</label>
                            <select id="tipo" name="tipo" class="form-select">
                                <option value="">— Seleccionar —</option>
                                <option value="LED">LED</option>
                                <option value="Sodio">Sodio</option>
                                <option value="Mercurio">Mercurio</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="estado" class="form-label fw-semibold">Estado</label>
                            <select id="estado" name="estado" class="form-select">
                                <option value="">— Seleccionar —</option>
                                <option value="Activo">Activo</option>
                                <option value="En proceso">En proceso</option>
                                <option value="Completado">Completado</option>
                                <option value="Pendiente">Pendiente</option>
                            </select>
                        </div>
                    </div>

                    <h6 class="text-muted fw-semibold text-uppercase small mb-3 border-bottom pb-2">
                        Trabajador Responsable
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="codtrabajador" class="form-label fw-semibold">
                                Seleccionar Trabajador
                            </label>
                            <select id="codtrabajador" name="codtrabajador" class="form-select">
                                <option value="">— Sin asignar —</option>
                                <?php foreach ($trabajadores as $t): ?>
                                    <option value="<?= e($t['codtrabajador']) ?>"
                                            data-nombre="<?= e($t['nombretrabajador']) ?>">
                                        <?= e($t['nombretrabajador']) ?> (Cód. <?= e($t['codtrabajador']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-text">
                                Trabajadores registrados en Actas.
                                <a href="<?= url('/actas/nuevo') ?>" target="_blank">Registrar nuevo</a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="nombretrabajador" class="form-label fw-semibold">
                                Nombre del Trabajador
                            </label>
                            <input type="text" id="nombretrabajador" name="nombretrabajador"
                                   class="form-control" maxlength="100"
                                   placeholder="Se completa al seleccionar arriba">
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-warning px-4 fw-semibold">
                            <i class="bi bi-save me-2"></i>Guardar Registro
                        </button>
                        <a href="<?= url('/cnel') ?>" class="btn btn-outline-secondary px-4">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Auto-completar nombre al seleccionar trabajador
    document.getElementById('codtrabajador').addEventListener('change', function () {
        const selected = this.options[this.selectedIndex];
        document.getElementById('nombretrabajador').value = selected.dataset.nombre ?? '';
    });
</script>
