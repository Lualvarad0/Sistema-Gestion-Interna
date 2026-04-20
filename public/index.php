<?php

declare(strict_types=1);

define('ROOT_PATH',  dirname(__DIR__));
define('SRC_PATH',   ROOT_PATH . '/src');
define('VIEWS_PATH', SRC_PATH . '/Views');

$config = require ROOT_PATH . '/config/app.php';

define('BASE_URL', rtrim($config['app']['url'], '/'));

ini_set('session.name',          $config['session']['name']);
ini_set('session.gc_maxlifetime', (string) $config['session']['lifetime']);
ini_set('session.cookie_httponly', '1');
ini_set('session.use_strict_mode', '1');

session_start();

require SRC_PATH . '/bootstrap.php';
