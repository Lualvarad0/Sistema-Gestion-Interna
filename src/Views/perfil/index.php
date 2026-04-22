<?php
use App\Models\User;

$rol      = $currentUser['rol'] ?? 'operador';
$rolLabel = User::getRoleLabel($rol);
$rolColor = User::getRoleColor($rol);
$isAdmin  = ($rol === 'administrador');

// Iniciales del avatar
$nombre   = trim($userRecord['nombre_completo'] ?? '');
$parts    = $nombre !== '' ? explode(' ', $nombre) : [$currentUser['username'] ?? 'U'];
$parts    = array_values(array_filter($parts));
$initials = implode('', array_map(fn($p) => mb_strtoupper(mb_substr($p, 0, 1)), array_slice($parts, 0, 2)));
if ($initials === '') $initials = mb_strtoupper(mb_substr($currentUser['username'] ?? 'U', 0, 1));

$avatarColorMap = [
    'blue'   => ['hex' => '#1a3a6e', 'label' => 'Azul institucional'],
    'green'  => ['hex' => '#059669', 'label' => 'Verde'],
    'amber'  => ['hex' => '#d97706', 'label' => 'Ámbar'],
    'red'    => ['hex' => '#dc2626', 'label' => 'Rojo'],
    'purple' => ['hex' => '#7c3aed', 'label' => 'Morado'],
    'cyan'   => ['hex' => '#0284c7', 'label' => 'Azul cielo'],
];

$currentAvatarColor = $userRecord['avatar_color'] ?? 'blue';
if (!isset($avatarColorMap[$currentAvatarColor])) $currentAvatarColor = 'blue';
$avatarBg = $avatarColorMap[$currentAvatarColor]['hex'];

$settings  ??= [];
$activeTab ??= 'perfil';
?>

<style>
/* Estilos de página de perfil (inline para garantizar carga) */
.ph-wrap { background:#eef0f7; margin:-1.5rem -12px 0; padding:1.5rem 12px 2rem; }
.ph-hero {
    background:linear-gradient(135deg,#0f2a5c 0%,#1a3a6e 40%,#1d4d9b 72%,#2563eb 100%);
    border-radius:1rem; padding:2rem 2.25rem; color:#fff; position:relative;
    overflow:hidden; box-shadow:0 12px 40px rgba(26,58,110,.35);
    margin-bottom:0;
}
.ph-shape { position:absolute; border-radius:50%; pointer-events:none; }
.ph-s1 { width:300px;height:300px;top:-90px;right:-70px; background:rgba(255,255,255,.07); }
.ph-s2 { width:180px;height:180px;bottom:-60px;right:150px; background:rgba(255,255,255,.04); }
.ph-s3 { width:90px; height:90px; top:10px; right:250px; background:rgba(255,255,255,.06); }
.ph-content { position:relative;z-index:2;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1.25rem; }
.ph-avatar {
    width:80px;height:80px;border-radius:50%;display:flex;align-items:center;justify-content:center;
    font-size:1.85rem;font-weight:700;color:#fff;flex-shrink:0;
    border:3px solid rgba(255,255,255,.38);box-shadow:0 4px 18px rgba(0,0,0,.25);
    transition:background .3s;
}
.ph-name  { font-size:1.5rem;font-weight:700;line-height:1.2; }
.ph-badge {
    display:inline-flex;align-items:center;padding:.2rem .7rem;border-radius:20px;
    background:rgba(255,255,255,.18);border:1px solid rgba(255,255,255,.28);
    font-size:.7rem;font-weight:600;text-transform:uppercase;letter-spacing:.04em;color:#fff;
}
.ph-meta  { font-size:.8rem;opacity:.72; }

/* Tabs del perfil */
.ptab-nav {
    display:flex;border-bottom:2px solid #e2e8f0;
    gap:.1rem;overflow-x:auto;flex-wrap:nowrap;
    margin-bottom:1.5rem;
}
.ptab-btn {
    display:inline-flex;align-items:center;gap:.45rem;
    padding:.65rem 1.1rem;border:none;background:transparent;
    color:#64748b;font-weight:500;font-size:.875rem;white-space:nowrap;
    border-bottom:2px solid transparent;margin-bottom:-2px;cursor:pointer;
    border-radius:0;transition:color .15s,border-color .15s;
}
.ptab-btn:hover { color:#1a3a6e;border-bottom-color:#bfdbfe; }
.ptab-btn.active { color:#1a3a6e;border-bottom-color:#1a3a6e;font-weight:600; }

/* Cards con acento lateral */
.card-accent { border-left:4px solid #1a3a6e !important; }
.card-accent-green  { border-left:4px solid #059669 !important; }
.card-accent-amber  { border-left:4px solid #d97706 !important; }
.card-accent-danger { border-left:4px solid #dc2626 !important; }

/* Cabecera coloreada de card */
.card-hdr {
    display:flex;align-items:center;gap:.65rem;
    padding:1rem 1.25rem;border-bottom:1px solid #f0f2f8;
    font-weight:600;font-size:.875rem;color:#1e293b;
}
.card-hdr-icon {
    width:34px;height:34px;border-radius:.5rem;
    display:flex;align-items:center;justify-content:center;
    font-size:1rem;flex-shrink:0;
}
.card-body-p { padding:1.25rem 1.25rem 1.5rem; }

/* Avatar preview */
.av-preview {
    width:90px;height:90px;border-radius:50%;
    display:flex;align-items:center;justify-content:center;
    font-size:2rem;font-weight:700;color:#fff;
    box-shadow:0 6px 24px rgba(0,0,0,.18);
    border:4px solid rgba(255,255,255,.85);transition:background .25s;
}
.av-sm {
    width:30px;height:30px;border-radius:50%;
    display:flex;align-items:center;justify-content:center;
    font-size:.7rem;font-weight:700;color:#fff;flex-shrink:0;
}
.color-dot-grid { display:flex;justify-content:center;gap:.55rem;flex-wrap:wrap;margin:.75rem 0; }
.cdot {
    width:30px;height:30px;border-radius:50%;
    border:3px solid transparent;cursor:pointer;outline:none;padding:0;
    transition:transform .15s,box-shadow .15s;
}
.cdot:hover { transform:scale(1.2);box-shadow:0 3px 10px rgba(0,0,0,.3); }
.cdot.sel { border-color:#fff;outline:3px solid rgba(0,0,0,.25);outline-offset:1px;transform:scale(1.12); }

/* Cuenta info */
.acc-stat { display:flex;align-items:center;gap:.7rem; }
.acc-stat-ic {
    width:36px;height:36px;border-radius:.5rem;
    display:flex;align-items:center;justify-content:center;font-size:.95rem;flex-shrink:0;
}
.acc-lbl { font-size:.67rem;color:#94a3b8;text-transform:uppercase;letter-spacing:.04em; }
.acc-val { font-size:.875rem;font-weight:600;color:#1e293b; }

/* Fortaleza contraseña */
.pw-meter { display:flex;align-items:center;gap:.65rem;margin-top:.5rem; }
.pw-bar   { flex:1;height:5px;background:#e2e8f0;border-radius:20px;overflow:hidden; }
.pw-fill  { height:100%;width:0;border-radius:20px;transition:width .3s,background .3s; }
.pw-lbl   { font-size:.7rem;font-weight:600;min-width:72px; }

/* Security tips */
.sec-tips { list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:.5rem; }
.sec-tip  { display:flex;align-items:center;gap:.55rem;font-size:.84rem;color:#475569; }

/* Sesión */
.sess-dot {
    width:10px;height:10px;border-radius:50%;background:#10b981;flex-shrink:0;
    animation:pd 2s ease-in-out infinite;
}
@keyframes pd {
    0%,100% { box-shadow:0 0 0 0 rgba(16,185,129,.45); }
    50%      { box-shadow:0 0 0 6px rgba(16,185,129,0); }
}

/* Preferencias */
.pref-row {
    display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;
    padding:.8rem 0;border-bottom:1px solid #f0f2f8;
}
.pref-row:last-child { border-bottom:0;padding-bottom:0; }
.pref-ttl { font-size:.875rem;font-weight:600;color:#1e293b; }
.pref-dsc { font-size:.77rem;color:#64748b;margin-top:.1rem;line-height:1.4; }

/* Sistema */
.sys-row {
    display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;
    padding:.9rem 0;border-bottom:1px solid #f0f2f8;
}
.sys-row:last-child { border-bottom:0;padding-bottom:0; }
.sys-row.warn { background:rgba(251,191,36,.08);border-radius:.5rem;padding:.85rem;margin:0 -.5rem;border-bottom:0; }
.sys-ttl { display:flex;align-items:center;font-size:.875rem;font-weight:600;color:#1e293b; }
.sys-dsc { font-size:.77rem;color:#64748b;margin-top:.25rem;line-height:1.4; }

/* Info del sistema */
.sys-grid { display:grid;grid-template-columns:1fr 1fr;gap:.6rem; }
.sys-item { background:#f8fafc;border-radius:.5rem;padding:.6rem .75rem; }
.sys-key  { font-size:.67rem;color:#94a3b8;text-transform:uppercase;letter-spacing:.04em; }
.sys-val  { font-size:.82rem;font-weight:600;color:#1e293b;margin-top:.1rem; }

/* Efectos preferencias */
body.pref-compact .table td,
body.pref-compact .table th { padding:.3rem .7rem !important;font-size:.82rem; }
body.pref-no-motion *,
body.pref-no-motion *::before,
body.pref-no-motion *::after { animation-duration:.01ms !important;transition-duration:.01ms !important; }
body.pref-hide-badge .badge { opacity:0 !important;pointer-events:none; }

@media(max-width:768px){
    .ph-hero { padding:1.5rem 1.25rem; }
    .ph-name { font-size:1.2rem; }
    .ph-avatar { width:58px;height:58px;font-size:1.35rem; }
    .ptab-btn i { margin:0!important; }
    .ptab-btn span { display:none; }
    .sys-grid { grid-template-columns:1fr; }
}
</style>

<!-- Zona de fondo con degradado suave -->
<div class="ph-wrap">

    <!-- Encabezado de página -->
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="<?= url('/') ?>" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h2 class="fw-bold mb-0 text-dark">
                <i class="bi bi-person-circle me-2" style="color:#1a3a6e"></i>Mi Perfil
            </h2>
            <p class="text-muted small mb-0">Gestiona tu información personal y la configuración de la plataforma</p>
        </div>
    </div>

    <!-- Hero banner del perfil -->
    <div class="ph-hero mb-4" style="background:linear-gradient(135deg,#0f2a5c 0%,#1a3a6e 40%,#1d4d9b 72%,#2563eb 100%);border-radius:1rem;padding:2rem 2.25rem;color:#fff;position:relative;overflow:hidden;box-shadow:0 12px 40px rgba(26,58,110,.35);">
        <div class="ph-shape ph-s1"></div>
        <div class="ph-shape ph-s2"></div>
        <div class="ph-shape ph-s3"></div>
        <div class="ph-content">
            <div class="d-flex align-items-center gap-4">
                <div class="ph-avatar" id="ph-avatar-hero" style="background:<?= e($avatarBg) ?>">
                    <?= e($initials) ?>
                </div>
                <div>
                    <div class="ph-name"><?= e($userRecord['nombre_completo'] ?? $currentUser['username'] ?? 'Usuario') ?></div>
                    <div class="d-flex align-items-center gap-2 mt-2 flex-wrap">
                        <span class="ph-badge"><i class="bi bi-shield-check me-1"></i><?= e($rolLabel) ?></span>
                        <?php if (!empty($userRecord['cargo'])): ?>
                        <span class="ph-badge" style="background:rgba(255,255,255,.12)">
                            <i class="bi bi-briefcase me-1"></i><?= e($userRecord['cargo']) ?>
                        </span>
                        <?php endif; ?>
                        <?php if ($isAdmin): ?>
                        <span class="ph-badge" style="background:rgba(220,38,38,.28);border-color:rgba(220,38,38,.45)">
                            <i class="bi bi-star-fill me-1"></i>Admin
                        </span>
                        <?php endif; ?>
                    </div>
                    <div class="d-flex gap-3 mt-2 flex-wrap">
                        <?php if (!empty($userRecord['email'])): ?>
                        <span class="ph-meta"><i class="bi bi-envelope me-1"></i><?= e($userRecord['email']) ?></span>
                        <?php endif; ?>
                        <?php if (!empty($userRecord['telefono'])): ?>
                        <span class="ph-meta"><i class="bi bi-telephone me-1"></i><?= e($userRecord['telefono']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="text-end d-none d-md-block">
                <div class="ph-meta" style="text-transform:uppercase;letter-spacing:.05em;font-size:.7rem">Miembro desde</div>
                <div class="fw-bold fs-4 mt-1 text-white">
                    <?= isset($userRecord['created_at']) ? date('M Y', strtotime($userRecord['created_at'])) : '—' ?>
                </div>
                <div class="ph-meta mt-1"><i class="bi bi-person-badge me-1"></i><?= e($currentUser['username'] ?? '') ?></div>
            </div>
        </div>
    </div>

    <!-- Navegación de pestañas -->
    <div class="ptab-nav" role="tablist">
        <button class="ptab-btn <?= $activeTab === 'perfil' ? 'active' : '' ?>"
                data-tab="tab-perfil" role="tab">
            <i class="bi bi-person-fill"></i><span>Perfil</span>
        </button>
        <button class="ptab-btn <?= $activeTab === 'seguridad' ? 'active' : '' ?>"
                data-tab="tab-seguridad" role="tab">
            <i class="bi bi-shield-lock-fill"></i><span>Seguridad</span>
        </button>
        <button class="ptab-btn <?= $activeTab === 'preferencias' ? 'active' : '' ?>"
                data-tab="tab-preferencias" role="tab">
            <i class="bi bi-sliders"></i><span>Preferencias</span>
        </button>
        <?php if ($isAdmin): ?>
        <button class="ptab-btn <?= $activeTab === 'sistema' ? 'active' : '' ?>"
                data-tab="tab-sistema" role="tab">
            <i class="bi bi-gear-fill"></i><span>Sistema</span>
            <span class="badge bg-danger ms-1" style="font-size:.58rem;vertical-align:middle">Admin</span>
        </button>
        <?php endif; ?>
    </div>

    <!-- Contenido de pestañas -->
    <div id="ptab-content">

        <!-- ══════════════════════════════════
             TAB: PERFIL
        ══════════════════════════════════ -->
        <div id="tab-perfil" class="ptab-pane <?= $activeTab === 'perfil' ? '' : 'd-none' ?>">
            <form method="POST" action="<?= url('/perfil/profile') ?>" novalidate>
                <?= csrfField() ?>
                <input type="hidden" name="avatar_color" id="av_color_input" value="<?= e($currentAvatarColor) ?>">

                <div class="row g-4">
                    <!-- Datos personales -->
                    <div class="col-lg-8">
                        <div class="card border-0 shadow-sm card-accent h-100">
                            <div class="card-hdr">
                                <div class="card-hdr-icon bg-primary bg-opacity-10 text-primary">
                                    <i class="bi bi-person-lines-fill"></i>
                                </div>
                                <div>
                                    <div>Información Personal</div>
                                    <div class="fw-normal text-muted" style="font-size:.75rem">Edita tus datos de contacto y cargo</div>
                                </div>
                            </div>
                            <div class="card-body-p">
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <label class="form-label fw-semibold small">Nombre Completo</label>
                                        <input type="text" name="nombre_completo" class="form-control"
                                               maxlength="100"
                                               value="<?= e($userRecord['nombre_completo'] ?? '') ?>"
                                               placeholder="Nombre y apellidos">
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label fw-semibold small">Cargo / Posición</label>
                                        <input type="text" name="cargo" class="form-control"
                                               maxlength="100"
                                               value="<?= e($userRecord['cargo'] ?? '') ?>"
                                               placeholder="Ej: Jefe de Departamento">
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label fw-semibold small">Correo Electrónico</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-envelope text-muted"></i></span>
                                            <input type="email" name="email" class="form-control"
                                                   maxlength="100"
                                                   value="<?= e($userRecord['email'] ?? '') ?>"
                                                   placeholder="correo@ejemplo.com">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label fw-semibold small">Teléfono</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-telephone text-muted"></i></span>
                                            <input type="tel" name="telefono" class="form-control"
                                                   maxlength="20"
                                                   value="<?= e($userRecord['telefono'] ?? '') ?>"
                                                   placeholder="+593 99 000 0000">
                                        </div>
                                    </div>
                                </div>

                                <!-- Datos de cuenta (solo lectura) -->
                                <div class="mt-4 pt-3 border-top">
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <i class="bi bi-info-circle text-muted"></i>
                                        <span class="text-muted small fw-semibold text-uppercase" style="letter-spacing:.06em;font-size:.67rem">Información de cuenta</span>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-sm-4">
                                            <div class="acc-stat">
                                                <div class="acc-stat-ic bg-primary bg-opacity-10 text-primary">
                                                    <i class="bi bi-person-badge"></i>
                                                </div>
                                                <div>
                                                    <div class="acc-lbl">Usuario</div>
                                                    <div class="acc-val"><?= e($currentUser['username'] ?? '') ?></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="acc-stat">
                                                <div class="acc-stat-ic bg-<?= e($rolColor) ?> bg-opacity-10 text-<?= e($rolColor) ?>">
                                                    <i class="bi bi-shield-check"></i>
                                                </div>
                                                <div>
                                                    <div class="acc-lbl">Rol</div>
                                                    <div class="acc-val"><?= e($rolLabel) ?></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="acc-stat">
                                                <div class="acc-stat-ic bg-success bg-opacity-10 text-success">
                                                    <i class="bi bi-calendar-check"></i>
                                                </div>
                                                <div>
                                                    <div class="acc-lbl">Registro</div>
                                                    <div class="acc-val">
                                                        <?= isset($userRecord['created_at'])
                                                            ? date('d/m/Y', strtotime($userRecord['created_at']))
                                                            : '—' ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="bi bi-save me-2"></i>Guardar Cambios
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Selector de avatar -->
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-hdr">
                                <div class="card-hdr-icon bg-warning bg-opacity-10 text-warning">
                                    <i class="bi bi-palette-fill"></i>
                                </div>
                                <div>
                                    <div>Color de Avatar</div>
                                    <div class="fw-normal text-muted" style="font-size:.75rem">Personaliza tu identificador</div>
                                </div>
                            </div>
                            <div class="card-body-p text-center">
                                <!-- Preview grande -->
                                <div class="d-flex justify-content-center mb-2">
                                    <div class="av-preview" id="av-preview-big"
                                         style="background:<?= e($avatarBg) ?>">
                                        <?= e($initials) ?>
                                    </div>
                                </div>
                                <p class="text-muted small mb-2">Selecciona un color</p>

                                <!-- Puntos de color -->
                                <div class="color-dot-grid">
                                    <?php foreach ($avatarColorMap as $ck => $cd): ?>
                                    <button type="button"
                                            class="cdot <?= $ck === $currentAvatarColor ? 'sel' : '' ?>"
                                            data-color="<?= e($ck) ?>" data-hex="<?= e($cd['hex']) ?>"
                                            title="<?= e($cd['label']) ?>"
                                            style="background:<?= e($cd['hex']) ?>">
                                    </button>
                                    <?php endforeach; ?>
                                </div>

                                <!-- Preview navbar -->
                                <div class="mt-3 p-3 rounded-3 text-start"
                                     style="background:#f1f5f9;border:1px solid #e2e8f0">
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <div class="av-sm" id="av-preview-sm"
                                             style="background:<?= e($avatarBg) ?>">
                                            <?= e($initials) ?>
                                        </div>
                                        <div class="flex-grow-1 overflow-hidden">
                                            <div class="fw-semibold text-truncate" style="font-size:.82rem">
                                                <?= e($nombre ?: ($currentUser['username'] ?? 'Usuario')) ?>
                                            </div>
                                            <span class="badge bg-<?= e($rolColor) ?>" style="font-size:.6rem">
                                                <?= e($rolLabel) ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="text-muted" style="font-size:.7rem">
                                        <i class="bi bi-eye me-1"></i>Vista previa en el menú
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- /row -->
            </form>
        </div>

        <!-- ══════════════════════════════════
             TAB: SEGURIDAD
        ══════════════════════════════════ -->
        <div id="tab-seguridad" class="ptab-pane <?= $activeTab === 'seguridad' ? '' : 'd-none' ?>">
            <div class="row g-4">
                <!-- Formulario -->
                <div class="col-lg-7">
                    <div class="card border-0 shadow-sm card-accent-danger">
                        <div class="card-hdr">
                            <div class="card-hdr-icon bg-danger bg-opacity-10 text-danger">
                                <i class="bi bi-lock-fill"></i>
                            </div>
                            <div>
                                <div>Cambiar Contraseña</div>
                                <div class="fw-normal text-muted" style="font-size:.75rem">Usa al menos 6 caracteres</div>
                            </div>
                        </div>
                        <div class="card-body-p">
                            <form method="POST" action="<?= url('/perfil/password') ?>" novalidate>
                                <?= csrfField() ?>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold small">
                                        Contraseña Actual <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" id="pw_actual" name="password_actual"
                                               class="form-control" required autocomplete="current-password">
                                        <button class="btn btn-outline-secondary tgl-pw" type="button"
                                                data-t="pw_actual" tabindex="-1">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold small">
                                        Nueva Contraseña <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" id="pw_nuevo" name="password_nuevo"
                                               class="form-control" required autocomplete="new-password" minlength="6"
                                               oninput="pwStrength(this.value)">
                                        <button class="btn btn-outline-secondary tgl-pw" type="button"
                                                data-t="pw_nuevo" tabindex="-1">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                    <div id="pw-meter-wrap" style="display:none" class="pw-meter mt-2">
                                        <div class="pw-bar"><div class="pw-fill" id="pw-fill"></div></div>
                                        <span class="pw-lbl" id="pw-lbl"></span>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-semibold small">
                                        Confirmar Contraseña <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" id="pw_confirm" name="password_confirm"
                                               class="form-control" required autocomplete="new-password"
                                               oninput="pwMatch(this.value)">
                                        <button class="btn btn-outline-secondary tgl-pw" type="button"
                                                data-t="pw_confirm" tabindex="-1">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                    <div id="pw-match-msg" style="display:none;font-size:.8rem;margin-top:.3rem"></div>
                                </div>

                                <button type="submit" class="btn btn-danger px-4">
                                    <i class="bi bi-lock me-2"></i>Actualizar Contraseña
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Panel lateral -->
                <div class="col-lg-5 d-flex flex-column gap-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-hdr">
                            <div class="card-hdr-icon bg-success bg-opacity-10 text-success">
                                <i class="bi bi-patch-check-fill"></i>
                            </div>
                            <div>Consejos de Seguridad</div>
                        </div>
                        <div class="card-body-p">
                            <ul class="sec-tips">
                                <li class="sec-tip"><i class="bi bi-check-circle-fill text-success"></i> Mínimo 6 caracteres</li>
                                <li class="sec-tip"><i class="bi bi-check-circle-fill text-success"></i> Combina mayúsculas y minúsculas</li>
                                <li class="sec-tip"><i class="bi bi-check-circle-fill text-success"></i> Agrega números o símbolos</li>
                                <li class="sec-tip"><i class="bi bi-x-circle-fill text-danger"></i> No uses tu nombre de usuario</li>
                                <li class="sec-tip"><i class="bi bi-x-circle-fill text-danger"></i> No repitas contraseñas anteriores</li>
                            </ul>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm card-accent-green">
                        <div class="card-hdr">
                            <div class="card-hdr-icon bg-success bg-opacity-10 text-success">
                                <i class="bi bi-wifi"></i>
                            </div>
                            <div>Sesión Activa</div>
                        </div>
                        <div class="card-body-p">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="sess-dot"></div>
                                <div>
                                    <div class="fw-semibold small">Conectado ahora</div>
                                    <div class="text-muted" style="font-size:.78rem">
                                        <?= e($currentUser['username'] ?? '') ?> · <?= e($rolLabel) ?>
                                    </div>
                                </div>
                            </div>
                            <div class="text-muted small d-flex align-items-center gap-2">
                                <i class="bi bi-calendar-event"></i>
                                <?= date('d/m/Y H:i') ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ══════════════════════════════════
             TAB: PREFERENCIAS
        ══════════════════════════════════ -->
        <div id="tab-preferencias" class="ptab-pane <?= $activeTab === 'preferencias' ? '' : 'd-none' ?>">
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm card-accent">
                        <div class="card-hdr">
                            <div class="card-hdr-icon bg-primary bg-opacity-10 text-primary">
                                <i class="bi bi-display"></i>
                            </div>
                            <div>
                                <div>Visualización</div>
                                <div class="fw-normal text-muted" style="font-size:.75rem">Ajusta cómo se muestra la interfaz</div>
                            </div>
                        </div>
                        <div class="card-body-p">
                            <div class="pref-row">
                                <div>
                                    <div class="pref-ttl">Tablas compactas</div>
                                    <div class="pref-dsc">Reduce el espaciado en las tablas de datos</div>
                                </div>
                                <div class="form-check form-switch ms-3 flex-shrink-0">
                                    <input class="form-check-input pref-tog" type="checkbox"
                                           id="pref_compact" role="switch" data-pref="compact_tables">
                                </div>
                            </div>
                            <div class="pref-row">
                                <div>
                                    <div class="pref-ttl">Animaciones reducidas</div>
                                    <div class="pref-dsc">Desactiva efectos de transición y movimiento</div>
                                </div>
                                <div class="form-check form-switch ms-3 flex-shrink-0">
                                    <input class="form-check-input pref-tog" type="checkbox"
                                           id="pref_reduced" role="switch" data-pref="reduced_motion">
                                </div>
                            </div>
                            <div class="pref-row">
                                <div>
                                    <div class="pref-ttl">Modo enfocado</div>
                                    <div class="pref-dsc">Oculta decoraciones secundarias al trabajar</div>
                                </div>
                                <div class="form-check form-switch ms-3 flex-shrink-0">
                                    <input class="form-check-input pref-tog" type="checkbox"
                                           id="pref_focused" role="switch" data-pref="focused_mode">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 d-flex flex-column gap-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-hdr">
                            <div class="card-hdr-icon" style="background:rgba(99,102,241,.12);color:#6366f1">
                                <i class="bi bi-bell-fill"></i>
                            </div>
                            <div>
                                <div>Notificaciones</div>
                                <div class="fw-normal text-muted" style="font-size:.75rem">Cómo aparecen las alertas</div>
                            </div>
                        </div>
                        <div class="card-body-p">
                            <div class="pref-row">
                                <div>
                                    <div class="pref-ttl">Badge de alertas</div>
                                    <div class="pref-dsc">Muestra el contador en el ícono de campana</div>
                                </div>
                                <div class="form-check form-switch ms-3 flex-shrink-0">
                                    <input class="form-check-input pref-tog" type="checkbox"
                                           id="pref_badge" role="switch" data-pref="notif_badge">
                                </div>
                            </div>
                            <div class="pref-row">
                                <div>
                                    <div class="pref-ttl">Animación de alerta</div>
                                    <div class="pref-dsc">Pulsa el ícono cuando hay notificaciones nuevas</div>
                                </div>
                                <div class="form-check form-switch ms-3 flex-shrink-0">
                                    <input class="form-check-input pref-tog" type="checkbox"
                                           id="pref_blink" role="switch" data-pref="notif_blink">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm">
                        <div class="card-body-p">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="card-hdr-icon bg-secondary bg-opacity-10 text-secondary">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold small">Restablecer preferencias</div>
                                    <div class="text-muted" style="font-size:.77rem">Vuelve a los valores predeterminados</div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="btn-reset">
                                <i class="bi bi-arrow-counterclockwise me-1"></i>Restablecer todo
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="alert alert-info border-0 mt-4 d-flex align-items-start gap-2" role="alert">
                <i class="bi bi-info-circle-fill mt-1 flex-shrink-0"></i>
                <span class="small">Las preferencias se guardan automáticamente en tu navegador. No necesitan guardarse manualmente y se aplican de inmediato.</span>
            </div>
        </div>

        <!-- ══════════════════════════════════
             TAB: SISTEMA (solo admin)
        ══════════════════════════════════ -->
        <?php if ($isAdmin): ?>
        <div id="tab-sistema" class="ptab-pane <?= $activeTab === 'sistema' ? '' : 'd-none' ?>">
            <form method="POST" action="<?= url('/perfil/settings') ?>" novalidate>
                <?= csrfField() ?>

                <div class="row g-4">
                    <!-- Configuración app -->
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm card-accent">
                            <div class="card-hdr">
                                <div class="card-hdr-icon bg-primary bg-opacity-10 text-primary">
                                    <i class="bi bi-app-indicator"></i>
                                </div>
                                <div>
                                    <div>Configuración de la Aplicación</div>
                                    <div class="fw-normal text-muted" style="font-size:.75rem">Parámetros generales del sistema</div>
                                </div>
                            </div>
                            <div class="card-body-p">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold small">Nombre del Sistema</label>
                                    <input type="text" name="app_name" class="form-control" maxlength="100"
                                           value="<?= e($settings['app_name'] ?? 'Gobernación del Guayas') ?>"
                                           placeholder="Nombre de la plataforma">
                                    <div class="form-text">Aparece en la barra de navegación y título del navegador.</div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold small">Duración de Sesión</label>
                                    <div class="input-group">
                                        <input type="number" name="session_lifetime" class="form-control"
                                               min="300" max="86400" step="300"
                                               value="<?= e($settings['session_lifetime'] ?? 7200) ?>"
                                               id="sess_input" oninput="updSess(this.value)">
                                        <span class="input-group-text">seg</span>
                                    </div>
                                    <div class="form-text" id="sess_label">
                                        <?php
                                            $s = (int)($settings['session_lifetime'] ?? 7200);
                                            echo 'Equivale a: '.intdiv($s,3600).'h '.intdiv($s%3600,60).'m · Rango: 5 min a 24 h';
                                        ?>
                                    </div>
                                </div>

                                <div class="mb-0">
                                    <label class="form-label fw-semibold small">Registros por Página</label>
                                    <select name="registros_por_pagina" class="form-select">
                                        <?php foreach ([10,15,25,50,100] as $n): ?>
                                        <option value="<?= $n ?>"
                                            <?= (int)($settings['registros_por_pagina'] ?? 15) === $n ? 'selected' : '' ?>>
                                            <?= $n ?> registros por página
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="form-text">Número predeterminado de filas en todas las tablas.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Funcionalidades + info servidor -->
                    <div class="col-lg-6 d-flex flex-column gap-4">
                        <div class="card border-0 shadow-sm card-accent-amber">
                            <div class="card-hdr">
                                <div class="card-hdr-icon bg-warning bg-opacity-10 text-warning">
                                    <i class="bi bi-toggles"></i>
                                </div>
                                <div>
                                    <div>Funcionalidades del Sistema</div>
                                    <div class="fw-normal text-muted" style="font-size:.75rem">Activa o desactiva módulos globales</div>
                                </div>
                            </div>
                            <div class="card-body-p">
                                <div class="sys-row <?= ($settings['maintenance_mode'] ?? false) ? 'warn' : '' ?>">
                                    <div>
                                        <div class="sys-ttl">
                                            <i class="bi bi-cone-striped text-warning me-2"></i>Modo Mantenimiento
                                        </div>
                                        <div class="sys-dsc">Bloquea el acceso a usuarios no administradores mientras realizas cambios en el sistema.</div>
                                    </div>
                                    <div class="form-check form-switch ms-3 flex-shrink-0">
                                        <input class="form-check-input" type="checkbox"
                                               name="maintenance_mode" role="switch"
                                               <?= ($settings['maintenance_mode'] ?? false) ? 'checked' : '' ?>>
                                    </div>
                                </div>
                                <div class="sys-row">
                                    <div>
                                        <div class="sys-ttl">
                                            <i class="bi bi-bell text-primary me-2"></i>Sistema de Notificaciones
                                        </div>
                                        <div class="sys-dsc">Habilita o deshabilita el módulo de notificaciones para todos los usuarios de la plataforma.</div>
                                    </div>
                                    <div class="form-check form-switch ms-3 flex-shrink-0">
                                        <input class="form-check-input" type="checkbox"
                                               name="notificaciones_activas" role="switch"
                                               <?= ($settings['notificaciones_activas'] ?? true) ? 'checked' : '' ?>>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm">
                            <div class="card-hdr">
                                <div class="card-hdr-icon" style="background:rgba(15,23,42,.08);color:#1e293b">
                                    <i class="bi bi-cpu-fill"></i>
                                </div>
                                <div>Estado del Servidor</div>
                            </div>
                            <div class="card-body-p">
                                <div class="sys-grid">
                                    <div class="sys-item">
                                        <div class="sys-key">PHP</div>
                                        <div class="sys-val"><?= PHP_VERSION ?></div>
                                    </div>
                                    <div class="sys-item">
                                        <div class="sys-key">Servidor</div>
                                        <div class="sys-val" style="font-size:.75rem"><?= e(explode('/', $_SERVER['SERVER_SOFTWARE'] ?? 'N/A')[0]) ?></div>
                                    </div>
                                    <div class="sys-item">
                                        <div class="sys-key">Memoria límite</div>
                                        <div class="sys-val"><?= ini_get('memory_limit') ?></div>
                                    </div>
                                    <div class="sys-item">
                                        <div class="sys-key">Zona horaria</div>
                                        <div class="sys-val" style="font-size:.72rem"><?= date_default_timezone_get() ?></div>
                                    </div>
                                    <div class="sys-item">
                                        <div class="sys-key">Fecha del servidor</div>
                                        <div class="sys-val"><?= date('d/m/Y H:i') ?></div>
                                    </div>
                                    <div class="sys-item">
                                        <div class="sys-key">RAM usada</div>
                                        <div class="sys-val"><?= round(memory_get_usage(true)/1024/1024,1) ?> MB</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- /row -->

                <div class="mt-4 d-flex align-items-center gap-3 flex-wrap">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-save me-2"></i>Guardar Configuración
                    </button>
                    <span class="text-muted small">
                        <i class="bi bi-shield-lock me-1"></i>Solo administradores pueden modificar esta configuración
                    </span>
                </div>
            </form>
        </div>
        <?php endif; ?>

    </div><!-- /tab-content -->

</div><!-- /ph-wrap -->

<script>
(function(){
'use strict';

// ── Tabs manuales ───────────────────────────────────────────────────────────
const btns  = document.querySelectorAll('.ptab-btn');
const panes = document.querySelectorAll('.ptab-pane');

btns.forEach(btn => {
    btn.addEventListener('click', function(){
        const target = this.dataset.tab;
        btns.forEach(b  => b.classList.remove('active'));
        panes.forEach(p => p.classList.add('d-none'));
        this.classList.add('active');
        const pane = document.getElementById(target);
        if(pane) pane.classList.remove('d-none');
        const url = new URL(window.location);
        url.searchParams.set('tab', target.replace('tab-',''));
        history.replaceState(null,'',url);
    });
});

// ── Selector de color de avatar ─────────────────────────────────────────────
document.querySelectorAll('.cdot').forEach(dot => {
    dot.addEventListener('click', function(){
        const hex = this.dataset.hex, color = this.dataset.color;
        document.getElementById('av_color_input').value = color;
        ['ph-avatar-hero','av-preview-big','av-preview-sm'].forEach(id => {
            const el = document.getElementById(id);
            if(el) el.style.background = hex;
        });
        document.querySelectorAll('.cdot').forEach(d => d.classList.remove('sel'));
        this.classList.add('sel');
    });
});

// ── Toggle visibilidad contraseña ───────────────────────────────────────────
document.querySelectorAll('.tgl-pw').forEach(btn => {
    btn.addEventListener('click', function(){
        const inp  = document.getElementById(this.dataset.t);
        const icon = this.querySelector('i');
        if(!inp) return;
        inp.type = inp.type === 'password' ? 'text' : 'password';
        icon.className = inp.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
    });
});

// ── Medidor de fortaleza ────────────────────────────────────────────────────
window.pwStrength = function(pw){
    const fill = document.getElementById('pw-fill');
    const lbl  = document.getElementById('pw-lbl');
    const wrap = document.getElementById('pw-meter-wrap');
    if(!fill) return;
    wrap.style.display = pw.length ? 'flex' : 'none';
    let s = 0;
    if(pw.length >= 6)           s++;
    if(pw.length >= 10)          s++;
    if(/[A-Z]/.test(pw))         s++;
    if(/[0-9]/.test(pw))         s++;
    if(/[^A-Za-z0-9]/.test(pw)) s++;
    const lvls = [
        null,
        {pct:'20%',col:'#ef4444',txt:'Muy débil'},
        {pct:'40%',col:'#f97316',txt:'Débil'},
        {pct:'60%',col:'#eab308',txt:'Aceptable'},
        {pct:'80%',col:'#3b82f6',txt:'Fuerte'},
        {pct:'100%',col:'#10b981',txt:'Muy fuerte'},
    ];
    const l = lvls[Math.min(s,5)] || lvls[1];
    fill.style.width = l.pct; fill.style.background = l.col;
    lbl.textContent = l.txt; lbl.style.color = l.col;
    pwMatch(document.getElementById('pw_confirm')?.value ?? '');
};

window.pwMatch = function(v){
    const msg  = document.getElementById('pw-match-msg');
    const nvo  = document.getElementById('pw_nuevo')?.value ?? '';
    if(!msg || !v){ if(msg) msg.style.display='none'; return; }
    msg.style.display = '';
    msg.innerHTML = v === nvo
        ? '<i class="bi bi-check-circle-fill text-success me-1"></i><span class="text-success">Las contraseñas coinciden</span>'
        : '<i class="bi bi-x-circle-fill text-danger me-1"></i><span class="text-danger">Las contraseñas no coinciden</span>';
};

// ── Etiqueta duración sesión ────────────────────────────────────────────────
window.updSess = function(v){
    const s = parseInt(v,10), lbl = document.getElementById('sess_label');
    if(!lbl || isNaN(s)) return;
    lbl.textContent = 'Equivale a: '+Math.floor(s/3600)+'h '+Math.floor((s%3600)/60)+'m · Rango: 5 min a 24 h';
};

// ── Preferencias (localStorage) ─────────────────────────────────────────────
const PREFS = {
    compact_tables: false,
    reduced_motion: false,
    focused_mode:   false,
    notif_badge:    true,
    notif_blink:    true,
};

function applyPref(k,v){
    const b = document.body;
    if(k==='compact_tables') b.classList.toggle('pref-compact',v);
    if(k==='reduced_motion') b.classList.toggle('pref-no-motion',v);
    if(k==='notif_badge')    b.classList.toggle('pref-hide-badge',!v);
}

function loadPrefs(){
    Object.entries(PREFS).forEach(([k,def])=>{
        const raw = localStorage.getItem('pref_'+k);
        const val = raw !== null ? raw==='true' : def;
        const el  = document.querySelector('[data-pref="'+k+'"]');
        if(el) el.checked = val;
        applyPref(k,val);
    });
}

document.querySelectorAll('.pref-tog').forEach(t=>{
    t.addEventListener('change',function(){
        localStorage.setItem('pref_'+this.dataset.pref, this.checked);
        applyPref(this.dataset.pref, this.checked);
    });
});

document.getElementById('btn-reset')?.addEventListener('click',function(){
    Object.keys(PREFS).forEach(k => localStorage.removeItem('pref_'+k));
    loadPrefs();
    this.innerHTML='<i class="bi bi-check2 me-1"></i>Restablecidas';
    setTimeout(()=>{ this.innerHTML='<i class="bi bi-arrow-counterclockwise me-1"></i>Restablecer todo'; },2000);
});

loadPrefs();

})();
</script>
