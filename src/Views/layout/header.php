<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> — Gobernación del Guayas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?= asset('css/app.css') ?>" rel="stylesheet">
</head>
<body class="bg-light d-flex flex-column min-vh-100">

<?php require VIEWS_PATH . '/layout/nav.php'; ?>

<main class="container py-4 flex-grow-1">

    <?php if (!empty($flash)): ?>
        <div class="alert alert-<?= $flash['type'] === 'error' ? 'danger' : e($flash['type']) ?> alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-<?= $flash['type'] === 'error' ? 'exclamation-circle-fill' : 'check-circle-fill' ?> flex-shrink-0"></i>
            <span><?= e($flash['message']) ?></span>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
