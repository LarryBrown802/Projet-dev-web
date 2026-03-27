<?php

namespace App\Controllers;

use App\Models\UserModel; // NOUVEAU : On importe le modèle
use Twig\Environment;

class ConnexionController
{
    private Environment $twig;
    private UserModel $userModel;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
        $this->userModel = new UserModel(); // NOUVEAU : On initialise le modèle
    }

    public function index(): void
    {
        // Plus de session_start() ici, car il est déjà dans index.php !
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            // NOUVEAU : On cherche l'utilisateur directement dans la vraie base de données !
            $found = $this->userModel->authenticate($email, $password);

            if ($found) {
                // L'utilisateur existe et le mot de passe est bon
                $_SESSION['role'] = $found['role'];
                $_SESSION['email'] = $found['email'];
                $_SESSION['id'] = $found['id'];
                
                header('Location: /index.php?page=accueil');
                exit;
            }

            // Si $found est null, c'est que ça a échoué
            $error = 'Identifiants incorrects.';
        }

        echo $this->twig->render('connexion.html.twig', [
            'current_page' => 'connexion',
            'error' => $error
        ]);
    }
}