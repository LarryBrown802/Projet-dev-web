<?php

namespace App\Controllers;

use App\Models\UserModel; // NOUVEAU : On importe le modèle
use Twig\Environment;

class ConnexionController
{
    private Environment $twig;
    private \PDO $bdd;
    private UserModel $userModel;

    public function __construct(Environment $twig, \PDO $bdd)
    {
        $this->twig      = $twig;
        $this->bdd       = $bdd;
        $this->userModel = new UserModel($bdd);
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
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email    = trim($_POST['email']    ?? '');
            $password = trim($_POST['password'] ?? '');

            $user = $this->userModel->findByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['ID_user'];
                $_SESSION['email']   = $user['email'];
                $_SESSION['role']    = $user['name_role'];

                session_write_close(); // Assure que les données de session sont enregistrées avant la redirection
                switch ($user['name_role']) {
                    case 'administrateur':
                        header('Location: /index.php?page=dashboard_admin');
                        break;
                    case 'pilote':
                        header('Location: /index.php?page=dashboard_pilot');
                        break;
                    case 'etudiant':
                    default:
                        header('Location: /index.php?page=accueil');
                        break;
                }
                exit;
            }

            $error = 'Email ou mot de passe incorrect.';
        }

        echo $this->twig->render('connexion.html.twig', [
            'current_page' => 'connexion',
            'error'        => $error,
        ]);
    }
}