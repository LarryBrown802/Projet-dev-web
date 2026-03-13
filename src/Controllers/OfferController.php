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
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $location = isset($_GET['location']) ? trim($_GET['location']) : '';

        $types = isset($_GET['types']) && is_array($_GET['types'])
            ? array_map('trim', $_GET['types'])
            : [];

        $levels = isset($_GET['levels']) && is_array($_GET['levels'])
            ? array_map('trim', $_GET['levels'])
            : [];

        $categories = isset($_GET['categories']) && is_array($_GET['categories'])
            ? array_map('trim', $_GET['categories'])
            : [];

        $offers = $this->offerModel->searchOffers(
            $search,
            $location,
            $types,
            $levels,
            $categories
        );

        echo $this->twig->render('offer.html.twig', [
            'current_page' => 'offres',
            'offers' => $offers,
            'search' => $search,
            'location' => $location,
            'types' => $types,
            'levels' => $levels,
            'categories' => $categories
        ]);
    }
}