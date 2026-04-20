<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\{Colegio, Cnel, Notificacion};

class ColegioController extends BaseController
{
    public function report(): void
    {
        $this->requireAuth();

        $this->render('colegios/report', [
            'pageTitle' => 'Colegios',
            'colegios'  => Colegio::all(),
        ]);
    }

    public function form(): void
    {
        $this->requireAuth();

        $this->render('colegios/form', [
            'pageTitle'   => 'Registrar Colegio',
            'cnelOptions' => Cnel::allForSelect(),
        ]);
    }

    public function store(): void
    {
        $this->requireAuth();
        verifyCsrf();

        $data = [
            'nombreinstitucion' => trim($_POST['nombreinstitucion'] ?? ''),
            'rector'            => trim($_POST['rector'] ?? ''),
            'direccion'         => trim($_POST['direccion'] ?? ''),
            'telefono'          => trim($_POST['telefono'] ?? ''),
            'distrito'          => trim($_POST['distrito'] ?? ''),
            'idregistrocnel'    => !empty($_POST['idregistrocnel']) ? (int) $_POST['idregistrocnel'] : null,
            'latitud'           => !empty($_POST['latitud'])  ? (float) $_POST['latitud']  : null,
            'longitud'          => !empty($_POST['longitud']) ? (float) $_POST['longitud'] : null,
        ];

        foreach (['nombreinstitucion', 'rector', 'direccion', 'telefono', 'distrito'] as $field) {
            if ($data[$field] === '') {
                $this->setFlash('error', 'Todos los campos obligatorios deben completarse.');
                $this->redirect('/colegios/nuevo');
            }
        }

        Colegio::create($data);

        Notificacion::notify(
            'Nuevo colegio registrado',
            "Se registró la institución '{$data['nombreinstitucion']}' en el distrito {$data['distrito']}.",
            'success',
            'colegios'
        );

        $this->setFlash('success', 'Colegio registrado exitosamente.');
        $this->redirect('/colegios');
    }
}
