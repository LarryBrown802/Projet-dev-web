<?php

namespace App\Controllers;

use App\Models\OfferManagementModel;
use Twig\Environment;

class OfferManagementController
{
    private Environment $twig;
    private OfferManagementModel $offerModel;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
        $this->offerModel = new OfferManagementModel(); 
    }

    public function index(): void
    {
        // Admin voit toutes les offres, pilote voit seulement les siennes
        if ($_SESSION['role'] === 'admin') {
            $allOffers = $this->offerModel->getAllOffers();
        } else {
            $allOffers = $this->offerModel->getOffersByPilot($_SESSION['id']);
        }

        $totalPages = $this->offerModel->totalPages($allOffers);
        $pageCourante = max(1, min((int) ($_GET['p'] ?? 1), $totalPages ?: 1));
        $offers = $this->offerModel->getPage($allOffers, $pageCourante);
        $pages = $this->offerModel->getPageNumbers($pageCourante, $totalPages ?: 1);

        echo $this->twig->render('offer_management.html.twig', [
            'current_page' => 'offer_management',
            'offers' => $offers,
            'pageCourante' => $pageCourante,
            'totalPages' => $totalPages,
            'pages' => $pages,
        ]);
    }
}