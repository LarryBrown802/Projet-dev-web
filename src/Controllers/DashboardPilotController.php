<?php

namespace App\Controllers;

use Twig\Environment;

class DashboardPilotController
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function index(): void
    {
        echo $this->twig->render('dashboard_pilot.html.twig', [
            'current_page' => 'dashboard_pilot',
        ]);
        
    }
}