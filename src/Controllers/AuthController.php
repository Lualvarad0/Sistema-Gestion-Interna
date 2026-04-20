<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\User;

class AuthController extends BaseController
{
    public function showLogin(): void
    {
        if (isset($_SESSION['user'])) {
            $this->redirect('/');
        }

        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);

        $this->renderOnly('auth/login', [
            'flash'     => $flash,
            'pageTitle' => 'Iniciar Sesión',
        ]);
    }

    public function login(): void
    {
        verifyCsrf();

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($username === '' || $password === '') {
            $this->setFlash('error', 'Usuario y contraseña son requeridos.');
            $this->redirect('/login');
        }

        $user = User::findByUsername($username);

        if ($user !== null && password_verify($password, $user['password'])) {
            if (isset($user['activo']) && !(bool)$user['activo']) {
                $this->setFlash('error', 'Su cuenta está desactivada. Contacte al administrador.');
                $this->redirect('/login');
            }
            session_regenerate_id(true);
            $_SESSION['user'] = [
                'id'             => $user['id'],
                'username'       => $user['username'],
                'rol'            => $user['rol'] ?? 'operador',
                'nombre_completo' => $user['nombre_completo'] ?? '',
            ];
            $this->redirect('/');
        }

        $this->setFlash('error', 'Credenciales incorrectas.');
        $this->redirect('/login');
    }

    public function logout(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params['path'], $params['domain'],
                $params['secure'], $params['httponly']
            );
        }

        session_destroy();
        $this->redirect('/login');
    }
}
