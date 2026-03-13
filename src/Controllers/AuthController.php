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
        // 1. Get the data from the form (using the 'name' attributes from your HTML)
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        // 2. DUMMY CHECK (We will replace this with a real Database query later)
        if ($email === 'admin@viacesi.fr' && $password === 'admin123') {
            
            // 3. Login successful! Save user data in the Session
            $_SESSION['user'] = [
                'role' => 'admin',
                'email' => $email
            ];
            
            // 4. Redirect to the Admin Dashboard
            header('Location: /dashboard-admin');
            exit;
            
        } else {
            // Login failed! Redirect back to the login page
            // (Later, we will add a nice error message here)
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