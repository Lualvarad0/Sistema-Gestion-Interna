<?php

declare(strict_types=1);

namespace App\Models;

class Colegio extends BaseModel
{
    protected static string $table      = 'formulario';
    protected static string $primaryKey = 'id_formulario';

    /** Trae colegios con el nombre del registro CNEL asociado (LEFT JOIN) */
    public static function all(): array
    {
        $stmt = static::db()->query('
            SELECT f.*, r.nombreinstitucion AS cnel_institucion
            FROM formulario f
            LEFT JOIN registrocnel r ON f.idregistrocnel = r.idregistrocnel
            ORDER BY f.created_at DESC
        ');
        return $stmt->fetchAll();
    }

    public static function create(array $data): bool
    {
        $stmt = static::db()->prepare('
            INSERT INTO formulario
                (nombreinstitucion, rector, direccion, telefono, distrito, idregistrocnel, latitud, longitud)
            VALUES
                (:nombreinstitucion, :rector, :direccion, :telefono, :distrito, :idregistrocnel, :latitud, :longitud)
        ');
        return $stmt->execute($data);
    }
}
