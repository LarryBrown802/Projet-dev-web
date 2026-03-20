<?php

namespace App\Controllers;

use Twig\Environment;

class PilotAdminController
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function index(): void
    {
        echo $this->twig->render('pilot_admin.html.twig', [
            'current_page' => 'pilot',
        ]);
        
    }
}