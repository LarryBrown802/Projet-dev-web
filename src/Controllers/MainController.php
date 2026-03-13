<?php
namespace App\Controllers;

class MainController {
    
    private $twig;

    public function __construct($twig) {
        $this->twig = $twig;
    }

    public function accueil() {
        // On demande à Twig d'afficher la page d'accueil
        echo $this->twig->render('accueil.html.twig', []);
    }
}