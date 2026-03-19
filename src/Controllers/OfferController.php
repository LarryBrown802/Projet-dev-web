<?php

namespace App\Controllers;

use App\Models\OfferModel;
use Twig\Environment;

class OfferController
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
        $search     = trim($_GET['search']   ?? '');
        $location   = trim($_GET['location'] ?? '');
        $types      = is_array($_GET['types']      ?? null) ? array_map('trim', $_GET['types'])      : [];
        $levels     = is_array($_GET['levels']     ?? null) ? array_map('trim', $_GET['levels'])     : [];
        $categories = is_array($_GET['categories'] ?? null) ? array_map('trim', $_GET['categories']) : [];

        $allOffers    = $this->offerModel->searchOffers($search, $location, $types, $levels, $categories);
        $totalPages   = $this->offerModel->totalPages($allOffers);
        $pageCourante = max(1, min((int) ($_GET['p'] ?? 1), $totalPages ?: 1));
        $offers       = $this->offerModel->getPage($allOffers, $pageCourante);

        echo $this->twig->render('offer.html.twig', [
            'current_page' => 'offers',
            'offers'       => $offers,
            'totalOffers'  => count($allOffers),
            'pageCourante' => $pageCourante,
            'totalPages'   => $totalPages,
            'search'       => $search,
            'location'     => $location,
            'types'        => $types,
            'levels'       => $levels,
            'categories'   => $categories,
        ]);
    }
}