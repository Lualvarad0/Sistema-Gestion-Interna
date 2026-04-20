<?php

declare(strict_types=1);

use App\Core\Router;
use App\Controllers\{
    AuthController,
    HomeController,
    ColegioController,
    CnelController,
    EncuentroController,
    ActaController
};

// ─── Autoloader PSR-4 simplificado ───────────────────────────────────────────
spl_autoload_register(function (string $class): void {
    $prefix  = 'App\\';
    $baseDir = SRC_PATH . '/';

    if (!str_starts_with($class, $prefix)) {
        return;
    }

    $file = $baseDir . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

require_once SRC_PATH . '/Core/helpers.php';

// ─── Configurar router ────────────────────────────────────────────────────────
$config   = require ROOT_PATH . '/config/app.php';
$basePath = parse_url($config['app']['url'], PHP_URL_PATH) ?? '';

$router = new Router($basePath);

// Auth
$router->get('/login',  [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/logout', [AuthController::class, 'logout']);

// Dashboard
$router->get('/', [HomeController::class, 'index']);

// Colegios
$router->get('/colegios',       [ColegioController::class, 'report']);
$router->get('/colegios/nuevo', [ColegioController::class, 'form']);
$router->post('/colegios',      [ColegioController::class, 'store']);

// CNEL / Luminarias
$router->get('/cnel',       [CnelController::class, 'report']);
$router->get('/cnel/nuevo', [CnelController::class, 'form']);
$router->post('/cnel',      [CnelController::class, 'store']);

// Encuentros Ciudadanos
$router->get('/encuentros',       [EncuentroController::class, 'report']);
$router->get('/encuentros/nuevo', [EncuentroController::class, 'form']);
$router->post('/encuentros',      [EncuentroController::class, 'store']);

// Actas / Trabajadores
$router->get('/actas',       [ActaController::class, 'report']);
$router->get('/actas/nuevo', [ActaController::class, 'form']);
$router->post('/actas',      [ActaController::class, 'store']);

$router->dispatch();
