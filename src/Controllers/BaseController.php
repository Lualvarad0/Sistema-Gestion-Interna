<?php

declare(strict_types=1);

namespace App\Controllers;

/**
 * Controlador base: renderizado de vistas, redirecciones,
 * autenticación requerida y mensajes flash.
 */
abstract class BaseController
{
    /**
     * Renderiza una vista dentro del layout principal.
     * Pasa $flash, $currentUser y $pageTitle a todas las vistas.
     */
    protected function render(string $view, array $data = []): void
    {
        $data['flash']       = $this->popFlash();
        $data['currentUser'] = $_SESSION['user'] ?? null;
        $data['pageTitle']   ??= 'Gobernación del Guayas';

        extract($data, EXTR_SKIP);

        $viewFile = VIEWS_PATH . '/' . $view . '.php';

        if (!file_exists($viewFile)) {
            throw new \RuntimeException("Vista no encontrada: {$view}");
        }

        require VIEWS_PATH . '/layout/header.php';
        require $viewFile;
        require VIEWS_PATH . '/layout/footer.php';
    }

    /**
     * Renderiza una vista SIN layout (ej. login).
     */
    protected function renderOnly(string $view, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        require VIEWS_PATH . '/' . $view . '.php';
    }

    /**
     * Redirige a una ruta relativa de la app y detiene la ejecución.
     */
    protected function redirect(string $path): never
    {
        header('Location: ' . BASE_URL . $path);
        exit;
    }

    /**
     * Verifica sesión activa; redirige al login si no existe.
     */
    protected function requireAuth(): void
    {
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
    }

    /** Guarda un mensaje flash en la sesión */
    protected function setFlash(string $type, string $message): void
    {
        $_SESSION['flash'] = compact('type', 'message');
    }

    /** Extrae y elimina el flash de la sesión (se muestra una sola vez) */
    private function popFlash(): ?array
    {
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        return $flash;
    }
}
