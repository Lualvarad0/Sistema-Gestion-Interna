<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Notificacion;

class NotificacionController extends BaseController
{
    public function index(): void
    {
        $this->requireAuth();

        $userId = (int) ($_SESSION['user']['id'] ?? 0);

        $this->render('notificaciones/index', [
            'pageTitle'      => 'Notificaciones',
            'notificaciones' => Notificacion::forUser($userId),
        ]);
    }

    public function markRead(string $id): void
    {
        $this->requireAuth();
        verifyCsrf();

        Notificacion::markRead((int) $id);
        $this->redirect('/notificaciones');
    }

    public function markAllRead(): void
    {
        $this->requireAuth();
        verifyCsrf();

        $userId = (int) ($_SESSION['user']['id'] ?? 0);
        Notificacion::markAllRead($userId);

        $this->setFlash('success', 'Todas las notificaciones marcadas como leídas.');
        $this->redirect('/notificaciones');
    }
}
