<?php

namespace App\Controllers;

use App\Models\OfferModel;
use Twig\Environment;

class HomeController
{
    private Environment $twig;
    private OfferModel $offerModel;

    public function __construct(Environment $twig, \PDO $bdd)
    {
        $this->twig = $twig;
        $this->offerModel = new OfferModel($bdd);
    }

    public function index(): void
    {
        $latestOffers = $this->offerModel->getLatestOffers(4);

        echo $this->twig->render('home.html.twig', [
            'current_page' => 'accueil',
            'latestOffers' => $latestOffers
        ]);
    }
}