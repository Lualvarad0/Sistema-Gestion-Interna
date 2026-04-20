<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\User;
use App\Models\Notificacion;

class UserController extends BaseController
{
    public function index(): void
    {
        $this->requireRole('administrador');

        $this->render('usuarios/report', [
            'pageTitle' => 'Gestión de Usuarios',
            'usuarios'  => User::allUsers(),
            'roles'     => User::ROLES,
        ]);
    }

    public function form(): void
    {
        $this->requireRole('administrador');

        $this->render('usuarios/form', [
            'pageTitle' => 'Crear Usuario',
            'roles'     => User::ROLES,
            'editing'   => false,
            'usuario'   => [],
        ]);
    }

    public function store(): void
    {
        $this->requireRole('administrador');
        verifyCsrf();

        $username       = trim($_POST['username'] ?? '');
        $password       = $_POST['password'] ?? '';
        $rol            = $_POST['rol'] ?? '';
        $nombreCompleto = trim($_POST['nombre_completo'] ?? '');
        $email          = trim($_POST['email'] ?? '');

        if ($username === '' || $password === '' || $rol === '') {
            $this->setFlash('error', 'Usuario, contraseña y rol son requeridos.');
            $this->redirect('/usuarios/nuevo');
        }

        if (!array_key_exists($rol, User::ROLES)) {
            $this->setFlash('error', 'Rol no válido.');
            $this->redirect('/usuarios/nuevo');
        }

        if (strlen($password) < 6) {
            $this->setFlash('error', 'La contraseña debe tener al menos 6 caracteres.');
            $this->redirect('/usuarios/nuevo');
        }

        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->setFlash('error', 'El correo electrónico no tiene un formato válido.');
            $this->redirect('/usuarios/nuevo');
        }

        if (User::usernameExists($username)) {
            $this->setFlash('error', "El usuario '{$username}' ya existe en el sistema.");
            $this->redirect('/usuarios/nuevo');
        }

        User::createUser($username, $password, $rol, $nombreCompleto, $email);

        Notificacion::notify(
            'Nuevo usuario creado',
            "Se creó el usuario '{$username}' con rol " . User::getRoleLabel($rol) . '.',
            'info',
            'usuarios'
        );

        $this->setFlash('success', "Usuario '{$username}' creado exitosamente.");
        $this->redirect('/usuarios');
    }

    public function edit(string $id): void
    {
        $this->requireRole('administrador');

        $intId   = (int) $id;
        $usuario = User::findById($intId);
        if ($usuario === null) {
            $this->setFlash('error', 'Usuario no encontrado.');
            $this->redirect('/usuarios');
        }

        $this->render('usuarios/form', [
            'pageTitle' => 'Editar Usuario',
            'roles'     => User::ROLES,
            'editing'   => true,
            'usuario'   => $usuario,
        ]);
    }

    public function update(string $id): void
    {
        $this->requireRole('administrador');
        verifyCsrf();

        $intId   = (int) $id;
        $usuario = User::findById($intId);
        if ($usuario === null) {
            $this->setFlash('error', 'Usuario no encontrado.');
            $this->redirect('/usuarios');
        }

        $rol            = $_POST['rol'] ?? '';
        $nombreCompleto = trim($_POST['nombre_completo'] ?? '');
        $email          = trim($_POST['email'] ?? '');
        $nuevaPassword  = $_POST['password'] ?? '';

        if ($rol === '' || !array_key_exists($rol, User::ROLES)) {
            $this->setFlash('error', 'Rol no válido.');
            $this->redirect('/usuarios/' . $intId . '/editar');
        }

        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->setFlash('error', 'El correo electrónico no tiene un formato válido.');
            $this->redirect('/usuarios/' . $intId . '/editar');
        }

        $selfId = (int) ($_SESSION['user']['id'] ?? 0);
        if ($intId === $selfId && $rol !== 'administrador') {
            $this->setFlash('error', 'No puede cambiar su propio rol de administrador.');
            $this->redirect('/usuarios/' . $intId . '/editar');
        }

        User::updateProfile($intId, $nombreCompleto, $email);
        User::updateRol($intId, $rol);

        if ($nuevaPassword !== '') {
            if (strlen($nuevaPassword) < 6) {
                $this->setFlash('error', 'La nueva contraseña debe tener al menos 6 caracteres.');
                $this->redirect('/usuarios/' . $intId . '/editar');
            }
            User::updatePassword($intId, password_hash($nuevaPassword, PASSWORD_DEFAULT));
        }

        $this->setFlash('success', 'Usuario actualizado exitosamente.');
        $this->redirect('/usuarios');
    }

    public function toggle(string $id): void
    {
        $this->requireRole('administrador');
        verifyCsrf();

        $intId  = (int) $id;
        $selfId = (int) ($_SESSION['user']['id'] ?? 0);
        if ($intId === $selfId) {
            $this->setFlash('error', 'No puede desactivar su propia cuenta.');
            $this->redirect('/usuarios');
        }

        $usuario = User::findById($intId);
        if ($usuario === null) {
            $this->setFlash('error', 'Usuario no encontrado.');
            $this->redirect('/usuarios');
        }

        User::toggleActive($intId);

        $estado = $usuario['activo'] ? 'desactivado' : 'activado';
        $this->setFlash('success', "Usuario '{$usuario['username']}' {$estado} exitosamente.");
        $this->redirect('/usuarios');
    }
}
