<?php
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$basePath    = parse_url(BASE_URL, PHP_URL_PATH);
$route       = '/' . ltrim(substr($currentPath, strlen($basePath)), '/');

function navLink(string $href, string $icon, string $label, string $route): string
{
    $active = str_starts_with($route, $href) ? ' active' : '';
    return sprintf(
        '<li class="nav-item"><a class="nav-link%s" href="%s"><i class="bi bi-%s me-1"></i>%s</a></li>',
        $active, url($href), $icon, $label
    );
}
?>
<nav class="navbar navbar-expand-lg navbar-dark shadow-sm" style="background: var(--color-primary);">
    <div class="container">
        <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="<?= url('/') ?>">
            <i class="bi bi-building-fill-gear fs-5"></i>
            <span class="d-none d-sm-inline">Gobernación del Guayas</span>
            <span class="d-sm-none">Gob. Guayas</span>
        </a>

        <button class="navbar-toggler border-0" type="button"
                data-bs-toggle="collapse" data-bs-target="#mainNav"
                aria-controls="mainNav" aria-expanded="false" aria-label="Menú">
            <span class="navbar-toggler-icon"></span>
        </button>

        <?php if (!empty($currentUser)): ?>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?= navLink('/colegios',   'building',           'Colegios',   $route) ?>
                <?= navLink('/cnel',       'lightning-charge',   'CNEL',       $route) ?>
                <?= navLink('/encuentros', 'people',             'Encuentros', $route) ?>
                <?= navLink('/actas',      'file-earmark-text',  'Actas',      $route) ?>
            </ul>
            <div class="d-flex align-items-center gap-3">
                <span class="text-white-50 small">
                    <i class="bi bi-person-circle me-1"></i><?= e($currentUser['username']) ?>
                </span>
                <a href="<?= url('/logout') ?>" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-box-arrow-right me-1"></i>Salir
                </a>
            </div>
        </div>
        <?php endif; ?>
    </div>
</nav>
