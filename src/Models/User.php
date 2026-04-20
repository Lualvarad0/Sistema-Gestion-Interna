<?php

declare(strict_types=1);

namespace App\Models;

class User extends BaseModel
{
    protected static string $table = 'users';

    public const ROLES = [
        'administrador' => 'Administrador',
        'supervisor'    => 'Supervisor',
        'operador'      => 'Operador',
    ];

    public const ROLE_COLORS = [
        'administrador' => 'danger',
        'supervisor'    => 'warning',
        'operador'      => 'info',
    ];

    public static function findByUsername(string $username): ?array
    {
        $stmt = static::db()->prepare(
            'SELECT * FROM `users` WHERE username = ? LIMIT 1'
        );
        $stmt->execute([$username]);
        return $stmt->fetch() ?: null;
    }

    public static function findById(int $id): ?array
    {
        $stmt = static::db()->prepare('SELECT * FROM `users` WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public static function allUsers(): array
    {
        $stmt = static::db()->query(
            'SELECT id, username, rol, nombre_completo, email, activo, created_at
             FROM `users`
             ORDER BY FIELD(rol,"administrador","supervisor","operador"), username'
        );
        return $stmt->fetchAll();
    }

    public static function createUser(
        string $username,
        string $password,
        string $rol,
        string $nombreCompleto = '',
        string $email = ''
    ): bool {
        $stmt = static::db()->prepare(
            'INSERT INTO `users` (username, password, rol, nombre_completo, email, activo)
             VALUES (?, ?, ?, ?, ?, 1)'
        );
        return $stmt->execute([
            $username,
            password_hash($password, PASSWORD_DEFAULT),
            $rol,
            $nombreCompleto !== '' ? $nombreCompleto : null,
            $email !== '' ? $email : null,
        ]);
    }

    public static function updateProfile(int $id, string $nombreCompleto, string $email): bool
    {
        $stmt = static::db()->prepare(
            'UPDATE `users` SET nombre_completo = ?, email = ? WHERE id = ?'
        );
        return $stmt->execute([
            $nombreCompleto !== '' ? $nombreCompleto : null,
            $email !== '' ? $email : null,
            $id,
        ]);
    }

    public static function updatePassword(int $id, string $hash): bool
    {
        $stmt = static::db()->prepare('UPDATE `users` SET password = ? WHERE id = ?');
        return $stmt->execute([$hash, $id]);
    }

    public static function updateRol(int $id, string $rol): bool
    {
        $stmt = static::db()->prepare('UPDATE `users` SET rol = ? WHERE id = ?');
        return $stmt->execute([$rol, $id]);
    }

    public static function toggleActive(int $id): bool
    {
        $stmt = static::db()->prepare('UPDATE `users` SET activo = NOT activo WHERE id = ?');
        return $stmt->execute([$id]);
    }

    public static function usernameExists(string $username, int $excludeId = 0): bool
    {
        $stmt = static::db()->prepare(
            'SELECT COUNT(*) FROM `users` WHERE username = ? AND id != ?'
        );
        $stmt->execute([$username, $excludeId]);
        return (int) $stmt->fetchColumn() > 0;
    }

    public static function getRoleLabel(string $rol): string
    {
        return self::ROLES[$rol] ?? ucfirst($rol);
    }

    public static function getRoleColor(string $rol): string
    {
        return self::ROLE_COLORS[$rol] ?? 'secondary';
    }
}
