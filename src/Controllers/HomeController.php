<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\{Colegio, Cnel, Encuentro, Acta, User, Notificacion};

class HomeController extends BaseController
{
    public function index(): void
    {
        $this->requireAuth();

        $userId = (int) ($_SESSION['user']['id'] ?? 0);

        $this->render('home/index', [
            'pageTitle'      => 'Dashboard',
            'stats'          => [
                'colegios'   => Colegio::count(),
                'cnel'       => Cnel::count(),
                'encuentros' => Encuentro::count(),
                'actas'      => Acta::count(),
                'usuarios'   => User::count(),
            ],
            'recentActivity' => Notificacion::recent($userId, 6),
            'unreadCount'    => Notificacion::unreadCount($userId),
        ]);
    }
}
