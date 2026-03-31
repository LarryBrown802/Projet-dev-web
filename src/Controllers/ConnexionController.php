<?php

namespace App\Controllers;

use App\Models\UserModel;
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
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email    = trim($_POST['email']    ?? '');
            $password = trim($_POST['password'] ?? '');

            $user = $this->userModel->findByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['id_user'] = $user['ID_user'];
                $_SESSION['email']   = $user['email'];
                $_SESSION['role']    = $user['name_role'];

                session_write_close();

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