<?php

namespace App\Controllers;

use App\Models\OfferModel;
use App\Models\WishlistModel;
use Twig\Environment;

class HomeController
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
        $latestOffers = $this->offerModel->getLatestOffers(4);

        // Récupère les IDs en wishlist si étudiant connecté
        $wishlistIds = [];
        if (isset($_SESSION['user_id']) && ($_SESSION['role'] ?? '') === 'etudiant') {
            $profileId = $this->wishlistModel->getOrCreateProfile($_SESSION['user_id']);
            $wishlistIds = $this->wishlistModel->getWishlistIds($profileId);
        }

        echo $this->twig->render('home.html.twig', [
            'current_page' => 'accueil',
            'latestOffers' => $latestOffers,
            'wishlistIds' => $wishlistIds,
        ]);
    }
}