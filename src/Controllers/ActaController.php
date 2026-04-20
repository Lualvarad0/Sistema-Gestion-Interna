<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Acta;

class ActaController extends BaseController
{
    public function report(): void
    {
        $this->requireAuth();

        $this->render('actas/report', [
            'pageTitle' => 'Actas de Trabajadores',
            'actas'     => Acta::all(),
        ]);
    }

    public function form(): void
    {
        $this->requireAuth();

        $this->render('actas/form', [
            'pageTitle' => 'Registrar Trabajador',
        ]);
    }

    public function store(): void
    {
        $this->requireAuth();
        verifyCsrf();

        $cedula = trim($_POST['cedula'] ?? '');

        if (Acta::existsByCedula($cedula)) {
            $this->setFlash('error', 'Ya existe un trabajador registrado con esa cédula.');
            $this->redirect('/actas/nuevo');
        }

        $data = [
            'codtrabajador'    => (int) ($_POST['codtrabajador'] ?? 0),
            'nombretrabajador' => trim($_POST['nombretrabajador'] ?? ''),
            'cedula'           => $cedula,
            'parroquia'        => trim($_POST['parroquia'] ?? ''),
        ];

        foreach ($data as $value) {
            if ($value === '' || $value === 0) {
                $this->setFlash('error', 'Todos los campos son requeridos.');
                $this->redirect('/actas/nuevo');
            }
        }

        Acta::create($data);
        $this->setFlash('success', 'Trabajador registrado en el acta exitosamente.');
        $this->redirect('/actas');
    }
}
