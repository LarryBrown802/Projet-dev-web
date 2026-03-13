<?php
namespace App\Controllers;

class AdminController {
    
    private $twig;

    public function __construct($twig) {
        $this->twig = $twig;
    }

    public function dashboard() {
        // SECURITY GATE: Check if the user is logged in AND is an admin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            // If not, kick them back to the login page!
            header('Location: /connexion');
            exit;
        }

        // If they pass the security check, show them the dashboard
        echo $this->twig->render('dashboardAdmin.html.twig');
    }
}