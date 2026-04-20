<?php
$rol      = $currentUser['rol'] ?? 'operador';
$rolLabels = ['administrador' => 'Administrador', 'supervisor' => 'Supervisor', 'operador' => 'Operador'];
$rolLabel  = $rolLabels[$rol] ?? 'Usuario';

$hora    = (int) date('H');
$saludo  = $hora < 12 ? 'Buenos días' : ($hora < 18 ? 'Buenas tardes' : 'Buenas noches');
$nombre  = !empty($currentUser['nombre_completo'])
    ? explode(' ', trim($currentUser['nombre_completo']))[0]
    : $currentUser['username'];

$meses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
$dias  = ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'];
$fechaTexto = $dias[date('w')] . ', ' . date('d') . ' de ' . $meses[(int)date('n')-1] . ' ' . date('Y');

$total = $stats['colegios'] + $stats['cnel'] + $stats['encuentros'] + $stats['actas'];
$maxVal = max(1, $stats['colegios'], $stats['cnel'], $stats['encuentros'], $stats['actas']);
?>
<style>
/* ── DASHBOARD INLINE STYLES ── */

/* Hero */
.db-hero {
    background: linear-gradient(135deg, #0d2347 0%, #1a3a6e 45%, #1e52a0 80%, #2563eb 100%);
    border-radius: 1rem;
    padding: 2rem 2.25rem;
    color: #fff;
    position: relative;
    overflow: hidden;
    box-shadow: 0 8px 32px rgba(26,58,110,.45), 0 2px 8px rgba(0,0,0,.25), inset 0 1px 0 rgba(255,255,255,.15);
    margin-bottom: 1.5rem;
}
.db-hero-row {
    position: relative; z-index: 2;
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 1.25rem;
}
.db-hero .circle {
    position: absolute; border-radius: 50%;
    background: rgba(255,255,255,.06); pointer-events: none;
}
.db-hero .c1 { width:280px; height:280px; top:-90px; right:-60px; }
.db-hero .c2 { width:160px; height:160px; bottom:-55px; right:160px; background:rgba(255,255,255,.04); }
.db-hero .c3 { width: 80px; height: 80px; top:15px;   right:240px; background:rgba(255,255,255,.09); }

.db-avatar {
    width:60px; height:60px; border-radius:50%;
    background:rgba(255,255,255,.18); border:2px solid rgba(255,255,255,.30);
    display:flex; align-items:center; justify-content:center;
    font-size:1.8rem; flex-shrink:0;
    box-shadow:0 4px 16px rgba(0,0,0,.22);
}
.db-saludo  { font-size:.9rem; opacity:.80; margin-bottom:.1rem; }
.db-nombre  { font-size:1.65rem; font-weight:800; line-height:1.1; margin-bottom:.4rem; }
.db-rol-pill {
    display:inline-block; padding:.22rem .85rem;
    background:rgba(255,255,255,.18); border:1px solid rgba(255,255,255,.28);
    border-radius:20px; font-size:.72rem; font-weight:700;
    text-transform:uppercase; letter-spacing:.05em;
}
.db-clock { font-size:2.2rem; font-weight:800; font-variant-numeric:tabular-nums; line-height:1; }
.db-date  { font-size:.78rem; opacity:.72; margin-top:.3rem; }

/* KPI Cards */
.db-kpi {
    border-radius:1rem; padding:1.6rem 1.4rem 1.4rem;
    color:#fff; text-decoration:none; display:block;
    position:relative; overflow:hidden;
    transition: transform .3s cubic-bezier(.34,1.56,.64,1), box-shadow .3s ease;
    will-change: transform;
    min-height:150px;
    /* 3-D stacked shadow */
    box-shadow:
        0 1px 0 rgba(0,0,0,.14),
        0 3px 0 rgba(0,0,0,.10),
        0 6px 0 rgba(0,0,0,.06),
        0 18px 40px rgba(0,0,0,.22),
        inset 0 1px 0 rgba(255,255,255,.20);
}
.db-kpi:hover {
    color:#fff;
    transform: translateY(-7px) scale(1.02);
    box-shadow:
        0 1px 0 rgba(0,0,0,.14),
        0 3px 0 rgba(0,0,0,.10),
        0 6px 0 rgba(0,0,0,.06),
        0 28px 55px rgba(0,0,0,.30),
        inset 0 1px 0 rgba(255,255,255,.28);
}
/* shine overlay */
.db-kpi::after {
    content:'';
    position:absolute; top:0; left:0; right:0; height:48%;
    background:linear-gradient(180deg,rgba(255,255,255,.16) 0%,rgba(255,255,255,0) 100%);
    border-radius:1rem 1rem 0 0; pointer-events:none;
}
/* background icon */
.db-kpi-icon {
    position:absolute; right:-14px; bottom:-16px;
    font-size:6.5rem; opacity:.14; line-height:1;
    transform:rotate(-12deg); pointer-events:none;
    transition: transform .3s ease, opacity .3s ease;
}
.db-kpi:hover .db-kpi-icon { transform:rotate(-6deg) scale(1.1); opacity:.22; }

.db-kpi-label { font-size:.75rem; font-weight:700; text-transform:uppercase; letter-spacing:.07em; opacity:.85; }
.db-kpi-num   { font-size:3rem; font-weight:900; line-height:1.05; margin:.3rem 0 .5rem; font-variant-numeric:tabular-nums; }
.db-kpi-link  { font-size:.76rem; opacity:.82; font-weight:500; }
.db-kpi-link i { margin-right:.25rem; }

.kpi-blue   { background: linear-gradient(135deg, #1a3a6e 0%, #1d4ed8 65%, #3b82f6 100%); }
.kpi-amber  { background: linear-gradient(135deg, #78350f 0%, #b45309 55%, #f59e0b 100%); }
.kpi-green  { background: linear-gradient(135deg, #064e3b 0%, #047857 55%, #10b981 100%); }
.kpi-cyan   { background: linear-gradient(135deg, #0c4a6e 0%, #0369a1 55%, #06b6d4 100%); }

/* Panels */
.db-panel {
    background:#fff; border-radius:1rem;
    box-shadow:0 4px 20px rgba(0,0,0,.08);
    overflow:hidden; display:flex; flex-direction:column;
    height:100%;
}
.db-panel-hd {
    display:flex; align-items:center; justify-content:space-between;
    padding:.9rem 1.3rem;
    border-bottom:1px solid #eef0f7;
    font-weight:700; font-size:.875rem; color:#1e293b;
    background:#fff; flex-shrink:0;
}
.db-panel-hd a {
    font-size:.76rem; font-weight:500; color:#1a3a6e;
    text-decoration:none; display:flex; align-items:center; gap:.3rem;
}
.db-panel-hd a:hover { text-decoration:underline; }
.db-panel-bd { padding:1rem 1.3rem; flex:1; }

/* Activity list */
.act-list { list-style:none; margin:0; padding:0; }
.act-item {
    display:flex; align-items:flex-start; gap:.8rem;
    padding:.65rem .15rem; border-bottom:1px solid #f4f5f9;
    transition:background .12s; border-radius:6px;
}
.act-item:last-child { border-bottom:0; }
.act-item:hover { background:#f8f9fc; }
.act-dot { width:10px; height:10px; border-radius:50%; flex-shrink:0; margin-top:.35rem; box-shadow:0 0 0 3px rgba(0,0,0,.06); }
.act-body { flex:1; min-width:0; }
.act-title {
    font-size:.84rem; font-weight:600; color:#1e293b;
    white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
    display:flex; align-items:center; gap:.4rem;
}
.act-new {
    display:inline-block; padding:0 .4rem;
    background:#2563eb; color:#fff; border-radius:20px;
    font-size:.58rem; font-weight:700; letter-spacing:.04em; flex-shrink:0;
}
.act-msg { font-size:.76rem; color:#64748b; margin-top:.1rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.act-time { font-size:.7rem; color:#94a3b8; margin-top:.15rem; }
.act-mod  { font-weight:600; }
.act-empty { text-align:center; padding:2rem 0; color:#94a3b8; }
.act-empty i { font-size:2.5rem; display:block; margin-bottom:.6rem; opacity:.45; }

/* Bar chart */
.bar-row   { display:flex; align-items:center; gap:.7rem; padding:.4rem 0; }
.bar-lbl   { width:90px; font-size:.78rem; font-weight:600; color:#475569; flex-shrink:0; }
.bar-track { flex:1; height:11px; background:#f0f2f8; border-radius:20px; overflow:hidden; }
.bar-fill  {
    height:100%; border-radius:20px;
    background:var(--bc,#4f6cf7);
    width:0; transition:width 1s cubic-bezier(.34,1.1,.64,1);
}
.bar-val { width:30px; text-align:right; font-size:.8rem; font-weight:700; color:#1e293b; flex-shrink:0; }

/* Quick action buttons */
.qa-btn {
    display:flex; flex-direction:column; align-items:center; justify-content:center;
    gap:.4rem; padding:.9rem .5rem; border-radius:.75rem;
    text-decoration:none; font-size:.76rem; font-weight:700;
    color:#fff; text-align:center; line-height:1.2;
    transition:transform .2s ease, box-shadow .2s ease, filter .2s ease;
    border:none; cursor:pointer;
}
.qa-btn i { font-size:1.5rem; }
.qa-btn:hover { transform:translateY(-4px); filter:brightness(1.08); color:#fff; box-shadow:0 10px 28px rgba(0,0,0,.22); }
.qa-blue  { background:linear-gradient(135deg,#1a3a6e,#2563eb); box-shadow:0 4px 14px rgba(37,99,235,.38); }
.qa-amber { background:linear-gradient(135deg,#78350f,#f59e0b); box-shadow:0 4px 14px rgba(245,158,11,.38); }
.qa-green { background:linear-gradient(135deg,#064e3b,#10b981); box-shadow:0 4px 14px rgba(16,185,129,.38); }
.qa-cyan  { background:linear-gradient(135deg,#0c4a6e,#06b6d4); box-shadow:0 4px 14px rgba(6,182,212,.38); }

/* Summary total badge */
.db-total {
    text-align:center; padding:.85rem; margin-top:.5rem;
    background:linear-gradient(135deg,#f0f4ff,#e8eeff);
    border-radius:.75rem; border:1px solid #dce4ff;
}
.db-total-num  { font-size:2rem; font-weight:900; color:#1a3a6e; line-height:1; }
.db-total-lbl  { font-size:.72rem; color:#64748b; font-weight:600; text-transform:uppercase; letter-spacing:.05em; margin-top:.2rem; }
</style>

<!-- ══ HERO ══ -->
<div class="db-hero">
    <span class="circle c1"></span>
    <span class="circle c2"></span>
    <span class="circle c3"></span>
    <div class="db-hero-row">
        <div class="d-flex align-items-center gap-3">
            <div class="db-avatar"><i class="bi bi-person-fill"></i></div>
            <div>
                <div class="db-saludo"><?= e($saludo) ?>,</div>
                <div class="db-nombre"><?= e($nombre) ?></div>
                <span class="db-rol-pill"><?= e($rolLabel) ?></span>
            </div>
        </div>
        <div class="text-end text-white">
            <div class="db-clock" id="db-clock">--:--:--</div>
            <div class="db-date"><?= e($fechaTexto) ?></div>
        </div>
    </div>
</div>

<!-- ══ KPI CARDS ══ -->
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <a href="<?= url('/colegios') ?>" class="db-kpi kpi-blue">
            <div class="db-kpi-icon"><i class="bi bi-building"></i></div>
            <div style="position:relative;z-index:2;">
                <div class="db-kpi-label">Colegios</div>
                <div class="db-kpi-num" data-target="<?= $stats['colegios'] ?>">0</div>
                <div class="db-kpi-link"><i class="bi bi-arrow-right-circle"></i>Ver registros</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-xl-3">
        <a href="<?= url('/cnel') ?>" class="db-kpi kpi-amber">
            <div class="db-kpi-icon"><i class="bi bi-lightning-charge"></i></div>
            <div style="position:relative;z-index:2;">
                <div class="db-kpi-label">CNEL / Luminarias</div>
                <div class="db-kpi-num" data-target="<?= $stats['cnel'] ?>">0</div>
                <div class="db-kpi-link"><i class="bi bi-arrow-right-circle"></i>Ver registros</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-xl-3">
        <a href="<?= url('/encuentros') ?>" class="db-kpi kpi-green">
            <div class="db-kpi-icon"><i class="bi bi-people"></i></div>
            <div style="position:relative;z-index:2;">
                <div class="db-kpi-label">Encuentros</div>
                <div class="db-kpi-num" data-target="<?= $stats['encuentros'] ?>">0</div>
                <div class="db-kpi-link"><i class="bi bi-arrow-right-circle"></i>Ver registros</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-xl-3">
        <a href="<?= url('/actas') ?>" class="db-kpi kpi-cyan">
            <div class="db-kpi-icon"><i class="bi bi-file-earmark-text"></i></div>
            <div style="position:relative;z-index:2;">
                <div class="db-kpi-label">Actas / Trabajadores</div>
                <div class="db-kpi-num" data-target="<?= $stats['actas'] ?>">0</div>
                <div class="db-kpi-link"><i class="bi bi-arrow-right-circle"></i>Ver registros</div>
            </div>
        </a>
    </div>
</div>

<!-- ══ FILA INFERIOR ══ -->
<div class="row g-3">

    <!-- Actividad reciente -->
    <div class="col-lg-7">
        <div class="db-panel">
            <div class="db-panel-hd">
                <span><i class="bi bi-activity me-2 text-primary"></i>Actividad Reciente</span>
                <a href="<?= url('/notificaciones') ?>">
                    Ver todas
                    <?php if ($unreadCount > 0): ?>
                        <span class="badge bg-danger rounded-pill" style="font-size:.6rem;"><?= $unreadCount ?></span>
                    <?php endif; ?>
                </a>
            </div>
            <div class="db-panel-bd">
                <?php if (empty($recentActivity)): ?>
                    <div class="act-empty">
                        <i class="bi bi-bell-slash"></i>
                        <p class="mb-0" style="font-size:.85rem;">Sin actividad registrada aún.<br>Los registros que ingreses aparecerán aquí.</p>
                    </div>
                <?php else: ?>
                    <?php
                    $dotColors = ['colegios'=>'#2563eb','cnel'=>'#f59e0b','encuentros'=>'#10b981','actas'=>'#06b6d4','usuarios'=>'#ef4444'];
                    ?>
                    <ul class="act-list">
                    <?php foreach ($recentActivity as $n):
                        $mod   = $n['modulo'] ?? '';
                        $color = $dotColors[$mod] ?? '#6c757d';
                    ?>
                        <li class="act-item">
                            <span class="act-dot" style="background:<?= e($color) ?>"></span>
                            <div class="act-body">
                                <div class="act-title">
                                    <?= e($n['titulo']) ?>
                                    <?php if (!$n['leida']): ?><span class="act-new">NUEVO</span><?php endif; ?>
                                </div>
                                <div class="act-msg"><?= e($n['mensaje']) ?></div>
                                <div class="act-time">
                                    <i class="bi bi-clock"></i>
                                    <?= date('d/m/Y H:i', strtotime($n['created_at'])) ?>
                                    <?php if ($mod): ?>
                                        &middot; <span class="act-mod" style="color:<?= e($color) ?>"><?= e(ucfirst($mod)) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Panel derecho -->
    <div class="col-lg-5 d-flex flex-column gap-3">

        <!-- Barras de resumen -->
        <div class="db-panel">
            <div class="db-panel-hd">
                <span><i class="bi bi-bar-chart-fill me-2 text-primary"></i>Resumen del Sistema</span>
            </div>
            <div class="db-panel-bd">
                <?php
                $bars = [
                    ['l'=>'Colegios',   'v'=>$stats['colegios'],   'c'=>'#2563eb'],
                    ['l'=>'CNEL',       'v'=>$stats['cnel'],       'c'=>'#f59e0b'],
                    ['l'=>'Encuentros', 'v'=>$stats['encuentros'], 'c'=>'#10b981'],
                    ['l'=>'Actas',      'v'=>$stats['actas'],      'c'=>'#06b6d4'],
                ];
                ?>
                <?php foreach ($bars as $b): ?>
                <div class="bar-row">
                    <div class="bar-lbl"><?= e($b['l']) ?></div>
                    <div class="bar-track">
                        <div class="bar-fill" style="--bc:<?= e($b['c']) ?>;"
                             data-pct="<?= round($b['v'] / $maxVal * 100) ?>"></div>
                    </div>
                    <div class="bar-val"><?= $b['v'] ?></div>
                </div>
                <?php endforeach; ?>
                <div class="db-total">
                    <div class="db-total-num"><?= $total ?></div>
                    <div class="db-total-lbl">Total de registros en el sistema</div>
                </div>
            </div>
        </div>

        <!-- Acciones rápidas -->
        <div class="db-panel">
            <div class="db-panel-hd">
                <span><i class="bi bi-lightning-fill me-2 text-primary"></i>Acciones Rápidas</span>
            </div>
            <div class="db-panel-bd">
                <div class="row g-2">
                    <div class="col-6">
                        <a href="<?= url('/colegios/nuevo') ?>" class="qa-btn qa-blue">
                            <i class="bi bi-building-add"></i><span>Nuevo Colegio</span>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="<?= url('/cnel/nuevo') ?>" class="qa-btn qa-amber">
                            <i class="bi bi-lightning-fill"></i><span>Nuevo CNEL</span>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="<?= url('/encuentros/nuevo') ?>" class="qa-btn qa-green">
                            <i class="bi bi-people-fill"></i><span>Nuevo Encuentro</span>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="<?= url('/actas/nuevo') ?>" class="qa-btn qa-cyan">
                            <i class="bi bi-file-earmark-plus"></i><span>Nuevo Trabajador</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
(function () {
    // Reloj
    function pad(n) { return String(n).padStart(2, '0'); }
    function tick() {
        var d = new Date(), el = document.getElementById('db-clock');
        if (el) el.textContent = pad(d.getHours()) + ':' + pad(d.getMinutes()) + ':' + pad(d.getSeconds());
    }
    tick(); setInterval(tick, 1000);

    // Contador animado KPI
    document.querySelectorAll('.db-kpi-num[data-target]').forEach(function (el) {
        var target = parseInt(el.dataset.target, 10) || 0;
        if (target === 0) { el.textContent = '0'; return; }
        var start = null, dur = 1100;
        function step(ts) {
            if (!start) start = ts;
            var p = Math.min((ts - start) / dur, 1);
            var ease = 1 - Math.pow(1 - p, 3);
            el.textContent = Math.round(ease * target);
            if (p < 1) requestAnimationFrame(step);
            else el.textContent = target;
        }
        requestAnimationFrame(step);
    });

    // Barras animadas
    document.querySelectorAll('.bar-fill[data-pct]').forEach(function (el) {
        var pct = el.dataset.pct + '%';
        setTimeout(function () { el.style.width = pct; }, 150);
    });

    // Tilt 3D en KPI cards
    document.querySelectorAll('.db-kpi').forEach(function (card) {
        card.addEventListener('mousemove', function (e) {
            var r = card.getBoundingClientRect();
            var x = (e.clientX - r.left) / r.width  - 0.5;
            var y = (e.clientY - r.top)  / r.height - 0.5;
            card.style.transform = 'perspective(600px) rotateY(' + (x * 14) + 'deg) rotateX(' + (-y * 14) + 'deg) translateY(-7px) scale(1.025)';
        });
        card.addEventListener('mouseleave', function () {
            card.style.transform = '';
        });
    });
})();
</script>
