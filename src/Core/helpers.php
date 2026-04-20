<?php

declare(strict_types=1);

/** Escapa output para prevenir XSS */
function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Genera URL absoluta de un asset público */
function asset(string $path): string
{
    return BASE_URL . '/assets/' . ltrim($path, '/');
}

/** Genera URL absoluta de una ruta de la app */
function url(string $path = ''): string
{
    return BASE_URL . '/' . ltrim($path, '/');
}

/** Genera y retorna el token CSRF de la sesión */
function csrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/** Renderiza el campo hidden con el token CSRF */
function csrfField(): string
{
    return '<input type="hidden" name="csrf_token" value="' . csrfToken() . '">';
}

/** Verifica el token CSRF del POST, detiene ejecución si falla */
function verifyCsrf(): void
{
    $submitted = $_POST['csrf_token'] ?? '';
    $stored    = $_SESSION['csrf_token'] ?? '';

    if (!hash_equals($stored, $submitted)) {
        http_response_code(403);
        die('Token de seguridad inválido. Por favor recargue la página e intente nuevamente.');
    }
}
