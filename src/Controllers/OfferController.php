<?php

namespace App\Controllers;

use App\Models\OfferModel;
use App\Models\WishlistModel;
use Twig\Environment;

class OfferController
{
    private Environment $twig;
    private OfferModel $offerModel;
    private WishlistModel $wishlistModel;

    public function __construct(Environment $twig, \PDO $bdd)
    {
        $this->twig = $twig;
        $this->offerModel = new OfferModel($bdd);
        $this->wishlistModel = new WishlistModel($bdd);
    }

    public function index(): void
    {
        $search = trim($_GET['search'] ?? '');
        $location = trim($_GET['location'] ?? '');
        $types = is_array($_GET['types'] ?? null) ? array_map('trim', $_GET['types']) : [];
        $levels = is_array($_GET['levels'] ?? null) ? array_map('trim', $_GET['levels']) : [];
        $categories = is_array($_GET['categories'] ?? null) ? array_map('trim', $_GET['categories']) : [];

        $allOffers = $this->offerModel->searchOffers($search, $location, $types, $levels, $categories);
        $totalPages = $this->offerModel->totalPages($allOffers);
        $pageCourante = max(1, min((int) ($_GET['p'] ?? 1), $totalPages ?: 1));
        $offers = $this->offerModel->getPage($allOffers, $pageCourante);
        $pages = $this->offerModel->getPageNumbers($pageCourante, $totalPages ?: 1);

        // Récupère les IDs en wishlist si étudiant connecté
        $wishlistIds = [];
        if (isset($_SESSION['user_id']) && ($_SESSION['role'] ?? '') === 'etudiant') {
            $profileId = $this->wishlistModel->getOrCreateProfile($_SESSION['user_id']);
            $wishlistIds = $this->wishlistModel->getWishlistIds($profileId);
        }

        echo $this->twig->render('pages/offer.html.twig', [
            'current_page' => 'offers',
            'offers' => $offers,
            'totalOffers' => count($allOffers),
            'pageCourante' => $pageCourante,
            'totalPages' => $totalPages,
            'pages' => $pages,
            'search' => $search,
            'location' => $location,
            'types' => $types,
            'levels' => $levels,
            'categories' => $categories,
            'wishlistIds' => $wishlistIds, // ← tableau des IDs en favori
        ]);
    }
}