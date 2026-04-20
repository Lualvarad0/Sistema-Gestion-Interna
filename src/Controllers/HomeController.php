<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\{Colegio, Cnel, Encuentro, Acta};

class HomeController extends BaseController
{
    public function index(): void
    {
        $this->requireAuth();

        $this->render('home/index', [
            'pageTitle' => 'Dashboard',
            'stats'     => [
                'colegios'   => Colegio::count(),
                'cnel'       => Cnel::count(),
                'encuentros' => Encuentro::count(),
                'actas'      => Acta::count(),
            ],
        ]);
    }
}
