<?php

namespace App\Controllers;

use Twig\Environment;

class ConnexionController
{
    private Environment $twig;

    private array $users = [
        // Admins
        ['id' => 1, 'email' => 'admin1@test.com',  'password' => 'admin123',   'role' => 'admin'],
        ['id' => 2, 'email' => 'admin2@test.com',  'password' => 'admin456',   'role' => 'admin'],

        // Pilotes
        ['id' => 3, 'email' => 'pilote1@test.com', 'password' => 'pilote123',  'role' => 'pilote'],
        ['id' => 4, 'email' => 'pilote2@test.com', 'password' => 'pilote456',  'role' => 'pilote'],

        // Etudiants
        ['id' => 5, 'email' => 'etudiant@test.com','password' => 'etudiant123','role' => 'etudiant'],
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
                $_SESSION['role'] = $found['role'];
                $_SESSION['email'] = $found['email'];
                $_SESSION['id'] = $found['id'];
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
