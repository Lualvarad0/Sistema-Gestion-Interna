<?php

/**
 * Script de instalación inicial.
 * Ejecutar UNA SOLA VEZ desde la raíz del proyecto:
 *
 *   php setup.php
 *
 * Crea el usuario administrador con contraseña hasheada.
 */

declare(strict_types=1);

define('ROOT_PATH', __DIR__);
define('SRC_PATH',  __DIR__ . '/src');

require_once SRC_PATH . '/Config/Database.php';

use App\Config\Database;

echo "\n=== Gobernación del Guayas — Setup ===\n\n";

$db = Database::getInstance();

// Verificar si ya existe el usuario admin
$check = $db->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
$check->execute(['admin']);

if ((int) $check->fetchColumn() > 0) {
    echo "⚠  El usuario 'admin' ya existe. Si desea cambiar su contraseña, use el siguiente hash:\n\n";
    echo "   " . password_hash('admin123', PASSWORD_DEFAULT) . "\n\n";
    exit(0);
}

$password = 'admin123'; // Cambiar esta contraseña en producción
$hash     = password_hash($password, PASSWORD_DEFAULT);

$stmt = $db->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
$stmt->execute(['admin', $hash]);

echo "✓  Usuario administrador creado exitosamente.\n\n";
echo "   Usuario:    admin\n";
echo "   Contraseña: {$password}\n\n";
echo "⚠  IMPORTANTE: Cambie la contraseña después del primer inicio de sesión.\n\n";
echo "Acceda al sistema en: " . (require ROOT_PATH . '/config/app.php')['app']['url'] . "\n\n";
