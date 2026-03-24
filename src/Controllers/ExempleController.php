<?php
namespace App\Controllers;

use App\Models\ExempleModel;

class ExempleController {
    public function index($twig) {
        $model = new ExempleModel();
        $data  = $model->getAll();

        echo $twig->render('exemple.html.twig', ['items' => $data]);
    }
}