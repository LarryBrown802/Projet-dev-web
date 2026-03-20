<?php

namespace App\Controllers;

use Twig\Environment;

class OfferAdminController
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function index(): void
    {
        echo $this->twig->render('offer_admin.html.twig', [
            'current_page' => 'offer',
        ]);
        
    }
}