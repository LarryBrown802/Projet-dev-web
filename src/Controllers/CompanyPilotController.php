<?php

namespace App\Controllers;

use Twig\Environment;

class CompanyPilotController
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function index(): void
    {
        echo $this->twig->render('company_pilot.html.twig', [
            'current_page' => 'company_pilot',
        ]);
        
    }
}