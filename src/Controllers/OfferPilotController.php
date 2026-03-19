<?php

namespace App\Controllers;

use App\Models\OfferModel;
use Twig\Environment;

class OfferPilotController
{
    private Environment $twig;
    private OfferModel $offerModel;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
        $this->offerModel = new OfferModel(); 
    }

    public function index(): void
    {
        $allOffers    = $this->offerModel->getAllOffers(); 
        $totalPages   = $this->offerModel->totalPages($allOffers);
        $pageCourante = max(1, min((int) ($_GET['p'] ?? 1), $totalPages ?: 1));
        $offers       = $this->offerModel->getPage($allOffers, $pageCourante);

        echo $this->twig->render('offer_pilot.html.twig', [
            'current_page' => 'offer_pilot',
            'offers'       => $offers,
            'pageCourante' => $pageCourante,
            'totalPages'   => $totalPages,
        ]);
    }
}