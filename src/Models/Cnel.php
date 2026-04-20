<?php

declare(strict_types=1);

namespace App\Models;

class Cnel extends BaseModel
{
    protected static string $table      = 'registrocnel';
    protected static string $primaryKey = 'idregistrocnel';

    public static function create(array $data): bool
    {
        $stmt = static::db()->prepare('
            INSERT INTO registrocnel
                (nombreinstitucion, nuevasluminarias, mantenimiento, tipo,
                 cantidad, estado, distrito, codtrabajador, nombretrabajador)
            VALUES
                (:nombreinstitucion, :nuevasluminarias, :mantenimiento, :tipo,
                 :cantidad, :estado, :distrito, :codtrabajador, :nombretrabajador)
        ');
        return $stmt->execute($data);
    }

    /** Lista simplificada para selects en formularios */
    public static function allForSelect(): array
    {
        $stmt = static::db()->query(
            'SELECT idregistrocnel, nombreinstitucion FROM registrocnel ORDER BY nombreinstitucion'
        );
        return $stmt->fetchAll();
    }
}
