<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\User;
use App\Models\Setting;

class ProfileController extends BaseController
{
    public function show(): void
    {
        $this->requireAuth();

        $userId = (int) ($_SESSION['user']['id'] ?? 0);
        $user   = User::findById($userId);

        $settings  = [];
        $isAdmin   = $this->hasRole('administrador');
        if ($isAdmin) {
            $settings = Setting::allAsMap();
        }

        $validTabs = ['perfil', 'seguridad', 'preferencias', 'sistema'];
        $activeTab = $_GET['tab'] ?? 'perfil';
        if (!in_array($activeTab, $validTabs, true)) {
            $activeTab = 'perfil';
        }
        if ($activeTab === 'sistema' && !$isAdmin) {
            $activeTab = 'perfil';
        }

        $this->render('perfil/index', [
            'pageTitle'  => 'Mi Perfil',
            'userRecord' => $user,
            'settings'   => $settings,
            'activeTab'  => $activeTab,
        ]);
    }

    public function updateProfile(): void
    {
        $this->requireAuth();
        verifyCsrf();

        $userId         = (int) ($_SESSION['user']['id'] ?? 0);
        $nombreCompleto = trim($_POST['nombre_completo'] ?? '');
        $email          = trim($_POST['email'] ?? '');
        $telefono       = trim($_POST['telefono'] ?? '');
        $cargo          = trim($_POST['cargo'] ?? '');
        $avatarColor    = trim($_POST['avatar_color'] ?? 'blue');

        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->setFlash('error', 'El correo electrónico no tiene un formato válido.');
            $this->redirect('/perfil?tab=perfil');
        }

        User::updateProfileFull($userId, $nombreCompleto, $email, $telefono, $cargo, $avatarColor);

        $_SESSION['user']['nombre_completo'] = $nombreCompleto;

        $this->setFlash('success', 'Perfil actualizado exitosamente.');
        $this->redirect('/perfil?tab=perfil');
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
            $this->redirect('/perfil?tab=seguridad');
        }

        if ($nuevo !== $confirm) {
            $this->setFlash('error', 'La nueva contraseña y la confirmación no coinciden.');
            $this->redirect('/perfil?tab=seguridad');
        }

        if (strlen($nuevo) < 6) {
            $this->setFlash('error', 'La nueva contraseña debe tener al menos 6 caracteres.');
            $this->redirect('/perfil?tab=seguridad');
        }

        $userId = (int) ($_SESSION['user']['id'] ?? 0);
        $user   = User::findById($userId);

        if ($user === null || !password_verify($actual, $user['password'])) {
            $this->setFlash('error', 'La contraseña actual es incorrecta.');
            $this->redirect('/perfil?tab=seguridad');
        }

        User::updatePassword($userId, password_hash($nuevo, PASSWORD_DEFAULT));

        $this->setFlash('success', 'Contraseña actualizada exitosamente.');
        $this->redirect('/perfil?tab=seguridad');
    }

    public function updateSettings(): void
    {
        $this->requireRole('administrador');
        verifyCsrf();

        $appName      = trim($_POST['app_name'] ?? '');
        $sessionLife  = max(300, min(86400, (int) ($_POST['session_lifetime'] ?? 7200)));
        $maintenance  = isset($_POST['maintenance_mode']) ? '1' : '0';
        $perPage      = max(5, min(100, (int) ($_POST['registros_por_pagina'] ?? 15)));
        $notifActivas = isset($_POST['notificaciones_activas']) ? '1' : '0';

        if ($appName === '') {
            $appName = 'Gobernación del Guayas';
        }

        Setting::set('app_name',               $appName);
        Setting::set('session_lifetime',        (string) $sessionLife);
        Setting::set('maintenance_mode',        $maintenance);
        Setting::set('registros_por_pagina',    (string) $perPage);
        Setting::set('notificaciones_activas',  $notifActivas);

        $this->setFlash('success', 'Configuración del sistema guardada exitosamente.');
        $this->redirect('/perfil?tab=sistema');
    }
}
