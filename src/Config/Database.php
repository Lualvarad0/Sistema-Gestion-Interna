<?php

declare(strict_types=1);

namespace App\Config;

use PDO;
use PDOException;

/**
 * Singleton PDO — una sola conexión por request.
 * Uso: Database::getInstance()
 */
final class Database
{
    private static ?PDO $instance = null;

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $cfg = require ROOT_PATH . '/config/app.php';
            $db  = $cfg['db'];
            $dsn = "mysql:host={$db['host']};dbname={$db['dbname']};charset={$db['charset']}";

            try {
                self::$instance = new PDO($dsn, $db['user'], $db['pass'], [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]);
            } catch (PDOException $e) {
                error_log('[DB] Conexión fallida: ' . $e->getMessage());
                http_response_code(500);
                die('Error de conexión a la base de datos. Verifique la configuración en config/app.php.');
            }
        }

        return self::$instance;
    }

    // Prevenir instanciación e clonación
    private function __construct() {}
    private function __clone() {}
}
