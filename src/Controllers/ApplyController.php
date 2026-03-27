<?php

namespace App\Controllers;

use Twig\Environment;

class ApplyController
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function index(): void
    {
        // 1. If the user submitted the form (POST request)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Here is where we would normally INSERT into the database.
            // For now, we simulate success and redirect to "Mes Candidatures"
            header('Location: /index.php?page=mes_candidatures&status=success');
            exit;
        }

        // 2. If it's a normal visit (GET request), get the job info from the URL
        $poste = $_GET['poste'] ?? 'Offre inconnue';
        $entreprise = $_GET['entreprise'] ?? 'Entreprise inconnue';

        // 3. Show the form
        echo $this->twig->render('apply.html.twig', [
            'current_page' => 'offers',
            'poste' => $poste,
            'entreprise' => $entreprise
        ]);
    }
}