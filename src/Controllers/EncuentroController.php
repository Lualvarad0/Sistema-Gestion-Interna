<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\{Encuentro, Notificacion};

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

        $data['latitud']  = !empty($_POST['latitud'])  ? (float) $_POST['latitud']  : null;
        $data['longitud'] = !empty($_POST['longitud']) ? (float) $_POST['longitud'] : null;

        Encuentro::create($data);

        Notificacion::notify(
            'Nuevo encuentro ciudadano',
            "Encuentro en {$data['parroquia']} con {$data['nombrecontacto']} — estado: {$data['estado']}.",
            'success',
            'encuentros'
        );

        $this->setFlash('success', 'Encuentro ciudadano registrado exitosamente.');
        $this->redirect('/encuentros');
    }
}
