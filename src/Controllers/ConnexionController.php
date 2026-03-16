<?php

namespace App\Controllers;

use Twig\Environment;

class ConnexionController
{
    private Environment $twig;

    private array $users = [
        ['email' => 'admin@test.com', 'password' => 'admin123', 'role' => 'admin'],
        ['email' => 'pilote@test.com', 'password' => 'pilote123', 'role' => 'pilote'],
        ['email' => 'etudiant@test.com', 'password' => 'etudiant123', 'role' => 'etudiant']
    ];

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function index(): void
    {
        session_start();
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            //Cherche l'utilisateur dans les faux comptes créers au dessus
            $found = null;
            foreach ($this->users as $user) {
                if ($user['email'] === $email && $user['password'] === $password) {
                    $found = $user;
                    break;
                }
            }
            if ($found) {
                $_SESSION['role']  = $found['role'];
                $_SESSION['email'] = $found['email'];
                header('Location: /index.php?page=accueil');
                exit;
            }

            $error = 'Identifiants incorrects.';
        }

        echo $this->twig->render('connexion.html.twig', [
            'current_page' => 'connexion',
            'error' => $error
        ]);
    }
}
