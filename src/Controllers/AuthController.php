<?php
namespace App\Controllers;

class AuthController {
    
    private $twig;

    public function __construct($twig) {
        $this->twig = $twig;
    }

    // Handles the GET request: Displays the login page
    public function connexion() {
        echo $this->twig->render('connexion.html.twig');
    }

    // Handles the POST request: Processes the form submission
    public function loginProcess() {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        // 1. Check pour l'Administrateur
        if ($email === 'admin@viacesi.fr' && $password === 'admin123') {
            $_SESSION['user'] = [
                'role' => 'admin',
                'email' => $email
            ];
            header('Location: /dashboard-admin');
            exit;
        } 
        // 2. Check pour le Pilote (NOUVEAU)
        elseif ($email === 'pilote@viacesi.fr' && $password === 'pilote123') {
            $_SESSION['user'] = [
                'role' => 'pilote',
                'email' => $email
            ];
            header('Location: /dashboard-pilote');
            exit;
        } 
        // 3. Échec de la connexion
        else {
            header('Location: /connexion');
            exit;
        }
    }

    // Handles the GET request: Logs the user out
    public function deconnexion() {
        // 1. Empty all session variables
        session_unset();
        
        // 2. Destroy the session
        session_destroy();
        
        // 3. Redirect back to the public home page
        header('Location: /');
        exit;
    }

}