<?php

namespace App\Controllers;

use Twig\Environment;

class CompanyAdminController
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function index(): void
    {
        echo $this->twig->render('company_admin.html.twig', [
            'current_page' => 'company',
        ]);
        
    }
}