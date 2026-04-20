<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\{Colegio, Cnel};

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
        ];

        foreach (['nombreinstitucion', 'rector', 'direccion', 'telefono', 'distrito'] as $field) {
            if ($data[$field] === '') {
                $this->setFlash('error', 'Todos los campos obligatorios deben completarse.');
                $this->redirect('/colegios/nuevo');
            }
        }

        Colegio::create($data);
        $this->setFlash('success', 'Colegio registrado exitosamente.');
        $this->redirect('/colegios');
    }
}
