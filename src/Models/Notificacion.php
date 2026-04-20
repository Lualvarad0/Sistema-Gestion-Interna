<?php

declare(strict_types=1);

namespace App\Models;

class Notificacion extends BaseModel
{
    protected static string $table = 'notificaciones';

    public static function recent(int $userId, int $limit = 5): array
    {
        $limit = max(1, min(50, $limit));
        $stmt  = static::db()->prepare(
            'SELECT * FROM `notificaciones`
             WHERE (user_id = ? OR user_id IS NULL)
             ORDER BY created_at DESC
             LIMIT ' . $limit
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public static function forUser(int $userId): array
    {
        $stmt = static::db()->prepare(
            'SELECT * FROM `notificaciones`
             WHERE (user_id = ? OR user_id IS NULL)
             ORDER BY created_at DESC
             LIMIT 100'
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public static function unreadCount(int $userId): int
    {
        $stmt = static::db()->prepare(
            'SELECT COUNT(*) FROM `notificaciones`
             WHERE (user_id = ? OR user_id IS NULL) AND leida = 0'
        );
        $stmt->execute([$userId]);
        return (int) $stmt->fetchColumn();
    }

    public static function markRead(int $id): bool
    {
        $stmt = static::db()->prepare('UPDATE `notificaciones` SET leida = 1 WHERE id = ?');
        return $stmt->execute([$id]);
    }

    public static function markAllRead(int $userId): bool
    {
        $stmt = static::db()->prepare(
            'UPDATE `notificaciones` SET leida = 1
             WHERE (user_id = ? OR user_id IS NULL) AND leida = 0'
        );
        return $stmt->execute([$userId]);
    }

    /** Crea una notificación para todos los usuarios (user_id NULL) o uno específico. */
    public static function notify(
        string $titulo,
        string $mensaje,
        string $tipo = 'info',
        string $modulo = '',
        ?int $userId = null
    ): bool {
        return static::create([
            'tipo'    => $tipo,
            'titulo'  => $titulo,
            'mensaje' => $mensaje,
            'modulo'  => $modulo !== '' ? $modulo : null,
            'user_id' => $userId,
        ]);
    }
}
