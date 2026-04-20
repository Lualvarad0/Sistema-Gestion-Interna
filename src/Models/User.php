<?php

declare(strict_types=1);

namespace App\Models;

class User extends BaseModel
{
    protected static string $table = 'users';

    public static function findByUsername(string $username): ?array
    {
        $stmt = static::db()->prepare(
            'SELECT * FROM `users` WHERE username = ? LIMIT 1'
        );
        $stmt->execute([$username]);
        return $stmt->fetch() ?: null;
    }
}
