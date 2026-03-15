<?php

namespace App\Controllers;

use Twig\Environment;

class DashboardAdminController
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function index(): void
    {
        echo $this->twig->render('dashboard_admin.html.twig', [
            'current_page' => 'dashboard_admin',
        ]);
        
    }
}