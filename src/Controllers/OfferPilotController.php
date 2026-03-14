<?php

namespace App\Controllers;

use Twig\Environment;

class OfferPilotController
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function index(): void
    {
        echo $this->twig->render('offer_pilot.html.twig', [
            'current_page' => 'offer_pilot',
        ]);
        
    }
}