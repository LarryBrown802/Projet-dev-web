<?php

namespace App\Controllers;

use Twig\Environment;

class CompanyController
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function index(): void
    {
        echo $this->twig->render('company.html.twig', [
            'current_page' => 'company',
        ]);
        
    }
}