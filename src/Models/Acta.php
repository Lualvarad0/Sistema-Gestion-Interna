<?php

declare(strict_types=1);

namespace App\Models;

class Acta extends BaseModel
{
    protected static string $table = 'acta';

    public static function create(array $data): bool
    {
        $stmt = static::db()->prepare('
            INSERT INTO acta (codtrabajador, nombretrabajador, cedula, parroquia)
            VALUES (:codtrabajador, :nombretrabajador, :cedula, :parroquia)
        ');
        return $stmt->execute($data);
    }

    /** Verifica si ya existe un trabajador con esa cédula (evita duplicados) */
    public static function existsByCedula(string $cedula): bool
    {
        $stmt = static::db()->prepare('SELECT COUNT(*) FROM acta WHERE cedula = ?');
        $stmt->execute([$cedula]);
        return (int) $stmt->fetchColumn() > 0;
    }

    /** Lista para selects en el formulario CNEL */
    public static function allForSelect(): array
    {
        $stmt = static::db()->query(
            'SELECT codtrabajador, nombretrabajador FROM acta ORDER BY nombretrabajador'
        );
        return $stmt->fetchAll();
    }
}
