<?php

declare(strict_types=1);

namespace App\Models;

class Setting extends BaseModel
{
    protected static string $table = 'settings';

    public static function get(string $clave, mixed $default = null): mixed
    {
        $stmt = static::db()->prepare(
            'SELECT valor, tipo FROM `settings` WHERE clave = ? LIMIT 1'
        );
        $stmt->execute([$clave]);
        $row = $stmt->fetch();
        return $row !== false ? self::cast($row['valor'], $row['tipo']) : $default;
    }

    public static function set(string $clave, string $valor): bool
    {
        $stmt = static::db()->prepare(
            'INSERT INTO `settings` (clave, valor)
             VALUES (?, ?)
             ON DUPLICATE KEY UPDATE valor = VALUES(valor)'
        );
        return $stmt->execute([$clave, $valor]);
    }

    public static function allAsMap(): array
    {
        $stmt = static::db()->query('SELECT clave, valor, tipo FROM `settings` ORDER BY clave');
        $map  = [];
        foreach ($stmt->fetchAll() as $row) {
            $map[$row['clave']] = self::cast($row['valor'], $row['tipo']);
        }
        return $map;
    }

    private static function cast(string $valor, string $tipo): mixed
    {
        return match ($tipo) {
            'integer' => (int)   $valor,
            'boolean' => (bool) (int) $valor,
            default   => $valor,
        };
    }
}
