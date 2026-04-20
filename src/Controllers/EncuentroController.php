<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Encuentro;

class EncuentroController extends BaseController
{
    public function report(): void
    {
        $this->requireAuth();

        $this->render('encuentros/report', [
            'pageTitle'  => 'Encuentros Ciudadanos',
            'encuentros' => Encuentro::all(),
        ]);
    }

    public function form(): void
    {
        $this->requireAuth();

        $this->render('encuentros/form', [
            'pageTitle' => 'Registrar Encuentro Ciudadano',
        ]);
    }

    public function store(): void
    {
        $this->requireAuth();
        verifyCsrf();

        $required = ['direccion', 'parroquia', 'estado', 'nombrecontacto', 'cedula', 'telefono'];
        $data     = [];

        foreach ($required as $field) {
            $data[$field] = trim($_POST[$field] ?? '');
            if ($data[$field] === '') {
                $this->setFlash('error', 'Todos los campos son requeridos.');
                $this->redirect('/encuentros/nuevo');
            }
        }

        Encuentro::create($data);
        $this->setFlash('success', 'Encuentro ciudadano registrado exitosamente.');
        $this->redirect('/encuentros');
    }
}
