<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\User;

class ProfileController extends BaseController
{
    public function show(): void
    {
        $this->requireAuth();

        $userId = (int) ($_SESSION['user']['id'] ?? 0);
        $user   = User::findById($userId);

        $this->render('perfil/index', [
            'pageTitle'  => 'Mi Perfil',
            'userRecord' => $user,
        ]);
    }

    public function updateProfile(): void
    {
        $this->requireAuth();
        verifyCsrf();

        $userId        = (int) ($_SESSION['user']['id'] ?? 0);
        $nombreCompleto = trim($_POST['nombre_completo'] ?? '');
        $email          = trim($_POST['email'] ?? '');

        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->setFlash('error', 'El correo electrónico no tiene un formato válido.');
            $this->redirect('/perfil');
        }

        User::updateProfile($userId, $nombreCompleto, $email);

        // Actualizar el nombre en la sesión
        $_SESSION['user']['nombre_completo'] = $nombreCompleto;

        $this->setFlash('success', 'Perfil actualizado exitosamente.');
        $this->redirect('/perfil');
    }

    public function updatePassword(): void
    {
        $this->requireAuth();
        verifyCsrf();

        $actual  = $_POST['password_actual']  ?? '';
        $nuevo   = $_POST['password_nuevo']   ?? '';
        $confirm = $_POST['password_confirm'] ?? '';

        if ($actual === '' || $nuevo === '' || $confirm === '') {
            $this->setFlash('error', 'Todos los campos son requeridos.');
            $this->redirect('/perfil');
        }

        if ($nuevo !== $confirm) {
            $this->setFlash('error', 'La nueva contraseña y la confirmación no coinciden.');
            $this->redirect('/perfil');
        }

        if (strlen($nuevo) < 6) {
            $this->setFlash('error', 'La nueva contraseña debe tener al menos 6 caracteres.');
            $this->redirect('/perfil');
        }

        $userId = (int) ($_SESSION['user']['id'] ?? 0);
        $user   = User::findById($userId);

        if ($user === null || !password_verify($actual, $user['password'])) {
            $this->setFlash('error', 'La contraseña actual es incorrecta.');
            $this->redirect('/perfil');
        }

        User::updatePassword($userId, password_hash($nuevo, PASSWORD_DEFAULT));

        $this->setFlash('success', 'Contraseña actualizada exitosamente.');
        $this->redirect('/perfil');
    }
}
