<?php

declare(strict_types=1);

namespace App\Models;

class Encuentro extends BaseModel
{
    protected static string $table = 'encuentros';

    public static function create(array $data): bool
    {
        $stmt = static::db()->prepare('
            INSERT INTO encuentros
                (direccion, parroquia, estado, nombrecontacto, cedula, telefono, latitud, longitud)
            VALUES
                (:direccion, :parroquia, :estado, :nombrecontacto, :cedula, :telefono, :latitud, :longitud)
        ');
        return $stmt->execute($data);
    }
}
