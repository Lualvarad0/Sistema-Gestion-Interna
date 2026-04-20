<?php

declare(strict_types=1);

namespace App\Models;

use App\Config\Database;
use PDO;

/**
 * Modelo base con operaciones CRUD comunes.
 * Cada modelo hijo define $table y opcionalmente $primaryKey.
 */
abstract class BaseModel
{
    protected static string $table;
    protected static string $primaryKey = 'id';

    protected static function db(): PDO
    {
        return Database::getInstance();
    }

    public static function all(): array
    {
        $stmt = static::db()->query(
            'SELECT * FROM `' . static::$table . '` ORDER BY created_at DESC'
        );
        return $stmt->fetchAll();
    }

    public static function find(int|string $id): ?array
    {
        $stmt = static::db()->prepare(
            'SELECT * FROM `' . static::$table . '` WHERE `' . static::$primaryKey . '` = ? LIMIT 1'
        );
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public static function count(): int
    {
        $stmt = static::db()->query('SELECT COUNT(*) FROM `' . static::$table . '`');
        return (int) $stmt->fetchColumn();
    }

    /**
     * Inserta una fila usando los keys del array como columnas.
     * Todos los valores se pasan como parámetros (previene SQL Injection).
     */
    public static function create(array $data): bool
    {
        $columns      = implode(', ', array_map(fn($k) => "`$k`", array_keys($data)));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        $stmt = static::db()->prepare(
            "INSERT INTO `" . static::$table . "` ($columns) VALUES ($placeholders)"
        );
        return $stmt->execute(array_values($data));
    }
}
