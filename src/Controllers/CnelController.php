<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\{Cnel, Acta};

class CnelController extends BaseController
{
    public function report(): void
    {
        $this->requireAuth();

        $this->render('cnel/report', [
            'pageTitle' => 'Registros CNEL',
            'registros' => Cnel::all(),
        ]);
    }

    public function form(): void
    {
        $this->requireAuth();

        $this->render('cnel/form', [
            'pageTitle'    => 'Registrar CNEL',
            'trabajadores' => Acta::allForSelect(),
        ]);
    }

    public function store(): void
    {
        $this->requireAuth();
        verifyCsrf();

        $data = [
            'nombreinstitucion' => trim($_POST['nombreinstitucion'] ?? ''),
            'nuevasluminarias'  => (int) ($_POST['nuevasluminarias'] ?? 0),
            'mantenimiento'     => (int) ($_POST['mantenimiento'] ?? 0),
            'tipo'              => trim($_POST['tipo'] ?? ''),
            'cantidad'          => (int) ($_POST['cantidad'] ?? 0),
            'estado'            => trim($_POST['estado'] ?? ''),
            'distrito'          => trim($_POST['distrito'] ?? ''),
            'codtrabajador'     => !empty($_POST['codtrabajador']) ? (int) $_POST['codtrabajador'] : null,
            'nombretrabajador'  => trim($_POST['nombretrabajador'] ?? ''),
        ];

        if ($data['nombreinstitucion'] === '') {
            $this->setFlash('error', 'El nombre de la institución es requerido.');
            $this->redirect('/cnel/nuevo');
        }

        Cnel::create($data);
        $this->setFlash('success', 'Registro CNEL guardado exitosamente.');
        $this->redirect('/cnel');
    }
}
