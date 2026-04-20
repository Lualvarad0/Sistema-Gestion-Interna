<?php

declare(strict_types=1);

return [
    'app' => [
        'name'  => 'Gobernación del Guayas',
        'url'   => 'http://localhost/PHP-repo/public', // Cambiar según tu configuración XAMPP
        'debug' => true,
    ],
    'db' => [
        'host'    => 'localhost',
        'dbname'  => 'gobernacion_guayas',
        'charset' => 'utf8mb4',
        'user'    => 'root',
        'pass'    => '',
    ],
    'session' => [
        'name'     => 'gobguayas_session',
        'lifetime' => 7200, // 2 horas en segundos
    ],
];
